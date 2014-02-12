<?php
App::uses('AppModel', 'Model');
/**
 * Merchant Model
 *
 */
class Merchant extends AppModel {

	public $useTable = 'merchant';

    public $active_arr = array('0'=>'正常','-1'=>'关闭');

	public $displayField = 'id';

    public function getSet()
    {
        return $this->find('first',array(
            'fields'=>array(
                'Merchant.*',
            ),
            'recursive'=>-1
        ));
    }

}
