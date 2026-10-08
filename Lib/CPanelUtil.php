<?php
App::import('Vendor', 'cpanel/xmlapi');
App::import('Vendor', 'cpanelUAPI', array('file' => 'cpanel/cpaneluapi.class.php'));

class CPanelUtil {

	public static function createEmail($username) {
		if ($username) {
			$xmlapi = new xmlapi(Configure::read('CPanel.ip'));
			$xmlapi->set_port(Configure::read('CPanel.port'));   
			$xmlapi->password_auth(Configure::read('CPanel.root_user'), Configure::read('CPanel.root_pass'));
			$xmlapi->set_output('json');
			$xmlapi->set_debug(1);
			$json = $xmlapi->api1_query(Configure::read('CPanel.root_user'), 'Email', 'addpop', array(
						$username, 
						Configure::read('CPanel.std_pass'), 
						Configure::read('CPanel.std_quota'), 
						Configure::read('CPanel.domain')
						)
					);
			CakeLog::debug($json);
			$response = json_decode($json, true);
			$result = array();
			if (isset($response['event']['result']) && (1 == $response['event']['result'])) {
				$result['success'] = true;
				if (isset($response['data']['result'])) {
					$result['email'] = $result['data']['result'];
				}
			} else {
				$result['success'] = false;
			}
			return $result;
		}
	}

	public static function createEmailForwarder($username) {
		if ($username) {
			$xmlapi = new xmlapi(Configure::read('CPanel.ip'));
			$xmlapi->set_port(Configure::read('CPanel.port'));   
			$xmlapi->password_auth(Configure::read('CPanel.root_user'), Configure::read('CPanel.root_pass'));
			$xmlapi->set_output('json');
			$xmlapi->set_debug(1);
			$json = $xmlapi->api2_query(Configure::read('CPanel.root_user'), 'Email', 'addforward', array(
						'email' => $username, 
						'fwdopt' => 'pipe', 
						'pipefwd' => Configure::read('CPanel.pipe_fwd'), 
						'domain' => Configure::read('CPanel.domain')
						)
					);
			CakeLog::debug($json);
			$response = json_decode($json, true);
			$result = array();
			if (isset($response['cpanelresult']['event']['result']) && (1 == $response['cpanelresult']['event']['result'])) {
				$result['success'] = true;
				if (isset($response['cpanelresult']['data'][0]['email'])) {
					$result['email'] = $response['cpanelresult']['data'][0]['email'];
				}
			} else {
				$result['success'] = false;
			}
			return $result;
		}
	}

	public static function deleteEmailForwarder($address, $forwarder) {
		$uapi = new cpanelUAPI(Configure::read('CPanel.root_user'), Configure::read('CPanel.root_pass'), Configure::read('CPanel.ip'));
		$uapi->scope = 'Email'; //use the email module
		$response = $uapi->delete_forwarder(array('address' => $address, 'forwarder' => $forwader));
		CakeLog::debug('deleteEmailForwarder:'.$address.':'.$forwarder.'::response::'.$response);
		if (0 == $response->status) {
			return array('success' => true);
		} else {
			return array('success' => false, 'message' => $response->messages);
		}
	}
}
?>