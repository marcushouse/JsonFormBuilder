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
$o_form->setSpamProtection(true);
$o_form->addRules($a_formRules);

//SETUP EMAIL
//Note, this is not required, you may want to not send an email and record the data to a database.
$o_form->setEmailToAddress($modx->getOption('emailsender'));
$o_form->setEmailFromAddress($o_form->postVal('email_address'));
$o_form->setEmailFromName($o_form->postVal('name_full'));
$o_form->setEmailSubject('JsonFormBuilder Contact Form Submission - From: '.$o_form->postVal('name_full'));
$o_form->setEmailHeadHtml('<p>This is a response sent by '.$o_form->postVal('name_full').' using the contact us form:</p>');

//Set jQuery validation on and to be output
$o_form->setJqueryValidation(true);
//You can specify that the javascript is sent into a placeholder for those that have jquery scripts just before body close. If jquery scripts are in the head, no need for this.
$o_form->setPlaceholderJavascript('JsonFormBuilder_myForm');
  
//After creating all your form elements add them
$o_form->addElements(
    array(
        $o_fe_name,$o_fe_email,$o_fe_notes,$o_fe_buttSubmit
    )
);

//Load our custom package. This could be done well befoer even running your form snippet.
//As long as the package is loaded prior to using your custom database you will be fine.
$packageName = 'testpackage';
$path = MODX_CORE_PATH . 'components/'.$packageName.'/';
$packageLoadResult = $modx->addPackage($packageName,$path.'model/',$packageName.'_');
if($packageLoadResult===false){
    //log or throw an error in some way.
   echo 'Failed to load package "'.$packageName.'"';
   exit();
}

//must force form to run validate check prior to checking if submitted
$o_form->validate();
    
if($o_form->isSubmitted()===true && count($o_form->getInvalidElements())===0){
    //form was submitted and is valid. Now we can record the data to the databse table
    //Of course you may want to secure some of the post variables before they enter your database as well.
    $o = $modx->newObject('Contactlog');
    $o->set('name_full',$o_form->postVal('name_full'));
    $o->set('email_address',$o_form->postVal('email_address'));
    $o->set('comments',$o_form->postVal('comments'));
    $o->set('email_html_content',$o_form->getEmailContent());
    $o->set('time_submitted',time());
    $o->save();
}

//The form HTML will now be available via teh output call
//This can be returned in a snippet or passed to any other script to handle in any way.
//$o_form->output();