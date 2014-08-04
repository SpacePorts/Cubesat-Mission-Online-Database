<?php

class Pages {

	function __construct() {
	}

	function HeaderContent()
	{
		?>
		<script type="text/javascript" src="<?php echo SITE_URL ?>/Admin/Pages/Media/Media.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL ?>/Admin/Pages/Media/Media.css">
		<?php
	}

	function BodyContent()
	{ 

			if(!empty($_POST["Delete"]))
			{
				unlink(ROOT . "/Uploaded/" . $_POST["file"]);
			}
			else if(!empty($_POST["SubmitFile"]))
			{
				
				if(!empty($_FILES["file"]))
				{
					$lTargetPath = ROOT . "/Uploaded/" . basename($_FILES['file']['name']);
					if(move_uploaded_file($_FILES['file']['tmp_name'], $lTargetPath))
					{
						echo "the file " . basename($_FILES['file']['name']) . " has been loaded";
					}
					else
					{
						echo "ERROR";
					}
				}
			}
		
		?>
		<form  enctype="multipart/form-data" method="post">
			<div> <input name="file" type="file"></input></div>
			<div><input type="Submit" name="SubmitFile" value="Submit File"></input></div>
		</form>
		<?php

		$listedPages = scandir(ROOT. "/Uploaded/", 1);
		for($x = 0; $x < count($listedPages)-2; $x++)
		{

			?>
			<form method="post">
				<div class="file"><?php echo $listedPages[$x]; ?> | <?php echo SITE_URL."/Uploaded/".$listedPages[$x]; ?></div>
				<input type="hidden" name="file" value="<?php echo $listedPages[$x]; ?>"></input>
				<input type="submit" name="Delete" value="Delete"></input>
			</form>

			<?php
			
		}
	}
}
?>
