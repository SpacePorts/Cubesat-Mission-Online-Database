<?php

 class HtmlItemSelectionFragment 
 {
 	private $_value = array();
 	private $_id = array();
 	private $_url;
 	private $_identifier;

 	public function __construct($url, $identifier)
 	{
 		$this->_url = $url;
 		$this->_identifier = $identifier;
 	}


 	public function Header()
 	{
 		//javascript for itemselect
 		?>
 				<script type="text/javascript" src="<?php echo SITE_URL; ?>/Public/ItemSelect.js"></script>
 	
		<?php

 	}

 	public  function AddPair($id,$value)
 	{
 		array_push($this->_id, $id);
 		array_push($this->_value, $value);
 	}

 	public function Output()
 	{

 		?>

		<div class="item_selection">
		
			<div class="unselected">
				
				<div>
				Un-Selected
					<input class="item_selection_search form-control"  placeholder="Search"  identifier="<?php echo  $this->_identifier; ?>"  ajax-search="<?php echo $this->_url; ?>" type="text"/>
					<ul>

					</ul>
				</div>
			</div><!-- white space hack!
		 --><div class="selected">
			 <div>
			 Selected
				<ul item-name="sat_item">

					<?php for($x = 0; $x < count($this->_value); $x++):?>
					<li><a href="#" item-id="<?php echo $this->_id[$x]; ?>"><?php echo $this->_value[$x]; ?></a><input type="hidden" name="<?php echo  $this->_identifier ; ?>[]" value="<?php echo $this->_id[$x]; ?>"/></li>
					<?php endfor; ?>
				</ul>
				</div>
			</div>
		</div>

		<?php

 	}
 }

//Ajax response used in combination with HTML ItemSelection
class HtmlItemSelectionAjax
{
 	private $_value = array();
 	private $_id = array();

	public function __construct()
	{

	}

 	public  function AddPair($id,$value)
 	{
 		array_push($this->_id, $id);
 		array_push($this->_value, $value);
 	}

	public function Execute($error,&$output)
	{
		$lpartOutput = array();
		for($x =0; $x < count($this->_id);$x++)
		{
			array_push($lpartOutput, array("id"=>$this->_id[$x],"value"=>$this->_value[$x]));
		}
		$output["parts"] = $lpartOutput;
	}

}

?>