<?php

require_once "Fragment.php";

class HtmlSearchFragment extends Fragment
{
	private $_searchOption = array();
	private $_dropdownName;
	private $_searchName;

	private $_searchValue;
	private $_selectValue;

	function __construct($dropdownName,$searchName,$searchValue="",$selectValue="") {
		$this->_dropdownName = $dropdownName;
		$this->_searchName = $searchName;

		$this->_searchValue = $searchValue;
		$this->_selectValue = $selectValue;
	}


	public function AddSearchOption($option,$value){
		array_push($this->_searchOption, array("option"=>$option,"value" => $value));
	}

	public function IsOptionValid($value)
	{
		for($x = 0; $x < count($this->_searchOption);$x++)
		{
			if($value == "NULL" )
				return false;
			if($this->_searchOption[$x]["value"] == $value )
				return true;
		}
		return false;

	}
	
	public function Output()
	{
		?>

		<div class="search-input">
			<div class="row">
				<div class="col-md-2 left">
					<select  name="<?php echo $this->_dropdownName; ?>">
					<?php for($x =0; $x < count($this->_searchOption);$x++): ?>
						<option value="<?php echo $this->_searchOption[$x]["option"] ?>" <?php if($this->_searchOption[$x]["option"] == $this->_selectValue) echo "selected"; ?>> <?php echo $this->_searchOption[$x]["value"] ?></option>
					<?php endfor; ?>
					</select>
				</div>
				<div class="col-md-10 right">
					<input type="text" name="<?php echo $this->_searchName; ?>" placeholder="Search" value="<?php echo $this->_searchValue; ?>" />
				</div>
			</div>
		</div>

		<?Php

	}
} 


?>