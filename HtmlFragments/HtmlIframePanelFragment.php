<?php
class HtmlIframePanelFragment
{
	private $_iframeUrl = array();

	public function __construct()
	{

	}

	public function AddIframe($action,$name){
			array_push($this->_iframeUrl, array("iframe" => $action,"name"=>$name));
	}
	public function Output()
	{
		?>
		<div class="iframe-single iframe-panel">
			<div class="iframe-container">
			<?php for($x = 0; $x < count($this->_iframeUrl);$x++): ?>
				<div class="panel panel-default retractable-panel">
					 <div class="panel-heading"><a href="#" class="expand-button"><span class="glyphicon-plus"></span></a> <?php echo $this->_iframeUrl[$x]["name"]; ?></div>
					<div class="panel-body" style="display:none;">
						
						<iframe seamless  src="<?php echo $this->_iframeUrl[$x]["iframe"]; ?>"></iframe>
						
					</div>
				</div>
				<?php endfor;?>
			</div>
		</div>
		<?php
	}

}


?>