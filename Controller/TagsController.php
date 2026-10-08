<?php
App::uses('AppController', 'Controller');
/**
 * Tags Controller
 *
 * @property Tag $Tag
 * @property PaginatorComponent $Paginator
 */
class TagsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('search', 'getJSON');
	}
	
	public function search($text = null) {
		$this->autorender = false;
		$this->layout = 'ajax';
		$result = $this->Tag->find('all', array('recursive' => -1,
												'fields' => array('Tag.id', 'Tag.name'),
												'conditions' => array('Tag.name LIKE ' => $text.'%')));
		echo json_encode($result);
		exit;
	}

	/*public function getJSON() {
		$this->loadModel('Country');
		$states = $this->Country->find('all', array('fields' => array('id', 'name', 'alpha_3')));
		echo json_encode($states);
	}*/
}
