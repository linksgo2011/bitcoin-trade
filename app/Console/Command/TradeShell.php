<?php
ini_set("default_socket_timeout", 90);
ini_set('memory_limit', '-1');
set_time_limit(600);
/**
 * 自动交易脚本
 * @author neo email:120377843@qq.com
 * 买单优先算法
 * 思路 1\查询买单  2\查询符合价格的卖单(卖单根据价格排队) 2\如果找到,交易一次,流程交给下个买单
 */

class TradeShell extends AppShell {
     var $uses = array(
        'User',
        'Order',
        'Account',
        'Trade'
    );

     public function run()
     {
        //查买单
        $this->recursive = -1;
        $wait_deals = $this->Order->find('all',array(
            'recursive'=>-1,
            'conditions'=>array(
                'active'=>0,
                'trade_type'=>'buy',
                'number > 0',
                'amount_price > 0',
            ),
            'order'=>'id desc'
        ));
        if ($wait_deals) {
            foreach ($wait_deals as $key => $buy) {
                //查卖单
                $order_type = $buy['Order']['order_type'];
                $price = $buy['Order']['price'];
                $sell = $this->Order->find('first',array(
                    'recursive'=>-1,
                    'conditions'=>array(
                        'active'=>0,
                        'trade_type'=>'sell',
                        'price <='=>$price,
                        'order_type'=>$order_type,
                        'number > 0',
                        'amount_price > 0',
                    ),
                    'order'=>'price desc,id desc' //价格低的优先交易
                ));
                if($sell){
                    $db = $this->Order->getDataSource();
                    $db->begin();
                    try {
                        //处理交易 1\从冻结资金中支付给对方  2\支付手续费 3\减少2个order数量 4\写入日志
                        $deal_number = min($buy['Order']['number'],$sell['Order']['number']); //成交量
                        $price = $sell['Order']['price']; //成交价
                        $accout_arr = split('_',$buy['Order']['order_type']); 
                        $real_account_type = strtolower($accout_arr[0]); 
                        $bit_account_type = strtolower($accout_arr[1]);
                        $sys_user_id  = $this->User->getSys(); //系统账户,用于手续费
                        if($deal_number <= 0){
                            continue ;
                        }

                        //处理买家 1\更新order 2\更新账户 3\写trade 4\写扣除手续费到系统账户
                        $amount = $price*$deal_number;
                        $buy_user_id = $buy['Order']['user_id'];
                        $buy_fee = $this->User->feeRate($buy_user_id,$order_type)*$deal_number;

                        $this->Order->recursive = -1;
                        $this->Order->updateAll(array(
                            'number'=>"`number` - {$deal_number}",
                            'amount_price' => "`amount_price`-{$amount}"
                        ),array('Order.id'=>$buy['Order']['id']));
                        if($this->Order->getAffectedRows() != 1){
                            throw new Exception("order 更新错误!",1);
                        }

                        if($buy_fee > 0){
                            $this->Account->updateAll(array(
                                "{$bit_account_type}_balance"=>"`{$bit_account_type}_balance` + {$buy_fee}"
                                ),array(
                                "user_id"=>$sys_user_id
                            ));
                            if($this->Account->getAffectedRows() != 1){
                                throw new Exception("手续费更新错误!",2);
                            }
                        }

                        $this->Account->recursive = -1;
                        $buy_get = abs($deal_number- $buy_fee);
                        $this->Account->updateAll(array(
                            "{$bit_account_type}_balance"=>"`{$bit_account_type}_balance` + {$buy_get}",
                            "{$real_account_type}_balance_freeze"=>"`{$real_account_type}_balance_freeze` - {$amount}",
                        ),array('user_id'=>$buy_user_id));
                        
                        if($this->Account->getAffectedRows() != 1){
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
                        $this->Trade->create();
                        if(!$this->Trade->save($trade_data)){
                            throw new Exception("交易记录失败:".print_r($trade_data,true), 4);
                        }

                        //处理卖家
                        $amount = $price*$deal_number;
                        $sell_user_id = $sell['Order']['user_id'];
                        $sell_fee = $this->User->feeRate($sell_user_id,$order_type)*$amount;

                        $this->Order->recursive = -1;
                        $this->Order->updateAll(array(
                            'number'=>"`number` - {$deal_number}",
                            'amount_price' => "`amount_price`-{$amount}"
                        ),array('Order.id'=>$sell['Order']['id']));
                        if($this->Order->getAffectedRows() != 1){
                            throw new Exception("卖家订单更新错误!order_id:".$sell['Order']['id'],5);
                        }  

                        $this->Account->recursive = -1;
                        if ($sell_fee > 0) {
                            $this->Account->updateAll(array(
                                "{$real_account_type}_balance"=>"`{$real_account_type}_balance` + {$sell_fee}"
                                ),array(
                                "user_id"=>$sys_user_id 
                            ));
                            if($this->Account->getAffectedRows() != 1){
                                throw new Exception("手续费更新错误!order_id:".$sell['Order']['id'],6);
                            }
                        }

                        $sell_get = abs($amount-$sell_fee);
                        $this->Account->updateAll(array(
                            "{$real_account_type}_balance"=>"`{$real_account_type}_balance` + {$sell_get}",
                            "{$bit_account_type}_balance_freeze"=>"`{$bit_account_type}_balance_freeze` - {$deal_number}"
                        ),array('user_id'=>$sell_user_id));
                        if($this->Account->getAffectedRows() != 1){
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
                        $this->Trade->create();
                        $this->Trade->save($trade_data);
                        if(!$this->Trade->save($trade_data)){
                            throw new Exception("交易记录失败:".print_r($trade_data,true), 8);
                        }
                        $db->commit();
                    } catch (Exception $e) {
                        $this->log($e->getMessage());
                        $db->rollback();
                        echo $e->getMessage().'code:'.$e->getCode();
                    }
                }
            }
        }
        echo 'done';
     }


     /**
      * 订单完成脚本,订单数量0时标记为完成,并解除剩余amout被冻结的资金,不在交易中处理
      * 处理交易完成但是冻结的资金没有恢复
      * 1\标记订单完成 2\清空订单剩余净额 3\反冻结账户资金
      * @ln 
      */
     public function finish()
     {
        $this->Order->recursive = -1;
        $wait_deals = $this->Order->find('all',array(
            'recursive'=>-1,
            'conditions'=>array(
                'active'=>0,
                'number = 0',
            ),
            'order'=>'id desc'
        ));
        if ($wait_deals) {
            foreach ($wait_deals as $key => $one) {
                $order_type = $one['Order']['order_type'];
                $trade_type = $one['Order']['trade_type'];
                $amount = $one['Order']['amount_price'];
                $accout_type = $this->Order->getAccountType($order_type,$trade_type);
                
                $db = $this->Order->getDataSource();
                $db->begin();
                try {
                    $this->Order->id = $one['Order']['id'];
                    $this->Order->set('active','1');
                    $this->Order->set('amount_price',0);
                    if(!$this->Order->save()){
                        throw new Exception("标记订单完成失败".print_r($this->Order->validationErrors,true), 1);
                    }
                    if($amount_price > 0){
                        list($result,$msg) = $this->Account->unFreeze($one['Order']['user_id'],$accout_type,$amount);
                        if(!$result){
                            throw new Exception("反向冻结失败",2);
                        }
                    }
                    $db->commit();
                } catch (Exception $e) {
                    $db->rollback();
                    $this->log($e->getMessage());
                    echo $e->getMessage();
                }
            }   
        }
        echo 'done';
     }

}