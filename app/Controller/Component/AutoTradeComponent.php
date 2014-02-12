<?php

class AutoTradeComponent extends Component {

    public function initialize(Controller $controller)
    {
        $this->controller = $controller;
        $controller->loadModel('User');
        $controller->loadModel('Order');
        $controller->loadModel('Account');
        $controller->loadModel('Trade');
    }

    /**
     * 自己订单ID
     * 寻找的订单类型
     */
     public function run($order_id,$search_trade_type='sell')
     {
        //查买单
        $c = $this->controller;
        $c->recursive = -1;

        $me = $c->Order->findById($order_id);
        $order_type = $me['Order']['order_type'];

        $conditions = array(
                'active'=>0,
                'order_type'=>$order_type,
                'trade_type'=>$search_trade_type,
                'number > 0',
                'amount_price > 0',
        );
        if ($search_trade_type == 'buy') {
            $conditions['price >='] = $me['Order']['price'];
        }else{
            $conditions['price <='] = $me['Order']['price'];
        }
        $order = array();
        if ($search_trade_type == 'buy') {
            $order = "price desc,id asc";
        }else{
            $order = "price asc,id asc";
        }

        //找到符合条件的挂单
        $wait_deals = $c->Order->find('all',array(
            'recursive'=>-1,
            'conditions'=>$conditions,
            'order'=>$order
        ));
        // pr($me);
        // echo $search_trade_type;
        // pr($wait_deals);exit();
        if ($wait_deals) {
            $c->Order->rowLock($me['Order']['id']);
            foreach ($wait_deals as $key => $one){
                if ($search_trade_type == 'buy') {
                    $buy = $one;
                    $sell = $me;
                }else{
                    $buy = $me;
                    $sell = $one;
                }
                $c->Order->rowLock($one['Order']['id']);

                //处理交易 1\从冻结资金中支付给对方  2\支付手续费 3\减少2个order数量 4\写入日志
                $deal_number = min($buy['Order']['number'],$sell['Order']['number']); //成交量
                $me['Order']['number'] =  $me['Order']['number'] - $deal_number;

                $price = $one['Order']['price']; //成交按照找到的订单计算
                $accout_arr = split('_',$buy['Order']['order_type']); 
                $real_account_type = strtolower($accout_arr[0]); 
                $bit_account_type = strtolower($accout_arr[1]);
                $sys_user_id  = $c->User->getSys(); //系统账户,用于手续费
                if($deal_number <= 0){
                    continue ;
                }

                //处理买家 1\更新order 2\更新账户 3\写trade 4\写扣除手续费到系统账户
                $amount = $price*$deal_number;
                $buy_user_id = $buy['Order']['user_id'];
                $buy_fee = $c->User->feeRate($buy_user_id,$order_type)*$deal_number;

                $c->Order->recursive = -1;
                $c->Order->updateAll(array(
                    'number'=>"`number` - {$deal_number}",
                    'amount_price' => "`amount_price`-{$amount}"
                ),array('Order.id'=>$buy['Order']['id']));
                if($c->Order->getAffectedRows() != 1){
                    throw new Exception("order 更新错误!",1);
                }

                if($buy_fee > 0){
                    $c->Account->updateAll(array(
                        "{$bit_account_type}_balance"=>"`{$bit_account_type}_balance` + {$buy_fee}"
                        ),array(
                        "user_id"=>$sys_user_id
                    ));
                    if($c->Account->getAffectedRows() != 1){
                        throw new Exception("手续费更新错误!",2);
                    }
                }

                $c->Account->recursive = -1;
                $buy_get = abs($deal_number- $buy_fee);
                $c->Account->updateAll(array(
                    "{$bit_account_type}_balance"=>"`{$bit_account_type}_balance` + {$buy_get}",
                    "{$real_account_type}_balance_freeze"=>"`{$real_account_type}_balance_freeze` - {$amount}",
                ),array('user_id'=>$buy_user_id));
                
                if($c->Account->getAffectedRows() != 1){
                    throw new Exception("买家账户更新错误!",3);
                }

                $trade_data = array(
                    'order_id'=>$buy['Order']['id'],
                    'he_order_id'=>$sell['Order']['id'],
                    'number'=>$deal_number,
                    'price'=>$price,
                    'amount'=>$amount,
                    'fee'=>$buy_fee,
                    'created'=>time()
                );
                $c->Trade->create();
                if(!$c->Trade->save($trade_data)){
                    throw new Exception("交易记录失败:".print_r($trade_data,true), 4);
                }

                //处理卖家
                $amount = $price*$deal_number;
                $sell_user_id = $sell['Order']['user_id'];
                $sell_fee = $c->User->feeRate($sell_user_id,$order_type)*$amount;

                $c->Order->recursive = -1;
                $c->Order->updateAll(array(
                    'number'=>"`number` - {$deal_number}",
                    'amount_price' => "`amount_price`-{$amount}"
                ),array('Order.id'=>$sell['Order']['id']));
                if($c->Order->getAffectedRows() != 1){
                    throw new Exception("卖家订单更新错误!order_id:".$sell['Order']['id'],5);
                }  

                $c->Account->recursive = -1;
                if ($sell_fee > 0) {
                    $c->Account->updateAll(array(
                        "{$real_account_type}_balance"=>"`{$real_account_type}_balance` + {$sell_fee}"
                        ),array(
                        "user_id"=>$sys_user_id 
                    ));
                    if($c->Account->getAffectedRows() != 1){
                        throw new Exception("手续费更新错误!order_id:".$sell['Order']['id'],6);
                    }
                }

                $sell_get = abs($amount-$sell_fee);
                $c->Account->updateAll(array(
                    "{$real_account_type}_balance"=>"`{$real_account_type}_balance` + {$sell_get}",
                    "{$bit_account_type}_balance_freeze"=>"`{$bit_account_type}_balance_freeze` - {$deal_number}"
                ),array('user_id'=>$sell_user_id));
                if($c->Account->getAffectedRows() != 1){
                    throw new Exception("卖家账户更新错误!order_id:".$sell['Order']['id'],7);
                }
                $trade_data = array(
                    'order_id'=>$sell['Order']['id'],
                    'he_order_id'=>$buy['Order']['id'],
                    'number'=>$deal_number,
                    'price'=>$price,
                    'amount'=>$amount,
                    'fee'=>$sell_fee,
                    'created'=>time()
                );
                $c->Trade->create();
                $c->Trade->save($trade_data);
                if(!$c->Trade->save($trade_data)){
                    throw new Exception("交易记录失败:".print_r($trade_data,true), 8);
                }
            }
        }

        //清理挂单
        $this->finish();
     }


     /**
      * 订单完成脚本,订单数量0时标记为完成,并解除剩余amout被冻结的资金,不在交易中处理
      * 处理交易完成但是冻结的资金没有恢复
      * 1\标记订单完成 2\清空订单剩余净额 3\反冻结账户资金
      * @ln 
      */
     public function finish()
     {
        $c = $this->controller;
        $c->Order->recursive = -1;
        $wait_deals = $c->Order->find('all',array(
            'recursive'=>-1,
            'conditions'=>array(
                'active'=>0,
                'number*price != amount_price'
            ),
            'order'=>'id desc'
        ));
        if ($wait_deals) {
            foreach ($wait_deals as $key => $one) {
                $order_type = $one['Order']['order_type'];
                $trade_type = $one['Order']['trade_type'];
                $jieyu = $one['Order']['amount_price']-$one['Order']['price']*$one['Order']['number'];
                $jieyu = max(0,$jieyu);

                $accout_type = $c->Order->getAccountType($order_type,$trade_type);
                
                $db = $c->Order->getDataSource();
                $db->begin();
                try {
                    $c->Order->id = $one['Order']['id'];
                    $c->Order->set('amount_price',$one['Order']['price']*$one['Order']['number']);
                    if(!$c->Order->save()){
                        throw new Exception("标记订单完成失败".print_r($c->Order->validationErrors,true), 1);
                    }
                    if($jieyu > 0){
                        list($result,$msg) = $c->Account->unFreeze($one['Order']['user_id'],$accout_type,$jieyu);
                        if(!$result){
                            throw new Exception("反向冻结失败",2);
                        }
                    }
                    $db->commit();
                } catch (Exception $e) {
                    $db->rollback();
                    $c->log($e->getMessage());
                }
            }   
        }


        //标记完成
        $sql = "UPDATE orders set active=1 WHERE number = 0 AND active = 0 AND amount_price =0;";
        $c->Order->query($sql);
     }
}