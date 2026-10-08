<?php
class SovrenParser {

	public $wsdl = 'http://services.resumeparsing.com/ParsingService.asmx?WSDL';
/**
 * parseFile method
 *
 * @param string $path path to file
 */
	public function parse($path = null) {
		$parsedXml = '<Resume><ParseSuccess>false</ParseSuccess></Resume>';
		if ($path) {
			try {
				@$stream = fopen($path, 'r'); //@ suppresses the errors during fopen
				$content = stream_get_contents($stream); //returns false on failure
				if ($content) {
					$filename = basename($path);
					$client = new SoapClient($this->wsdl,  array('soap_version' => SOAP_1_2));
					CakeLog::write('debug', 'Begin Sovren parse :'.$filename);
					$hrxml = $client->ParseResume(array(
													'request' => array(
														'AccountId' => '31844301', // Replace this value with your assigned value.
														'ServiceKey' => 'UqRVmSLMGgp3ORy3vrtWI2w/73vHQts1p8JK17nb', // Replace this value with your assigned value.
														'FileBytes' => $content,
														//'FileText' => 'xxxxx', // Optionally pass this instead of FileBytes, but be careful of character encodings
														//'Configuration' => NULL, // See "Parser Config String Builder.xls"
														//'OutputXmlDoc' => FALSE,  // Set TRUE if you want inline XML instead of a full XML document 
														//'OutputHtml' => FALSE,  // Set TRUE if you want an HTML version of the original document
														//'OutputRtf' => FALSE,  // Set TRUE if you want an RTF version of the original document
														//'OutputWordXml' => FALSE,  // Set TRUE if you want a Word XML version of the original document
														//'RevisionDate' => '2001-04-07'   // Optional "as of" date when parsing old resumes
														)));
					CakeLog::write('debug', 'End Sovren parse :'.$filename);
					$parsedXml = $hrxml->ParseResumeResult->Xml;
				}
			} catch (SoapFault $sf) {
				CakeLog::write('error', 'SOAPFault->faultcode:'.$sf->faultcode);
				CakeLog::write('error', 'SOAPFault->detail:'.$sf->detail);
				$parsedXml = '<ResumeParserData><ParseSuccess>false</ParseSuccess><ParseError>'.$sf->detail.'</ParseError></ResumeParserData>';
			} catch (Exception $ex) {
				CakeLog::write('error', 'Exception:'.$ex);
			}
		}
		return $parsedXml;
	}

/**
 * getCandidateFromParsedXml method
 * 
 * In test mode do the following steps
 * 1. Comment the line - $cakeXml = simplexml_load_string($xml);
 * 2. Insert a line to effect - $cakeXml = $xml
 * 3. Insert CakeLog::write('error', $xml) if you want to debug the XML being passed in
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
			try {
				//register these namespaces to enable XPATH to function properly
				$cakeXml->registerXPathNamespace('hr', 'http://ns.hr-xml.org/2006-02-28');
				$cakeXml->registerXPathNamespace('sov', 'http://sovren.com/hr-xml/2006-02-28');
				
				$node = $cakeXml->xpath('//hr:PersonName');
				$response['firstname'] = isset($node[0]) ? (string) @$node[0]->GivenName : '';
				$response['lastname'] = isset($node[0]) ? (string) @$node[0]->MiddleName . ' ' . $node[0]->FamilyName : '';
				
				$node = $cakeXml->xpath('//hr:ContactMethod//hr:Telephone');
				$response['phone'] = isset($node[0]) ? (string) @$node[0]->FormattedNumber : '';
				
				$node = $cakeXml->xpath('//hr:ContactMethod//hr:Mobile');
				$response['phone_alt'] = isset($node[0]) ? (string) @$node[0]->FormattedNumber : '';
				
				$node = $cakeXml->xpath('//hr:ContactMethod//hr:InternetEmailAddress');
				$response['email'] = isset($node[0]) ? (string) @$node[0] : '';
				
				$node = $cakeXml->xpath('//hr:ContactMethod//hr:PostalAddress');
				$response['city'] = isset($node[0]) ? (string) @$node[0]->Municipality : '';
				$response['state'] = isset($node[0]) ? (string) @$node[0]->Region : '';
				
				$response['parseSuccess'] = true;
			} catch(Exception $ex) {
				CakeLog::write('error', 'Error while parsing XML - '.$ex->getMessage());
				$response['errors']['ParseError'] = (string) $node[0];
				$response['parseSuccess'] = false;
			}
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