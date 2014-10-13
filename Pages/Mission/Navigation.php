<?php
 function Navigation($satID,$pageId)
{
  if(Get("single") != "single")
  {

  require_once ROOT . "/Utility/Url.php";
  $url = new Url();
  $url->AddPair("mission_id",$satID);
  ?>

  </br>
  <ul class="nav nav-tabs" role="tablist">
    <?php $url->AddPair("page-id","Mission-Single"); ?>
    <li class="<?php if($pageId == "Mission-Single")  echo "active"; ?>"><a href="<?php echo $url->Output(); ?>">Single</a></li>
     <?php $url->AddPair("page-id","Mission-Modify"); ?>
    <li class="<?php if($pageId == "Mission-Modify")  echo "active"; ?>"><a href="<?php echo $url->Output(); ?>">Modify</a></li>
     <?php $url->AddPair("page-id","Mission-History"); ?>
    <li class="<?php if($pageId == "Mission-History")  echo "active"; ?>"><a href="<?php echo $url->Output(); ?>">History</a></li>
  </ul>
  <?php
  }
}
?>
