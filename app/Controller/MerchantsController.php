<?php
App::uses('AppController', 'Controller');
/**
 * Merchants Controller
 *
 * @property Merchant $Merchant
 * @property PaginatorComponent $Paginator
 */
class MerchantsController extends AppController {

/**
 * Components
 *
 * @var array
 */
   public $components = array('Paginator');
    public $prefixLayout = true;

   public $layout = 'admin';
      
      public function admin_site()
      {  
         $active_arr = $this->Merchant->active_arr;
         $this->set(compact('active_arr'));
         if ($this->request->isPost() || $this->request->isPut()) {
            $post_data = $this->request->data;
            $result = $this->Merchant->save($post_data);
            if($result){
               $this->succ('保存成功!');
               $this->redirect($this->referer());
            }else{
               $this->error("保存失败!");
            }
         }else{
            $this->request->data = $this->Merchant->find('first');
         }
      }

      public function admin_account()
      {
         $this->_update();
      }

      public function admin_trade()
      {
         $this->_update();
      }

      public function admin_service()
      {
         $this->_update();
      }

      private function _update()
      {
         if ($this->request->isPost() || $this->request->isPut()) {
            $post_data = $this->request->data;
            $result = $this->Merchant->save($post_data);
            if($result){
               $this->succ('保存成功!');
               $this->redirect($this->referer());
            }else{
               $this->error("保存失败!");
            }
         }else{
            $this->request->data = $this->Merchant->find('first');
         }
      }

}
