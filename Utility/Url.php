<?php 
class Url
{
	private $_valuePair ;
	function __construct() {
		$this->_valuePair = array();
	}

	function AddArray($value,$ignore = array())
	{
		foreach ($value as $key => $value) {
			if(!in_array($key,$ignore))
				$this->_valuePair[$key] = $value;
		}
	}

	function AddPair($key,$value)
	{
		$this->_valuePair[$key] = $value;
	}

	function Output($baseUrl = SITE_URL)
	{
		$lurl = $baseUrl . "?";
		$lfirst = true;
		foreach ($this->_valuePair as $key => $value) {
			if(!$lfirst)
				$lurl .= "&";
			$lurl .= $key . "=" . "$value";
			$lfirst = false;

		}
		return $lurl;
	}
}

?>