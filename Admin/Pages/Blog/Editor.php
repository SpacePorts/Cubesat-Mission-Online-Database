<?php 
require_once ROOT."/Admin/Check.php";

if(!empty($_POST["submit"]))
{
	if($_POST["submit"] == "submit")
	{
		//process the meta data
		$lMetaData = array();
		for($x = 0; $x < count($_POST["MetaKey"]); $x++)
		{
			$lMeta = array();
			if(!empty($_POST[$_POST["MetaKey"][$x]]))
			{
				array_push($lMeta, $_POST["MetaKey"][$x]);
				if(is_array( $_POST[$_POST["MetaKey"][$x]]))
				{
					$lData = array();
					for($y = 0; $y < count($_POST[$_POST["MetaKey"][$x]]); $y++)
					{
						if($_POST[$_POST["MetaKey"][$x]][$y] != "")
						{
							array_push($lData, $_POST[$_POST["MetaKey"][$x]][$y]);
						} 
					}
					array_push($lMeta, serialize($lData));
				}
				else
				{
					array_push($lMeta, $_POST[$_POST["MetaKey"][$x]]);
				}
				array_push($lMetaData, $lMeta);
			}
		}

		$this->_SQL->UpdateMetaData($lMetaData,$_GET["Post-id"]);


		if($_GET["Post-id"] == -1)
		{
			$_GET["Post-id"]  = $this->_SQL->NewPost($_POST["content"],$_POST["title"]);
		}
		else
		{
			$this->_SQL->UpdatePost($_GET["Post-id"],$_POST["content"],$_POST["title"]);
		}

		if(!empty($_POST["tags"]))
		{
			$this->_SQL->UpdateCategoriesForPost($_GET["Post-id"],$_POST["tags"]);
		}
	}
	else if($_POST["submit"] == "add")
	{
		$this->_SQL->AddCategories($_POST["Category"]);
	}
}

if($_GET["Post-id"] != -1)
{
	$post = $this->_SQL->getSinglePost($_GET["Post-id"]);
	$_POST["title"] = $post->getTitle();
	$_POST["content"] = $post->getContent();
}
else
{
	if(empty($_POST["title"]))$_POST["title"] = "";
	if(empty($_POST["content"]))$_POST["content"] = "";
}

?>
	

	<form id="EditorForm" action="<?php echo ADMIN_PAGE_GET_URL . "&Post-id=" .$_GET["Post-id"]; ?>"  method="post">
		<div>
			<input type="text" name="title" value="<?php echo $_POST["title"]; ?>">
		</div>

		<div>
			<textarea wrap="soft" name="content" rows="30" cols="50"><?php echo $_POST["content"]; ?></textarea>
		</div>
		<select id="meta-option-select" name="meta-option-select">
			<option value="Default">Default</option>
			<option value="Theme">Theme</option>
		</select>
		<div id="meta-option-container"></div>

		</br>
		<div id="category-wrapper">
			<?php
			$categories = $this->_SQL->getAllCategories();
			for($x = 0;$x < count($categories); $x++)
			{
				?>
				<div><input  type="checkbox" <?php if($this->_SQL->IsPostCategoryRelation($_GET["Post-id"],$categories[$x]->getID()) == true){echo "checked";} ?> name="tags[]" value="<?php echo  $categories[$x]->getID(); ?>"><?php echo $categories[$x]->getTag(); ?></div>
				<?php
			}

			?>
			<input name="submit" value="add" type="submit"><input name="Category" class="category" type="text">
		</div>
		<div>
			<input name="submit" value="submit" type="submit">
		</div>
	</form>

	<div id="category-data"><?php echo json_encode($this->_SQL->GetMetaData($_GET["Post-id"])) ?></div>