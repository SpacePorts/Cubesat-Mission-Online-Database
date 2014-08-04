<?php
class PostContainer
{
	private $_SQL;
	private $_Post;
	function __construct($SQL) {
		$this->_SQL = $SQL;
		$this->_Post = $this->_SQL->getSinglePost($_GET["post-id"]);
	}

	public function PostHeader()
	{
		$this->_Post->PostHeader();
	}

	public function IsRightBarVisible()
	{
		return $this->_Post->IsRightBarVisible();
	}

	private function CommentWalk($Comment_Walker,$PostID)
	{
		for($x = 0; $x < count($Comment_Walker); $x++)
		{

			if(is_array($Comment_Walker[$x]))
			{
				?>
				<div class="Comment_Inset">
					<?php
					$this->CommentWalk($Comment_Walker[$x],$PostID);?>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="Comment_Info">
					<div class="Comment_Avatar" ><img src="http://www.gravatar.com/avatar/"></img></div>
						<div>
							<div class="Comment_Name"><?php echo $Comment_Walker[$x]->getName(); ?></div>
							<div class="Comment_Second_Block" >
							<div> <?php echo $Comment_Walker[$x]->getTimeSincePost(); ?> |</div>
							<div class="reply-container">
								<input type="hidden" class="CommentID" value="<?php echo $Comment_Walker[$x]->getID(); ?>"></input>
								<a href="#" class="reply-anchor" >reply</a></div>
							</div>
						</div>
					</div>
				<div class="Comment" id="Comment<?php echo $Comment_Walker[$x]->getID();  ?>">
					<?php
					echo $Comment_Walker[$x]->getComment();
					?>
				</div>
				<?php
			}
		}
	}

	function Post()
	{

		$Comment_Walker =  $this->_SQL->getCommentsForPost($this->_Post->getID());

		$this->_Post->Post();
		?>

			<h2 id="total-comments">TOTAL COMMENTS(<?php echo $this->_SQL->getTotalComments($this->_Post->getID()); ?>)</h2>

			<div id="comment-container">
			<?php
				$this->CommentWalk($Comment_Walker,$this->_Post->getID());
			?>	
			</div>

			<form class="CommentForm" id="MainReplyForm" >
				<h2 class="comment-leave-reply">Leave Reply:</h2>
				<div class="CommentContainer">
					<textarea rows="10" cols="50"></textarea>
					<input type="submit"></input>
				</div>
			</form>

		</div>
		<?php
	}
}

?>