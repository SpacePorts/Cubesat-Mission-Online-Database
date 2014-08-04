<?php 

//used for menu generation
function MenuRecursion($value)
{
	?>
	
	<?php for($x = 0; $x < count($value);$x++) : ?>
		<?php 
		$lurl = SITE_URL. '?page-id='. $value[$x]["page-id"];
		$lname = $value[$x]["page_name"]; 
		?>
		
			<?php if(isset($value[$x]["sub_menu"])): ?>
			<li>
				<a id="<?php echo $lname;?>"  data-toggle="dropdown" class="dropdown-toggle" href="#">
					<?php echo $lname; ?> <span class="caret"></span>
				</a> 
				<ul class="dropdown-menu" ><?php MenuRecursion($value[$x]["sub_menu"]); ?></ul>
			</li>
			<?php else: ?>
				<li <?php if($value[$x]["page-id"] == $_GET["page-id"]) echo "class='active'"; ?>>
					<a id="<?php echo $lname;?>" href="<?php echo $lurl; ?>">
						<?php echo $lname; ?>
					</a>
				</li>
			<?php endif; ?>
		
			
		<?php endfor; ?>

	
		<?php
}

//an alternative Get function
function Get($id)
{
	if(isset($_GET[$id]))
	{
		return $_GET[$id];
	}
	return "";
}


function Post($id)
{
	if(isset($_POST[$id]))
	{
		return $_POST[$id];
	}
	return "";
}


?>