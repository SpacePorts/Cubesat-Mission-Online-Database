<?php

require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/HtmlFragments/HtmlTableFragment.php";
require ROOT . "/HtmlFragments/HtmlPaginationFragment.php";
require ROOT . "/Database/UserTable.php";
require ROOT . "/Utility/Url.php";
require ROOT . "/ImageStorage.php";

use Respect\Validation\Validator as v;
use Zend\Db\Sql\Where;

class User extends PageBase{

	private $_user;
	private $_userTable;

	private $_htmlTableFragment;
	private $_searchForm;
	private $_pagination;

	function __construct()
	{
		$this->_user = UserRow::RetrieveFromSession();
		$this->_userTable = new UserTable();

		$this->_htmlTableFragment = new HtmlTableFragment("table table-striped table-hover");
		$this->_searchForm = new HtmlFormFragment(SITE_URL,"GET","","search_form");
		$this->_pagination = new HtmlPaginationFragment(7,"page",SITE_URL);

	}

	function HeaderContent($libraries)
	{

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


	function Ajax($error,&$output)
	{
		switch (Get("type")) {
			case 'RegisterUser':
				# code...
				break;

			default:
				# code...
				break;
		}

	}


	function BodyContent()
	{
		include ROOT . "\Pages\Account\SubMenu.php";


		$this->_searchForm->AddBlock("<label>Search</label>");
		$this->_searchForm->AddHiddenInput("type","search");
		$this->_searchForm->AddTextInput("user_name","Username:",Get("user_name"));
		$this->_searchForm->AddHiddenInput("page-id","Account-User");
		$this->_searchForm->AddSubmitButton("search","pull-right");

		$this->_searchForm->Output();

		$lwhere = new Where();
		$lwhere->Like("user_name","%".Get("user_name")."%");
		$lusers = $this->_userTable->Find($this->_pagination->GetPage(),10,$lwhere);
		$this->_pagination->CalculatePageMax($this->_userTable->FindCount($lwhere),20);

		$this->_htmlTableFragment->AddHeadRow(array("Username","Type"));
		for($x = 0; $x < count($lusers);$x++)
		{
			$lurl = new Url();
			$lurl->AddPair("page-id","Account-User-Modify");
			$lurl->AddPair("user-id", $lusers[$x]->GetId());
			$this->_htmlTableFragment->AddBodyRow(array(
			"<a href='".$lurl->Output()."' >" . $lusers[$x]->GetUsername() . "</a>",
			"<a href='".$lurl->Output()."' >" . $lusers[$x]->GetType() . "</a>"
			));
		}

		$this->_htmlTableFragment->Output();
		$this->_pagination->Output();
	}
}



?>
