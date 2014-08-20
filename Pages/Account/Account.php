<?php

require ROOT . "/HtmlFragments/HtmlFormFragment.php";
require ROOT . "/Database/UserTable.php";

require ROOT . "/ImageStorage.php";

use Respect\Validation\Validator as v;

class Account extends PageBase{

	private $_changePasswordForm;
	private $_userProfileForm;
	private $_imageStorage;

	private $_user;

	function __construct() {
		$this->_changePasswordForm = new HtmlFormFragment(PAGE_GET_AJAX_URL);
		$this->_userProfileForm = new HtmlFormFragment(PAGE_GET_AJAX_URL);

		$this->_user = UserRow::RetrieveFromSession();
		$this->_imageStorage = new ImageStorage();
	}

	function HeaderContent()
	{

	}

	function Ajax($error,&$output)
	{

		switch (Post("type")) {
			case "change_password":
				if(!$this->_user->VerifyPassword(Post("current_password")))
					$error->AddErrorPair("current_password","Passwords Is Invalid");

				if(Post("re_enter_password") != Post("password"))
					$error->AddErrorPair("password","Passwords Don't Match");
				if(Post("re_enter_password") == "")
					$error->AddErrorPair("re_enter_password","Password Required");
				if(Post("password") == "")
					$error->AddErrorPair("password","Password Required");
				if(!$error->HasError())
				{
					$this->_user->UpdateUserPassword(Post("password"));
				}
			break;
			case "image_upload":
				$this->_imageStorage->UploadImageFixedSize("upload_image",$this->_user->GetAvatarImageToken(),200,200,true);

			break;
			default:

		}

	}

	public function IsUserLegal()
	{
		if(isset($this->_user))
		{
			return true;
		}
		return false;
	}


	function BodyContent()
	{

		include ROOT . "\Pages\Account\SubMenu.php"; 

		$this->_changePasswordForm->AddBlock("<h4>Change Password</h4>");
		$this->_changePasswordForm->AddPasswordInput("current_password","Current Password");
		$this->_changePasswordForm->AddPasswordInput("password","new password");
		$this->_changePasswordForm->AddPasswordInput("re_enter_password","Confirm Password");
		$this->_changePasswordForm->AddSubmitButton("submit","pull-right");
		$this->_changePasswordForm->AddHiddenInput("type","change_password");

		$this->_userProfileForm->AddBlock("<h4>Profile Image</h4>");
		$this->_userProfileForm->AddBlock("<img class='center-block'  src='".$this->_imageStorage->GetImageUrl($this->_user->GetAvatarImageToken()) ."'></img>");
		$this->_userProfileForm->AddUploadButton("upload_image", "Upload Image");
		$this->_userProfileForm->AddSubmitButton("submit","pull-right");
		$this->_userProfileForm->AddHiddenInput("type","image_upload");

		?>
		<div class="row">
			<div class="col-sm-8">
				<?php $this->_changePasswordForm->Output(); ?>
			</div>
			<div class="col-sm-4">
				<?php $this->_userProfileForm->Output(); ?>
			</div>
		</div>

		<?php
	}
}



?>
