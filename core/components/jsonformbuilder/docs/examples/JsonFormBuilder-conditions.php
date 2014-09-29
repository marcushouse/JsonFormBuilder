<?php
require_once $modx->getOption('core_path',null,MODX_CORE_PATH).'components/jsonformbuilder/model/jsonformbuilder/JsonFormBuilder.class.php';

//SET VALIDATION RULES
$a_formRules=array();

//CREATE FORM ELEMENTS
$o_fe_name      = new JsonFormBuilder_elementText('name_full','Full Name');
$o_fe_email     = new JsonFormBuilder_elementText('email_address','Email Address');

$a_opts = array(
    ''=>'Please select...',
    'Yes'=>'Yes',
    'No'=>'No',
);
$o_fe_havedog      = new JsonFormBuilder_elementSelect('havedog','Do you have a dog?',$a_opts);
$o_fe_dogname     = new JsonFormBuilder_elementText('dogname','Name of Dog');

//NOTE: Requires jQuery Validate to work.
//Conditional required rule example
$r = new FormRule(FormRuleType::required,$o_fe_dogname,NULL,'As you have a dog, please tell us its name.');
$r->setCondition(array($o_fe_havedog,'Yes'));
$a_formRules[] = $r;
//You can create a Show rule which will keep the field hidden, unless the value of another field is selected.
$r = new FormRule(FormRuleType::conditionShow,$o_fe_dogname);
$r->setCondition(array($o_fe_havedog,'Yes'));
$a_formRules[] = $r;


$o_fe_havecat      = new JsonFormBuilder_elementRadioGroup('havecat','Do you have a cat?',array(
    'Yes'=>'Yes',
    'No'=>'No',
));
$o_fe_catname     = new JsonFormBuilder_elementText('catname','Name of Cat');

//Same setup for a radio group
$r = new FormRule(FormRuleType::required,$o_fe_catname,NULL,'As you have a cat, please tell us its name.');
$r->setCondition(array($o_fe_havecat,'Yes'));
$a_formRules[] = $r;
$r = new FormRule(FormRuleType::conditionShow,$o_fe_catname);
$r->setCondition(array($o_fe_havecat,'Yes'));
$a_formRules[] = $r;

$o_fe_buttSubmit    = new JsonFormBuilder_elementButton('submit','Submit Form','submit');
  

//Set required fields
$a_formFields_required = array($o_fe_havedog,$o_fe_havecat,$o_fe_name,$o_fe_email);
foreach($a_formFields_required as $field){
    $a_formRules[] = new FormRule(FormRuleType::required,$field);
}

        
//Make email field require a valid email address
$a_formRules[] = new FormRule(FormRuleType::email, $o_fe_email, NULL, 'Please provide a valid email address');
  
//CREATE FORM AND SETUP
$o_form = new JsonFormBuilder($modx,'contactForm');
$o_form->setRedirectDocument(3);
$o_form->addRules($a_formRules);
$o_form->setEmailToAddress($modx->getOption('emailsender'));
$o_form->setEmailFromAddress($o_form->postVal('email_address'));
$o_form->setEmailFromName($o_form->postVal('name_full'));
$o_form->setEmailSubject('JsonFormBuilder Contact Form Submission - From: '.$o_form->postVal('name_full'));
$o_form->setEmailHeadHtml('<p>This is a response sent by '.$o_form->postVal('name_full').' using the contact us form:</p>');
$o_form->setJqueryValidation(true);
$o_form->setPlaceholderJavascript('JsonFormBuilder_myForm');
  
//ADD ELEMENTS TO THE FORM IN PREFERRED ORDER
$o_form->addElements(
    array(
        $o_fe_name,$o_fe_email,$o_fe_havedog,$o_fe_dogname,$o_fe_havecat,$o_fe_catname,$o_fe_buttSubmit
    )
);

//The form HTML will now be available via
//$o_form->output();
//This can be returned in a snippet or passed to any other script to handle in any way.