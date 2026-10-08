<?php
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::import('Vendor', 'CloudConvert', array('file' => 'cloudconvert/CloudConvert.class.php'));

class UploadUtil {
	public static $cloudconvertAPIKey = 'pydirdO0yEw9xKVtzVE42oiXe47_ej7yzvw3RNennYJTCAycvbBIZqLEFGj3mMjsx_P_iPBr896yDFSXz5hA2g';
	
/**
 * uploadFile method
 *
 * @param array $file
 * @param boolean $isUpload
 * @param string $bulk
 */	
	public function uploadFile($file=null, $isUpload=true, $bulk=false) {
		$response = array();
		
		//if bulk upload then max size is 5MB else it is 1MB
		$maxSize = $bulk ? (5 * 1024 * 1024) : (1024 * 1024); 
		
		//if bulk upload then zip is also a valid extension
		$validExtensions = $bulk ? array('rtf', 'pdf', 'doc', 'docx', 'txt', 'html', 'zip') : 
											array('rtf', 'pdf', 'doc', 'docx', 'txt', 'html'); 
		if ($file) {
			$filename = $file['name'];
			$filesize = $file['size'];
			if (strlen($filename)) { 
				$extension = strtolower(substr($filename, strrpos($filename, '.')+1)); //file extension and then make it lowercase
				if (in_array($extension, $validExtensions)) { //check file extension
					if ($filesize < $maxSize) { //check-file size
						if ($file['error'] == UPLOAD_ERR_OK) {
							
							//get system assigned temp name & path, the full path where we want to move the file
							//execute the move based on whether it is a bulk upload or not
							$tmp = $file['tmp_name']; 
							$path = APP . 'Upload' . DS . 'tmp'. DS . $filename; //where we want to move the file
							
							if (($isUpload && move_uploaded_file($tmp, $path)) ||
												(!$isUpload && rename($tmp, $path))) {
									
								//conversion in real time happens only for single file uploads to save on performance and time outs
								//if the file is an upload but not a bulk upload then the conversion will take place in realtime
								//if the file is an upload but part of bulk upload then conversion will take place in the process loop
								if ($isUpload && !$bulk) {
									$conversion = $this->convertToPDF($path); //convert non PDF and ZIP to PDF
									if ($conversion['success']) {
										$filename = $conversion['filename'];
										$path = $conversion['path'];
									}
								}
								
								$response = array('success' => true,
												  'message' => 'The file was uploaded successfully', 
												  'filename' => $filename,
												  'path' => $path,
												  'data' => '/cake/candidates/getFileFor/-1/'.$filename);
							} else {
					 			$response = array('success' => false,
												  'message' => 'An error occurred during the move of '.$filename,
												  'filename' => $filename);
							}
						} else {
							$response = array('success' => false,
										  	  'message' => 'An error occurred during the upload of '.$filename,
											  'filename' => $filename);
						}
					} else {
						$response = array('success' => false,
										  'message' => $filename.' exceeds the maximum allowed file size of 2MB',
										  'filename' => $filename);
					}
				} else {
					$response = array('success' => false,
									  'message' => $filename.' has a file extension that is not supported',
									  'filename' => $filename);
				}
			} else {
				$response = array('success' => false,
								  'message' => $filename.' is an invalid filename',
								  'filename' => $filename);
			}
		}
		return $response;
	}

/**
 * processZipArchive method
 *
 * @param string $path Path to zip file
 * @return array 
 */	
	public function processZipArchive($path=null) {
		$response = array(); 
		if ($path) {
			$zip = new ZipArchive;
			if ($zip->open($path)) { //open the archive
				$tempPath = APP . 'Upload' . DS . 'tmp' . DS . 'zip_' . time(); //generated folder name & temp path
				$folder = new Folder($tempPath, true); //create a folder to hold extracted files
				$zip->extractTo($tempPath); //extract files to path
				$zip->close();
				
				$files = $folder->find('.*\.*'); //get all files recently extracted into temp folder
				foreach ($files as $file) {
					$file = new File($folder->pwd() . DS . $file);
					$fileArr = array('name' => $file->name, 'tmp_name' => $file->path, 'error' => 0, 'size' => $file->size());
					$res = $this->uploadFile($fileArr, false, false);
					array_push($response, $res);
				}
			}
		}
		return $response;
	}

/**
 * convertToPDF method
 *
 * @param string $path Path to file
 * @return array 
 */	
	public function convertToPDF($path=null) {
		$response = array();
		$filename = basename($path); 
		$name = substr($filename, 0, strrpos($filename, '.'));
		$extension = substr($filename, strrpos($filename, '.')+1);
		if ($extension == 'pdf' || $extension == 'zip') {
			return;
		} else {
			CakeLog::write('debug', 'Begin CloudConvert process - '.$filename);
			$process = CloudConvert::createProcess($extension, "pdf", UploadUtil::$cloudconvertAPIKey);
			//$process->setOption('email', true);
			$process->setOption('wait', true);
			$process->upload($path, "pdf" );
			
			if ($process->waitForConversion()) {
				$process->download(APP . 'Upload'. DS . 'tmp' . DS . $name . '.pdf');
				CakeLog::write('debug', 'End CloudConvert process - '.$filename);
				$response['success'] = true;
				$response['filename'] = $name . '.pdf';
				$response['path'] = APP . 'Upload'. DS . 'tmp' . DS . $name . '.pdf';
			}
		}
		return $response;
	}

	public function convertToPDF_REST($options = array(), $inputfile = NULL, $outputfile = NULL, &$result = NULL) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_URL, "https://api.cloudconvert.org/convert");
	    curl_setopt($ch, CURLOPT_POST, true);
	    if ($inputfile !== NULL) 
	        $options = array_merge(array('file' =>  '@' . $inputfile), $options);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
	    
	    // If you have SSL cert errors, try to disable SSL verifyer.
	    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	    
	    $output = curl_exec($ch);
	    
	    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	    $curlerr = curl_error($ch);
	    curl_close($ch);
	    
	    if ($curlerr && $result !== NULL) {
	        $result = array('error' => $curlerr);
	    } elseif (strpos($content_type, "application/json") === 0 && $result !== NULL) {
	        $result = @json_decode($output, true);
	    } elseif ($http_status == 200 && $outputfile !== NULL) {
	        $file = fopen($outputfile, "w+");
	        fputs($file, $output);
	        fclose($file);
	    }
	    
	    return $http_status == 200;
	}		
}
?>