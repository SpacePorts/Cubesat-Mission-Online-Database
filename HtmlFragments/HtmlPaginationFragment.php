<?php 
require_once "Fragment.php";
class HtmlPaginationFragment extends Fragment
{
	private $_bottom;
	private $_top;
	private $_page;

	private $_url;
	private $_baseUrl;
	private $_name;
 	public function __construct($range,$name,$baseUrl)
 	{
 		$this->_baseUrl = $baseUrl;

 		if(isset($_GET[$name]))
 			$this->_page = $_GET[$name];
 		else
 			$this->_page = 0;

 		$this->_name = $name;
 		$this->_url = "";

 		$bottom = ceil(intval($range)/2)-$range + $this->_page;
 		$top = ceil(intval($range)/2) -1 + $this->_page;
 		if($bottom < 0)
 		{
 			$bottom *= -1;
 			$top = $top + $bottom;
 			$bottom = 0;
 		}
 		$this->_top = $top;
 		$this->_bottom = $bottom;

		foreach ($_GET as $key => $value) { 
			if($key != $this->_name && !empty($value))
			{
				if($this->_url == "")
	 			{
					$this->_url .= "?".$key ."=". $value;
	 			} 
	 			else
	 			{
	 				$this->_url .= "&".$key ."=". $value;
	 			}
			}
		}
 
 	}
 	public function CalculatePageMax($numerOfEntries,$entiresPerPage)
 	{
 		if($numerOfEntries == 0)
 		{
 			$this->_top = 0;
 		}
 		else
 		{
	 		$lmaxPage = ceil($numerOfEntries/$entiresPerPage)-1;
	 		if($this->_top > $lmaxPage)
	 		{
	 			$this->_top = $lmaxPage;
	 		}
 		} 	
 	}

 	public function GetPage()
 	{
 		return $this->_page;
 	}

	public function Output()
	{

		$lfinalUrl = $this->_baseUrl . $this->_url;
		if($lfinalUrl == "")
		{
			$lfinalUrl  .= "?".$this->_name . "=";
		}
		else
		{
			$lfinalUrl  .= "&" . $this->_name . "=";
		}
		?>
		<ul class="pagination pull-right">
			<?php if($this->_page -1 < 0):?>
				<li class="disabled"><a href="">&laquo;</a></li>
			<?php else: ?>
		  		<li><a href="<?php echo $lfinalUrl; echo $this->_page -1 ;?>">&laquo;</a></li>
		  	<?php endif; ?>
			<?php for($x = $this->_bottom;$x <= $this->_top;$x++): ?>
				<?php if($x == $this->_page): ?>
					<li class="active"><a href="<?php echo $lfinalUrl; echo $x;?>"><?php echo $x; ?> <span class="sr-only">(current)</span></a></li>
				<?php else: ?>
					<li><a href="<?php echo $lfinalUrl; echo $x;?>"><?php echo $x; ?></a></li>
				<?php endif; ?>
			<?php endfor; ?>
			

			<?php if($this->_page +1 > $this->_top):?>
				<li class="disabled"><a href="">&raquo;</a></li>
			<?php else: ?>
		  		<li><a href="<?php echo $lfinalUrl; echo $this->_page+1;?>">&raquo;</a></li>
		  	<?php endif; ?>
		</ul>
		<?php
	}
}


?>