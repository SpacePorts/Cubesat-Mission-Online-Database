<?php
require ROOT . "/Database/SpaceportTable.php";

class Single extends PageBase
{
	private $_spaceportTable;
	private $_spaceport;
	function __construct() {
		$this->_spaceportTable = new SpaceportTable();
		$this->_spaceport = $this->_spaceportTable->GetRowById(Get("spaceport_id"));

	}
	public function HeaderContent($libraries)
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
		<?php  if(Get("single") != "single") : ?>
		<a href="<?php echo SITE_URL; ?>?page-id=Spaceport-Modify&spaceport_id=<?php echo $this->_spaceport->GetId();?>">Modify</a>
		<?php endif; ?>

		<h1><?php echo $this->_spaceport->GetName(); ?></h1>
		<h2>Lat Long</h2>
		<?php echo $this->_spaceport->GetLatLong(); ?>
		<h2>Description:</h2>
		<?php echo $this->_spaceport->GetDescription(); ?>
		<h2>Address:</h2>
		<address>
			<?php echo $this->_spaceport->GetAddressOne(); ?></br>
			<?php if($this->_spaceport->GetAddressTwo() != ""): ?>
			<?php echo $this->_spaceport->GetAddressTwo(); ?></br>
			<?php endif; ?>
			<?php echo $this->_spaceport->GetCity(); ?>,<?php echo $this->_spaceport->GetZip(); ?></br>
			<?php echo $this->_spaceport->GetCountry(); ?>
		</address>
		<?php
	}
}
?>
