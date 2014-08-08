<?php
class Pages extends PageBase {
function HeaderContent()
{
?>
<link rel="stylesheet" type="text/css" href="<?php echo PAGE_URL; ?>Contact.css">
<script type="text/javascript" src="<?php echo PAGE_URL; ?>Contact.js"></script>
<script type="text/javascript">AJAX_MAIL = "<?php echo PAGE_URL; ?>AjaxContact.php";</script>
<?php
}


function BodyContent()
{
?>
		<h1>Contact Us:</h1>
	<div id="contactFormContainer">
			<form id="contactForm" onSubmit="sendEmailForm();return false;">
				<div>
					<div class="contactFormElementPadding">
						<div>Name*<span class="warning" id="contactFormWarning1"></span></div>
						<input id="contactFormNameField" type="text"></input>
					</div>
					<div class="contactFormElementPadding">
						<div>Email*<span class="warning" id="contactFormWarning2"></span></div>
						<input id="contactFormEmailField" type="text"></input>
					</div>
					<div class="contactFormElementPadding">
						<div>Subject<span class="warning" id="contactFormWarning3"></span></div>
						<input id="contactFormSubjectField" type="text"></input>
					</div>
					<div class="contactFormElementPadding" id="capche">
						<div>Human Verification<span  class="warning" id="contactFormWarning4"></span></div>
						<div id="ContactFormCapcha"></div>
					</div>
					<div class="contactFormElementPadding">
						<div>Message*<span class="warning" id="contactFormWarning5"></span></div>
						<textarea id="contactFormMessageField"  rows="5" cols="50"></textarea>
					</div>
					<div id="submitButton" class="contactFormElementPadding">
						<input type="submit" value="Submit"/>
					</div>
				</div>
			</form>
			<div id="formSent">Message Sent</div>
		</div>

<?php
}
}

?>
