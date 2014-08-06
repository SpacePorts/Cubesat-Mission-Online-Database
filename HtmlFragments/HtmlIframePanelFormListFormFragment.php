<?php
class HtmlIframePanelFormListFormFragment
{
	private $_iframeUrl = array();
	private $_extract;
	private $_searchName;
	private $_searchAction;
	private $_titleExtract;
	private $_addAction;
	private $_searchPair = array();
	public function __construct($extract,$seachName,$titleExtract,$searchAction,$addAction)
	{
		$this->_titleExtract = $titleExtract;
		$this->_searchName = $seachName;
		$this->_extract = $extract;
		$this->_searchAction = $searchAction;
		$this->_addAction = $addAction;
	}

	public function AddIframe($action){
		array_push($this->_iframeUrl, $action);
	}

	public function addSearchPair($name,$action)
	{
		array_push($this->_searchPair,array("action"=> $action,"name"=>$name));	
	}

	public function Ajax(&$output)
	{
		$output["search"] = $this->_searchPair;
	}

	public function Output()
	{
		if(empty($this->_searchPair))
			$this->AddIframe($this->_addAction);


		?>
		<div class="iframe-list iframe-panel">
			<div class="dropdown">
				<input type="text"  class='form-control' placeholder="Search" action="<?php echo $this->_searchAction; ?>" search-id="<?php echo  $this->_searchName; ?>"/>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel"></ul>
			</div>

			<div class="iframe-container">
				<?php for($x = 0; $x < count($this->_iframeUrl);$x++): ?>
				<div class="panel panel-default retractable-panel">
					 <div class="panel-heading"><a href="#" class="expand-button"><span class="glyphicon-plus"></span></a><span class="title"></span></div>
					<div class="panel-body" style="display:none;">
						<iframe seamless title-extract="<?php echo $this->_titleExtract ?>" extract="<?php echo $this->_extract; ?>" src="<?php echo $this->_iframeUrl[$x]; ?>"></iframe>
						
					</div>
				</div>
				<?php endfor;?>
			</div>
			<button type="button" action="<?php echo $this->_addAction; ?>" class="btn btn-primary btn-lg btn-block add-another">Add</button>
		</div>
		<?php
	}

}


?>