<?php

require ROOT. "/Base/Comment.php";
class MySQL
{
	private $_db;
	//INTIALZATION----------------------------------------------------------------------------------------------------------------------------
	function __construct() {
		$this->_db= new mysqli(HOST,USER_NAME,USER_PASSWORD,DB_NAME);
	}


	public function getCommentsForApproval()
	{
		$stmt = $this->_db->prepare("SELECT * FROM comments WHERE comment_approved = 0");
		$stmt->execute();
		$Comments = array();


		$stmt->bind_result($ID,$UserID,$PostID,$CommentParentID,$Comment,$Date,$CommentApproved);
		while($stmt->fetch())
		{

			array_push($Comments, new Comment($this->_db,$ID,$UserID,$PostID,$CommentParentID,$Comment,$Date,$CommentApproved));
	
		}
		$stmt->close();
		return $Comments;
	}

	public function ApproveComment($ID)
	{
		$stmt = $this->_db->prepare("UPDATE comments SET comment_approved=1 WHERE comment_approved=0 AND ID=?");
		$stmt->bind_param("i",$ID);
		$stmt->execute();
	}

	public function DeleteComment($ID)
	{
		$stmt = $this->_db->prepare("DELETE FROM comments WHERE comment_approved=0 AND ID=?");
		$stmt->bind_param("i",$ID);
		$stmt->execute();
	}


}

?>