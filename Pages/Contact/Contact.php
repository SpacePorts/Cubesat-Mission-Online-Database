<?php
require ROOT . "/HtmlFragments/HtmlFormFragment.php";


use Respect\Validation\Validator as v;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;

class Contact extends PageBase {
	private $_form;
	private $_message;
	function HeaderContent($libraries)
	{
		$this->_form = new HtmlFormFragment(PAGE_GET_AJAX_URL);
	}


	function Ajax($error,&$output)
	{
		if(!v::string()->notEmpty()->validate(Post("name")))
			$error->AddErrorPair("name","Require Name");

		if(!v::string()->notEmpty()->validate(Post("email")))
			$error->AddErrorPair("email","Require Email");
		else if(!v::email()->validate(Post("email")))
			$error->AddErrorPair("email","Email is invalid");

		if(!v::string()->notEmpty()->validate(Post("subject")))
			$error->AddErrorPair("subject","Require Subject");

		if(!v::string()->notEmpty()->validate(Post("message")))
			$error->AddErrorPair("message","Require Message");

		if(!$error->HasError())
		{
			$this->_message = new Message();
			$this->_message->addFrom( Post("email"), Post("name"));
			$this->_message->addTo(CONTACT_MAIL);
			$this->_message->setSubject(Post("subject"));
			$this->_message->setBody(Post("message"));

			$ltransport = new SendmailTransport();
			$ltransport->send($this->_message);
		}
	}

	function BodyContent()
	{
		echo "<h1>Contact Us:</h1>";
		$this->_form->AddTextInput("name","Name:*");
		$this->_form->AddTextInput("email","Email:*");
		$this->_form->AddTextInput("subject","Subject:");
		$this->_form->AddInput("captcha","Capcha:*",recaptcha_get_html(CAPTCHA_PUBLIC_KEY));
		$this->_form->AddTextArea("message","Message:");
		$this->_form->AddSubmitButton("Send");
		$this->_form->output();
	}
}

?>
