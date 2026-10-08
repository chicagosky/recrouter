<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
	
	public function unbindAll() { 
	    foreach(array('hasOne' => array_keys($this->hasOne), 
		              'hasMany' => array_keys($this->hasMany), 
		              'belongsTo' => array_keys($this->belongsTo), 
		              'hasAndBelongsToMany' => array_keys($this->hasAndBelongsToMany) 
				     ) as $relation => $model) {
			$this->unbindModel(array($relation => $model)); 
	    } 
	} 
	
/*
 * Incorporated concurrency check in the beforeSave callback method to ensure that data is not stale before user saves.
 * The function checks if the model has a `modified` field, before it proceeds. If the model does not have such a method
 * then concurrency does not apply to this data structure. Upon proceeding, the method checks to see if the value of modified
 * data is the same in the database as well as the request that invokes this method. If they are not same then the save is 
 * aborted
 */
	public function beforeSave($options = array()) {
		if ($this->hasField('modified') && isset($this->data[$this->name]['id']) && isset($this->data[$this->name]['modified'])) {
			CakeLog::debug('AppModel: beforeSave - inside concurrency check');
			CakeLog::debug(print_r($this->data, true));
			$this->recursive = -1;
			
			// run a select statement to ensure the modified date in the database has not changed. If it has changed then 
			// the below find query will return 0 rows
			$row = $this->find('first', array(
				'fields' => array(
					'id', 'modified'
				),
				'conditions' => array(
					'id' => $this->data[$this->name]['id'],
					'modified' => $this->data[$this->name]['modified']
				)
			));
			
			// if no row is returned then error out and return - basically a concurrency error has occurred
			if (!$row) {
				CakeLog::error($this->name.':Concurrency error - [row-id:'.$this->data[$this->name]['id'].']');
				return false;
			}
			
			// if a row was retrned then there is no concurrency error, so proceed but change the modified date
			// to current timestamp to reflect accuracy
			$this->data[$this->name]['modified'] = date('Y-m-d H:i:s');
			return true;
		}
	}
}
