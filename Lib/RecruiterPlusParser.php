<?php
class RecruiterPlusParser {
	
	public $wsdl = 'http://jobsite.onlineresumeparser.com/rPlusParseResume.asmx?WSDL';
	
	public function parse($path=null) {
		$parsedXml = '<Resume><ParseSuccess>false</ParseSuccess></Resume>';
		if ($path) {
			try {
				@$stream = fopen($path, 'r');
				$content = stream_get_contents($stream);
				$filename = basename($path);
				$client = new SoapClient($this->wsdl, 
											array(
												  'encoding' => 'ISO-8859-1', 
												  'exceptions' => true,
												  'trace' => 1));
				CakeLog::write('debug', 'Begin RecruiterPlus parse :'.$filename);
				$hrxml = $client->Get_HRXML(
											array(
												  'B64FileZippedContent' => base64_encode($content), 
												  'FileName' => $filename, 
												  'secretKey' => 'lp39lb757fD5n5cI', 
												  'UserID' => 1));
				CakeLog::write('debug', 'End RecruiterPlus parse :'.$filename);
				$parsedXml = $hrxml->Get_HRXMLResult;
				$this->saveXml($parsedXml, $path.'.xml'); //save parsed XML
			} catch (SoapFault $sf) {
				CakeLog::write('error', 'SOAPFault->faultcode:'.$sf->faultcode);
				CakeLog::write('error', 'SOAPFault->detail:'.$sf->detail);
				$parsedXml = '<Resume><ParseSuccess>false</ParseSuccess><ParseError>'.$sf->detail.'</ParseError></Resume>';
			} catch (Exception $ex) {
				CakeLog::write('error', 'Exception:'.$ex);
			}
		}
		//CakeLog::write('debug', $parsedXml);
		return $parsedXml;
	}
	
/**
 * getCandidateFromParsedXml method
 *
 * @param string $xml parsed xml string
 * @param array $response response array to be populated from xml string
 */
	public function getCandidateFromParsedXml($xml=null, $response=array()) {
		$cakeXml = simplexml_load_string($xml);
		$parseSuccess = $cakeXml->xpath('//ParseSuccess');
		
		//if the ParseSuccess flag is set then the orginal parse  did not take place successfully
		//if the original parse was successful then there would be no such tag
		if (!$parseSuccess) {
			$node = $cakeXml->xpath('//PersonName');
			$response['firstname'] = (string) $node[0]->GivenName;
			$response['lastname'] = (string) $node[0]->MiddleName . ' ' . $node[0]->FamilyName;
			
			$node = $cakeXml->xpath('//PhoneBasic');
			$response['phone'] = (string) $node[0];
			
			$node = $cakeXml->xpath('//phone_Mobile');
			$response['phone_alt'] = (string) $node[0];
			
			$node = $cakeXml->xpath('//InternetEmailAddress');
			$response['email'] = (string) $node[0];
			
			$node = $cakeXml->xpath('//PostalAddress');
			$response['city'] = (string) $node[0]->City;
			$response['state'] = (string) $node[0]->State;
			$response['parseSuccess'] = true;
		}  else {
			$node = $cakeXml->xpath('//ParseError');
			if (!node) {
				$response['errors']['ParseError'] = (string) $node[0];
				$response['parseSuccess'] = false;
			}
		}
		return $response;
	}

/**
 * saveXml method
 *
 * @param string $xml parsed xml string
 * @param array $path path where the xml file needs to be saved
 */
	public function saveXml($xml='', $path=null) {
		if ($path) {
			try {
				$file = new File($path, true, 0777);
				$file->open('w');
				$file->write($xml);
				$file->close();
				return true;
			} catch (Exception $ex) {
				CakeLog::write('error', $ex->getMessage());
				return false;
			}
		}
		return false;
	}
}
?>