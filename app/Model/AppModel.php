<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	/**
	 * 最小验证器
	 */
	public function min($check,$min)
	{
		return current($check)>$min;
	}

    /**
     * 过滤数据
     *
     * @param string $options
     * @return void
     * @author Lin Yang
     */
    public function beforeValidate($options = array()) {
        if (isset($this->data[$this->name]) && is_array($this->data[$this->name])) {
            foreach ($this->data[$this->name] as &$value) {
                if (is_string($value)) {
                    $value = trim($value);
                }
            }
        }
        return true;
    }

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->name]) && is_array($this->data[$this->name])) {
            foreach ($this->data[$this->name] as &$value) {
                if (is_string($value)) {
                    $value = trim($value);
                }
            }
        }
        return true;
    }


    function updateAll($fields, $conditions = true) {
        $this->updateCounter();
        return parent::updateAll($fields, $conditions);
    }

    function afterDelete() {
        $this->updateCounter();
        parent::afterDelete();
    }

    function afterSave($created) {
        $this->updateCounter();
        parent::afterSave($created);
    }

    function updateCounter() {
        if ($this->cache) {
            $tag = $this->useTable ? : __CLASS__;
            Cache::write($tag, 1 + (int) Cache::read($tag));
        }
    }

    /**
     * 锁住表一行，防止脏数据，必须要用主建，以免全表锁。
     * 调用之前必须有 $db->begin();
     * @param type $primaryKeyValue
     * @return type
     */
    function rowLock($primaryKeyValue){
        if (!$primaryKeyValue || !$this->useTable || !$this->primaryKey) return ;
        $sql="select 1 from ".$this->useTable." where ".$this->primaryKey."='".$primaryKeyValue."' for update";
        return $this->query($sql);
    }
}
