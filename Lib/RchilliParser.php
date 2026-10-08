<?php
class RchilliParser {

	public static $wsdl = "http://java.rchilli.com/RChilliParser/services/RChilliParser?wsdl";
	
/**
 * parseFile method
 *
 * @param string $path path to file
 */
	public function parse($path = null) {
		//this is default parsed xml - if anything fails inbetween 
		//the calling function will see parseSuccess flag set to false
		$parsedXml = '<ResumeParserData><ParseSuccess>false</ParseSuccess></ResumeParserData>';
		if ($path) {
			try {
				@$stream = fopen($path, 'r'); //@ suppresses the errors during fopen
				$content = stream_get_contents($stream); //returns false on failure
				if ($content) {
					$filename = basename($path);
					$client = new SoapClient(RchilliParser::$wsdl, array("trace" => 1));
					//make the SOAP call to RChilli - bounded by log statements to measure performance
					CakeLog::write('debug', 'Begin RChilli parse :'.$filename);
					$hrxml = $client->parseResumeBinary(
													array(
														  'filedata' => base64_encode($content), 
														  'fileName' => $filename, 
														  'userkey' => '6XYY5Y6XLS2', 
														  'version' => '5.0.0', 
														  'subUserId' => 'sriraj'));
					CakeLog::write('debug', 'End RChilli parse :'.$filename);
					$parsedXml = $hrxml->return; //the RChilli SOAP object has a return method that needs to be invoked to get XML
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
 * @param string $xml parsed xml string
 * @param array $response response array to be populated from xml string
 */
	public function getCandidateFromParsedXml($xml=null, $response=array()) {
		//CakeLog::write('error', $xml);
		$cakeXml = Xml::build($xml);
		$resume = Xml::toArray($cakeXml);
		
		//if the ParseSuccess flag is set then the orginal parse  did not take place successfully
		//if the original parse was successful then there would be no such tag
		if (!isset($resume['ResumeParserData']['ParseSuccess'])) {
			$response['firstname'] = $resume['ResumeParserData']['FirstName'];
			$response['lastname'] = $resume['ResumeParserData']['LastName'];
			$response['phone'] = $resume['ResumeParserData']['Phone'];
			$response['altPhone'] = $resume['ResumeParserData']['Mobile'];
			$response['email'] = $resume['ResumeParserData']['Email'];
			$response['city'] = $resume['ResumeParserData']['City'];
			$response['state'] = $resume['ResumeParserData']['State'];
			$response['country'] = $resume['ResumeParserData']['Country'];
			$response['parseSuccess'] = true;
		}  else {
			if (isset($response['ResumeParserData']['ParseError'])) {
				$response['errors']['ParseError'] = $response['ResumeParserData']['ParseError'];
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