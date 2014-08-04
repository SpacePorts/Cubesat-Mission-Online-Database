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
		<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PAGE_URL; ?>Comments.css"></link>
		<?php
	}

	function BodyContent()
	{
		if(!empty($_POST["submit"]))
		{
			if($_POST["submit"] == "Delete")
			{
				$this->_SQL->DeleteComment($_POST["comment-id"] );
			}
			else if($_POST["submit"] == "Approve")
			{
				$this->_SQL->ApproveComment($_POST["comment-id"] );
			}
		}

		$comments = $this->_SQL->getCommentsForApproval();
		for($x = 0; $x < count($comments); $x++)
		{
			 ?>
			<form action="<?php echo ADMIN_PAGE_GET_URL ?>" method="post" class="Comment_Wrapper">
				<div>
					<div>
						<?php echo $comments[$x]->getName(); ?> said:
					</div>
					<?php echo $comments[$x]->getComment(); ?>
				</div>
				<input type="hidden" name="comment-id" value="<?php echo $comments[$x]->getID(); ?>">
				<input type="submit" name="submit" value="Approve">|<input name="submit" type="submit" value="Delete">
			</form>
			<?php
		}

	}
}
?>
