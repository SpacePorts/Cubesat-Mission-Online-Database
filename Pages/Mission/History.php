<?php

require ROOT . "/Database/SatelliteTable.php";
require_once ROOT . "/Database/PartTable.php";

require ROOT . "/HtmlFragments/HtmlIframePanelFormListFormFragment.php";
require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlDropdownFragment.php";
require ROOT . "/HtmlFragments/HtmlComparisonFragment.php";

require_once ROOT . "/Database/UserTable.php";

require ROOT . "/Database/MissionTable.php";

require ROOT . "/Utility/Url.php";
require "Navigation.php";

use Respect\Validation\Validator as v;
use Zend\Db\Sql\Where;

class History extends PageBase {
  private $_missionTable;
  function __construct() {
  }
  
  function HeaderContent($libraries)
  {
  }

  public function IsUserLegal()
  {
    return true;

  }

  function Ajax($error,&$output)
  {
    Navigation(Get("sat_id"),Get("page-id"));

  }

  function BodyContent()
  {
  }


}

?>
