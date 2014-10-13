<?php
class Libraries{
  private $_javascript;
  private $_CSS;

  public function __construct() {
    $this->_javascript = array();
    $this->_CSS = array();


  }

  public function AddJavascript($scriptUrl)
  {
    array_push($this->_javascript,array("url"=>$scriptUrl));
  }

  public function AddCSS($cssUrl)
  {
      array_push($this->_CSS,array("url"=>$cssUrl));
  }

  public function Output()
  {

    for($x =0; $x < count($this->_javascript);$x++)
    {
      ?>
        <script type="text/javascript" src="<?php echo $this->_javascript[$x]["url"]; ?>"></script>
      <?php
    }

    for($x =0; $x < count($this->_CSS);$x++)
    {
      ?>
        <link rel="stylesheet" href="<?php echo $this->_CSS[$x]["url"]; ?>">
      <?php
    }

  }
}

?>
