<?php
App::uses('AppModel', 'Model');
/**
 * Trade Model
 *
 */
class Trade extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'amount';

    /**
     * 获取每天统计
     */
    public function getStatis()
    {
        $this->recursive = -1;
        $data = $this->find('all',array(
            'fields'=>array(
                "date_format(FROM_UNIXTIME( `Trade`.`created`),'%y%m%d') as day",
                "COUNT(1) as count",
                'created',
            ),  
            'group'=>array('day'),
            'order'=>"Trade.created desc"
        ));
        $out = array();
        foreach ($data as $key => $one) {
            $time = $one['Trade']['created']*1000;
            $out[] = array( $time, (int)$one[0]['count'] );
        }
        $out = array_reverse($out);
        return $out;
    }

    /**
     * 价格统计
     */
    public function avgPrice($limit_day = 10)
    {
        $this->recursive = -1;
        $conditions = array();
        if($limit_day){
            $conditions['Trade.created >= '] = $limit_day*3600*24;
        }
        $data = $this->find('all',array(
            'conditions'=>$conditions,
            'fields'=>array(
                "date_format(FROM_UNIXTIME( `Trade`.`created`),'%Y-%m-%d') as day",
                "avg(`Trade`.`price`) as price_avg",
                'created',
            ),  
            'group'=>array('day'),
            'order'=>"Trade.created desc"
        ));
        $date_arr = array();
        foreach ($data as $key => $one) {
            $date_arr[$one[0]['day']] = $one[0]['price_avg'];
        }

        $out = array();
        for ($i=0; $i <= $limit_day; $i++) { 
            $current_day = date("Y-m-d",strtotime("-{$i} day"));
            $current_day_price = $date_arr[$current_day]?$date_arr[$current_day]:0;
            $out[] = array($i,$current_day_price);
        }   
        $out = array_reverse($out);
        return $out;
    }

    /**
     * 用户统计
     * @param $user_id
     */
    public function statis($user_id)
    {
        $this->recursive = 0;
        $data = $this->find('all',array(
            'fields'=>array(
                'count(Trade.id) as count',
                'sum(Trade.number) as number_sum',
                'sum(Trade.amount) as amount_sum',
                'avg(Trade.price) as price_avg',
                'Order.trade_type',
            ),
            'conditions'=>array('Order.user_id'=>$user_id),
            'group'=>array('Order.trade_type')
        ));
        $out = array();
        if ($data) {
            foreach ($data as $key => $one) {
                $out[$one['Order']['trade_type']] = $one[0];
            }
        }
        return $out;
    }

    /**
     * 最近的成交
     */
    public function getLast($order_type,$limit=20)
    {
        $conditions = array(
            'Order.order_type'=>$order_type //fortest
        );

        $limit = $limit?$limit:9999;
        return $this->find('all',array(
            'conditions'=>$conditions,
            'limit'=>$limit,
            'recursive'=>0,
            'order'=>'Order.id desc'
        ));
    }

    public function minMax($order_type)
    {
        $conditions = array(
            'Order.order_type'=>$order_type
        );
        $data = $this->find('first',
            array(
                'fields'=>array(
                    'min(Trade.price) as min_price',
                    'max(Trade.price) as max_price'
                ),
                'conditions'=>$conditions
            )
        );
        return $data[0];
    }

    /**
     * 根据市场统计价格走势
     */
    public function frontStatic($order_type,$limit_day=10)
    {
        $this->recursive = 0;
        $conditions = array(
            'Order.order_type'=>$order_type
        );
        if($limit_day){
            $conditions['Trade.created >= '] = $limit_day*3600*24;
        }
        $data = $this->find('all',array(
            'fields'=>array(
                "date_format(FROM_UNIXTIME( `Trade`.`created`),'%Y-%m-%d') as day",
                "avg(Trade.price) as price_avg",
                'created',
            ),  
            'conditions'=>$conditions,
            'group'=>array('day'),
            'order'=>"Trade.created desc"
        ));
        $date_arr = array();
        foreach ($data as $key => $one) {
            $date_arr[$one[0]['day']] = $one[0]['price_avg'];
        }

        $out = array();
        for ($i=0; $i <= $limit_day; $i++) { 
            $current_day = date("Y-m-d",strtotime("-{$i} day"));
            $time = strtotime($current_day)*1000;
            $current_day_price = $date_arr[$current_day]?$date_arr[$current_day]:0;
            $out[] = array( $time,round($current_day_price,8));
        }  
        $out = array_reverse($out);
        return $out;
    }


    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
