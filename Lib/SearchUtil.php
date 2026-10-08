<?php

class SearchUtil {
	public static function convertToQueryString($searchStr = '', $prefix = '%', $suffix = '%') {
		$searchList = array();
		if (!empty($searchStr)) {
			$searchList = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/", 
									 $searchStr, 
									 0, 
									 PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			
			for ($i = 0; $i < sizeof($searchList); $i++) { //add suffix and prefix
				$searchList[$i] = $prefix.$searchList[$i].$suffix;
			}
		} else {
			$searchList[0] = '%';
		}
		//return pipe-delimited string
		return implode('|', $searchList);
	}
}
?>