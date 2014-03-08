<?php
require_once $modx->getOption('core_path',null,MODX_CORE_PATH).'components/jsonformbuilder/model/jsonformbuilder/JsonFormBuilder.class.php';

//CREATE FORM ELEMENTS
$o_fe_name      = new JsonFormBuilder_elementText('name_full','Your Name');
$o_fe_email     = new JsonFormBuilder_elementText('email_address','Email Address');
$o_fe_notes     = new JsonFormBuilder_elementTextArea('comments','Comments',5,30);

$o_fe_buttSubmit    = new JsonFormBuilder_elementButton('submit','Submit Form','submit');
  
//SET VALIDATION RULES
$a_formRules=array();
//Set required fields
$a_formFields_required = array($o_fe_notes, $o_fe_name, $o_fe_email);
foreach($a_formFields_required as $field){
    $a_formRules[] = new FormRule(FormRuleType::required,$field);
}

        
//Make email field require a valid email address
$a_formRules[] = new FormRule(FormRuleType::email, $o_fe_email, NULL, 'Please provide a valid email address');
  
//CREATE FORM AND SETUP
$o_form = new JsonFormBuilder($modx,'contactForm');
$o_form->setRedirectDocument(3);
$o_form->addRules($a_formRules);

//////////////////
//AUTO RESPONDER//
//////////////////
//Set email addresses and format
$o_form->setAutoResponderToAddress($o_form->postVal('email_address')); //this must be the field ID for your return email, NOT the email address itself
//You can also use an array of email addresses to send to multiple TO addresses.
//$o_form->setAutoResponderToAddress(array('email@address1.com','email@address2.com'));
$o_form->setAutoResponderToAddress(array('webmaster@walkerdesigns.com.au','marcus@datawebnet.com.au'));
$o_form->setAutoResponderFromAddress('from@mybusiness.address');
$o_form->setAutoResponderFromName('Business Title');
$o_form->setAutoResponderReplyTo('reply@mybusiness.address');
//Set the email subject and content
$o_form->setAutoResponderSubject('Business Name - Thanks for contacting us!');
$o_form->setAutoResponderEmailContent('<p>Thank you for contacting us. We will get back to you as soon as possible. Your submitted information is listed below.</p>{{tableContent}}<p>Thanks again!</p>');
//In most cases these probably will not be used, but you can also send the responder to a CC and BCC address.
//$o_form->setAutoResponderCC('cc@address.com.au');
//$o_form->setAutoResponderBCC('bcc@address.com.au');



$o_form->setJqueryValidation(true);
$o_form->setPlaceholderJavascript('JsonFormBuilder_myForm');

  
//ADD ELEMENTS TO THE FORM IN PREFERRED ORDER
$o_form->addElements(
    array(
        $o_fe_name,$o_fe_email,$o_fe_notes,$o_fe_buttSubmit
    )
);

//The form HTML will now be available via
//$o_form->output();
//This can be returned in a snippet or passed to any other script to handle in any way.