<?php
require "MySQL.php";
class Pages {
	private $_SQL;
	function __construct() {
		$this->_SQL = new MySQL();
	}

	function HeaderContent()
	{
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PAGE_URL; ?>Blog.css">
		<script type="text/javascript" src="<?php echo ADMIN_PAGE_URL; ?>Editor.js"></script>
		<?php
	}

	function BodyContent()
	{ ?>
	<div>
		<?php
		if(empty($_GET["Post-id"]))
		{
			$lpost = $this->_SQL->getAllPost();
			
			for($x = 0; $x < count($lpost); $x++)
			{
				?>

				 <div><a href="<?php echo ADMIN_PAGE_GET_URL . "&Post-id=" . $lpost[$x]->getID(); ?>"><?php echo $lpost[$x]->getTitle(); ?></a></div>

				<?php
			}
			?>
			<a href="<?php echo ADMIN_PAGE_GET_URL . "&Post-id=-1"  ?>">Start New Post</a>
			<?php
		}
		else
		{
			require "Editor.php";
		}
		?>

	</div>
	<?php
	}
}
?>
