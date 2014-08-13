<?php 

class HtmlFormSwitchBlock 
{
	private $_forms = array();
	private $_blockId;
	public function __construct($block_id)
	{
		$this->_blockId = $block_id;

	}

	public function addForm($block_id,$form,$class)
	{
		array_push($this->_forms, array("form"=>$form,"block_id"=>$this->block_id,"class"=>$class));
	}


	public function Output()
	{
		?>
		<div class="form_switch_block" id="<?php echo $this->_blockId ?>">
			<?php for($x  =0; $x < count($this->_forms);$x++): ?>
				<div class="block" class="<?php echo $this->_forms[$x]["class"]; ?>" data-blockid="<?php echo $this->_forms[$x]["block_id"]; ?>"> 
					<?php  $this->_forms[$x]["form"]->Output(true); ?>
				</div>
			<?php endfor; ?>
		</div>
		<?php
	}
}

?>