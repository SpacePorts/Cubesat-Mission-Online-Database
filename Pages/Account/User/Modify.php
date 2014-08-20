<?php

require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/Database/UserTable.php";
require ROOT . "/HtmlFragments/HtmlDropdownFragment.php";

use Respect\Validation\Validator as v;

class Modify extends PageBase{
	private $_user;
	private $_modifyUser;
	private $_userTable;

	private $_userForm;
	private $_typeSelect;

	function __construct() {
		
		$this->_userTable = new UserTable();
		$this->_user = UserRow::RetrieveFromSession();
		if(Get("user-id") != "")
		{
			$this->_modifyUser = $this->_userTable->GetRowById(Get("user-id"));
			$this->_typeSelect = new HtmlDropdownFragment("user_type",$this->_modifyUser->GetType());
		}
		if(Post("user-id") != "")
		{
			$this->_modifyUser = $this->_userTable->GetRowById(Post("user-id"));
			$this->_typeSelect = new HtmlDropdownFragment("user_type",$this->_modifyUser->GetType());
		}

		$this->_typeSelect->AddOption(UserRow::ADMIN,UserRow::ADMIN);
		$this->_typeSelect->AddOption(UserRow::PRODUCER,UserRow::PRODUCER);
		$this->_typeSelect->AddOption(UserRow::END_USER,"End User");

		$this->_userForm = new HtmlFormFragment(PAGE_GET_AJAX_URL);
	}

	function HeaderContent()
	{

	}

	function Ajax($error,&$output)
	{

		if(!$this->_typeSelect->IsOptionValid(Post("user_type")))
		{
			$error->AddErrorPair("user_type","Invalid User Type");	
		}

		if(!$error->HasError())
		{
			$this->_modifyUser->ChangeType(Post("user_type"));
		}
	}

	function IsUserLegal()
	{
		if(isset($this->_user))
		{
			if($this->_user->GetType() ==  UserRow::ADMIN)
			{
				return true;
			}
		}
		return false;
	}




	function BodyContent()
	{

		$this->_userForm->AddBlock("<div  class='form-group'><h4>Username:</h4>" . $this->_modifyUser->GetUsername() . "</div>" );
		$this->_userForm->AddBlock("<div  class='form-group'><h4>Email:</h4>" . $this->_modifyUser->GetEmail(). "</div>" );
		$this->_userForm->AddFragment("user_type","Type",$this->_typeSelect);
		$this->_userForm->AddHiddenInput("user-id",$this->_modifyUser->GetId());
		$this->_userForm->AddSubmitButton("Modify","pull-right");
		$this->_userForm->Output();
	}
}



?>
