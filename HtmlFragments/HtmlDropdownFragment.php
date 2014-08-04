<?php 
class HtmlDropdownFragment
{
	private $_options = array();
	private $_name;
	private $_selected;
	function __construct($name,$selected = "")
	{
		$this->_name = $name;
		$this->_selected = $selected;

	}

	public function AddOption($value,$output)
	{
		array_push($this->_options, array("value" =>$value,"output"=>$output));
	}

	public function IsOptionValid($value)
	{
		for($x = 0; $x < count($this->_options);$x++)
		{
			if($value == "NULL" )
				return false;
			if($this->_options[$x]["value"] == $value )
				return true;
		}
		return false;

	}

	public function Output()
	{
		?>
		</br>
		<select name="<?php echo $this->_name; ?>">    
			<?php for($x = 0; $x < count($this->_options);$x++): ?>

			<option value="<?php echo $this->_options[$x]["value"]; ?>" <?php if($this->_options[$x]["value"] == $this->_selected) echo "selected"; ?> ><?php echo $this->_options[$x]["output"]; ?></option>
			<?php endfor; ?>   
		</select>


		<?php
	}
}

?>