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
//make email field require a valid email address
$a_formRules[] = new FormRule(FormRuleType::email, $o_fe_email, NULL, 'Please provide a valid email address');
  
//CREATE FORM AND SETUP
$o_form = new JsonFormBuilder($modx,'contactForm');
$o_form->setHooks(array('spam','email','redirect'));
$o_form->setRedirectDocument(5);
$o_form->addRules($a_formRules);
$o_form->setPostHookName($snippetName);
$o_form->setEmailToAddress('your@email.address');
$o_form->setEmailFromAddress('[[+email_address]]');
$o_form->setEmailSubject('JsonFormBuilder Contact Form Submission - From: [[+name_full]]');
$o_form->setEmailHeadHtml('<p>This is a response sent by [[+name_full]] using the contact us form:</p>');
$o_form->setJqueryValidation(true);
  
//ADD ELEMENTS TO THE FORM IN PREFERRED ORDER
$o_form->addElements(
    array(
        $o_fe_name,$o_fe_email,$o_fe_notes,
        new JsonFormBuilder_htmlBlock('<hr class="formSpltter" />'),
        $o_fe_buttSubmit
    )
);
?>