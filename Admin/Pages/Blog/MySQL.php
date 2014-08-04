<?php
require ROOT. "/Base/Post.php";
require ROOT. "/Base/Category.php";

class MySQL
{
	private $_db;
	//INTIALZATION----------------------------------------------------------------------------------------------------------------------------
	function __construct() {
		$this->_db= new mysqli(HOST,USER_NAME,USER_PASSWORD,DB_NAME);

	}

	function getAllPost()
	{
		//creates an array
		$Posts = array();
		$stmt = $this->_db->prepare("SELECT * FROM post");
		$stmt->execute();

		$stmt->bind_result($ID,$Content,$Title,$Date);
		while($stmt->fetch()){
			array_push($Posts, new Post($ID,$Content,$Title,$Date,0));
		}

		return $Posts;

	}

	function getSinglePost($ID)
	{
		$stmt = $this->_db->prepare("SELECT * FROM post WHERE id=?");
		$stmt->bind_param("i",$ID);
		$stmt->execute();

		$stmt->bind_result($ID,$Content,$Title,$Date);
		while($stmt->fetch()){
			return  new Post($ID,$Content,$Title,$Date,0);
	
		}

	}

	function UpdatePost($ID,$Message,$Title)
	{
		$stmt = $this->_db->prepare("UPDATE post SET Message=?,Title=? WHERE ID=?");
		$stmt->bind_param("ssi",$Message,$Title,$ID);
		$stmt->execute();
		$stmt->close();
	}

	function NewPost($Message,$Title)
	{
		$stmt = $this->_db->prepare("INSERT INTO post (Message,Title,Date) VALUES (?,?,UTC_TIMESTAMP())");
		$stmt->bind_param("ss",$Message,$Title);
		$stmt->execute();

		return $stmt->insert_id;
	}

	function getAllCategories()
	{
		$categories = array();

		$stmt = $this->_db->prepare("SELECT * FROM categories");
		$stmt->execute();

		$stmt->bind_result($ID,$Tag);
		while($stmt->fetch()){
			array_push($categories, new Category($ID,$Tag));
		}
		$stmt->close();
	
		return $categories;
	}

	function IsPostCategoryRelation($postID,$CategoryID)
	{
		$stmt = $this->_db->prepare("SELECT count(*) AS total FROM category_post_relation  WHERE Post_ID=? AND Category_ID=?");
		$stmt->bind_param("ii",$postID,$CategoryID);
		$stmt->execute();

		$stmt->bind_result($Total);
		while($stmt->fetch()){
			if($Total == 1)
			{

				return true;
			}
		
			return false;
		}

	}

	function UpdateCategoriesForPost($PostID,$CategoryIDs)
	{
		$stmt = $this->_db->prepare("DELETE FROM category_post_relation WHERE Post_ID=?");
		$stmt->bind_param("i",$PostID);
		$stmt->execute();
		$stmt->close();


		$stmt = $this->_db->prepare("INSERT INTO category_post_relation (Post_ID,Category_ID) VALUES (?,?)");
		for($x = 0; $x < count($CategoryIDs); $x++)
		{
			$stmt->bind_param("ii",$PostID,$CategoryIDs[$x]);
			$stmt->execute();
		}
		$stmt->close();
	}



	function AddCategories($Tag)
	{
		$stmt = $this->_db->prepare("INSERT INTO categories (Tag) VALUES (?)");
		$stmt->bind_param("s",$Tag);
		$stmt->execute();
	}

	function GetMetaData($PostID)
	{
		$stmt = $this->_db->prepare("SELECT Meta_Key, Meta_Value FROM post_meta WHERE Post_ID = ?");
		$stmt->bind_param("i", $PostID);
		$stmt->execute();

		$lMetaDataPair = array();

		$stmt->bind_result($lMetaKey,$lMetaValue);
		while($stmt->fetch())
		{
			$pair = array();
			$pair["key"] = $lMetaKey;

			if($lMetaValue == serialize(false) || @unserialize($lMetaValue) !== false)
			{
				$pair["value"]  =unserialize($lMetaValue);
			}
			else
			{
				$pair["value"]  =$lMetaValue;
			}
			array_push(	$lMetaDataPair, $pair);
		}
		return $lMetaDataPair;
	}

	function UpdateMetaData($Meta,$PostID)
	{
		$stmt = $this->_db->prepare("DELETE FROM post_meta WHERE Post_ID = ?");
		$stmt->bind_param("i",$PostID);
		$stmt->execute();
		$stmt->close();

		$stmt = $this->_db->prepare("INSERT INTO post_meta(Post_ID,Meta_Key,Meta_Value) VALUES (?,?,?)");

		for($x =0; $x < count($Meta); $x++)
		{
			$lKey  = $Meta[$x][0];
			$lValue = $Meta[$x][1];
			$stmt->bind_param("iss",$PostID,$lKey,$lValue);
			$stmt->execute();
		}
		$stmt->close();
		
	}
}

?>