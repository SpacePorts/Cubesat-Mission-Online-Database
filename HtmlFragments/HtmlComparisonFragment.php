<?php

class HtmlComparisonFragment
{
	private $_comparisonPair;
	function __construct()
	{
		$this->_comparisonPair = array();
	}

	public function Header($libraries)
	{
		$libraries->AddJavascript(SITE_URL . "/Public/Iframe.js");
	}

	function AddComparisonPair($name,$content1,$content2)
	{
		array_push($this->_comparisonPair, array("name"=>$name,"content1" => $content1,"content2" => $content2));
	}

	function Output()
	{
		for($x =0; $x < count($this->_comparisonPair);$x++ )
		{
			?>
			<div class="panel-heading"><?php echo $this->_comparisonPair[$x]["name"] ?></div>
			<div class="row">
				<div class="col-xs-6">
					<div class="well well-sm">
						<?php echo $this->_comparisonPair[$x]["content1"] ?>
					</div>
				</div>
				<div class="col-xs-6">
					<div class="well well-sm">
						<?php echo $this->_comparisonPair[$x]["content2"] ?>
					</div>
				</div>
			</div>

			<?php
		}
	}
}

?>
