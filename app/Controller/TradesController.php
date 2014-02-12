<?php
App::uses('AppController', 'Controller');
/**
* Trades Controller
*
* @property Trade $Trade
* @property PaginatorComponent $Paginator
*/
class TradesController extends AppController {
/**
* Components
*
* @var array
*/
	public $components = array('Paginator');
    
    public $layout = 'user';

    /**
    * index method
    *
    * @return void
    */
	public function index($order_id=0) {
        $user_id = $this->UserAuth->getUserId();
		$this->Trade->recursive = 0;

        $query = $this->request->query;

        $conditions = array(
                'Order.user_id'=>$user_id,
        );
        if ($order_id) {
            $conditions['Order.id'] = $order_id;
        }
        if($query['ajax']){
            $this->layout = false;
        }
        if ($query['order_type']) {
            $conditions['Order.order_type'] = $query['order_type'];
        }
        $this->paginate = array(
            'conditions'=>$conditions,
            'order'=>'Trade.id desc'
        );  
        $data = $this->paginate('Trade');
        $trade_type = $this->Trade->Order->trade_type;
        $this->set('trade_type',$trade_type);
        $this->set('data',$data);
	}

    public function ajax_index($order_id=0)
    {
        $this->layout = false;
        $user_id = $this->UserAuth->getUserId();
        $this->Trade->recursive = 0;

        $query = $this->request->query;

        $conditions = array(
                'Order.user_id'=>$user_id,
        );
        if ($order_id) {
            $conditions['Order.id'] = $order_id;
        }
        $this->paginate = array(
            'conditions'=>$conditions,
            'order'=>'Trade.id desc'
        );  
        $data = $this->paginate('Trade');
        $this->set('data',$data);
    }

    public function run_shell()
    {   
        $this->autoRender = false;
        App::import('Console', 'Shell');
        App::import('Shell', 'Trade');
        $shell = new TradeShell();
        $shell->initialize();
        // $shell->loadTasks();
        $shell->run();
    }


    public function finish_shell()
    {   
        $this->autoRender = false;
        App::import('Console', 'Shell');
        App::import('Shell', 'Trade');
        $shell = new TradeShell();
        $shell->initialize();
        // $shell->loadTasks();
        $shell->finish();
    }

    /**
     * k线
     * @param 隔一天 隔5分钟 
     */
    public function k_line($value='')
    {
        # code...
    }
}