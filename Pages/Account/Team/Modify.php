<?php
require ROOT. "/HtmlFragments/HtmlFormFragment.php";

require ROOT. "/Database/UserRow.php";

require_once ROOT. "/Database/TeamTable.php";
require ROOT . "/Utility/Url.php";

use Respect\Validation\Validator as v;

class Modify extends PageBase {
	private $_user;
	private $_form;
	private $_teamTable;
	private $_team;
	function __construct() {
		$this->_user = UserRow::RetrieveFromSession();
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);
		$this->_teamTable = new TeamTable();
		if(Get("team-id") != "")
			$this->_team = $this->_teamTable->GetRowById(Get("team-id"));

	}


	public function IsUserLegal()
	{
		if(isset($this->_user))
		{
			if($this->_user->GetType() == UserRow::PRODUCER || $this->_user->GetType() == UserRow::ADMIN)
			return true;
		}
		return false;
	}

	function HeaderContent($libraries)
	{

	}


	function Ajax($error,&$output)
	{

		if(!v::string()->notEmpty()->validate(Post("team_name")))
			$error->AddErrorPair("team_name","Require Team Name");

		if(!$error->HasError())
		{
			$lurl = new Url();
			$this->_team = $this->_teamTable->AddTeam(Post("team_name"),Post("team_latlong"));
			$this->_team->AddUser($this->_user);

			$lurl->AddPair("page-id","Account-Team-Modify");
			$lurl->AddPair("team-id",$this->_team->GetId());
			return $lurl->Output();
		}

	}

	function BodyContent()
	{
		if(isset($this->_team))
		{
			$this->_form->AddTextInput("team_name","Name:*",$this->_team->GetName());
			$this->_form->AddTextInput("team_latlong","Lat Long:",$this->_team->GetLatLong());
			$this->_form->AddSubmitButton("Register");
			$this->_form->Output();
		}
		else
		{
			$this->_form->AddTextInput("team_name","Name:*");
			$this->_form->AddTextInput("team_latlong","Lat Long:");
			$this->_form->AddSubmitButton("Register");
			$this->_form->Output();
		}

	}
}



?>
