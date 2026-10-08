<?php
App::uses('CakeEmail', 'Network/Email');

class Emailer {
	public static function sendEmail(array $data = null) {
		if (!$data) {
			return false;
		}
		
		$email = new CakeEmail();
		$email->config($data['config']);
		$email->to($data['to']);
		$email->viewVars($data['vars']);
		if (isset($data['template'])) {
			$email->template($data['template']);
		}
		try {
			if ($email->send()) {
				return true;
			}
		} catch(Exception $ex) {
			CakeLog::error('Unable to send email - '.$ex->getMessage());
		}
		
		return false;
	}
}
?>