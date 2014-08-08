<?php
require ROOT . "/Database/SpaceportTable.php";

class Pages extends PageBase
{
	private $_spaceportTable;
	private $_spaceport;
	function __construct() {
		$this->_spaceportTable = new SpaceportTable();
		$this->_spaceport = $this->_spaceportTable->GetRowById(Get("spaceport_id"));
	
	}
	public function HeaderContent()
	{

	}

	public function GetPageID()
	{
		return "Mission-Single";
	}

	function Ajax($error,&$output)
	{
	
	}


	public function BodyContent()
	{
		?>
		<h1><?php echo $this->_spaceport->GetName(); ?></h1>
		<h2>Lat Long</h2>
		<?php echo $this->_spaceport->GetLatLong(); ?>
		<h2>Description:</h2>
		<?php echo $this->_spaceport->GetDescription(); ?>
		<?php
	}
}
?>
