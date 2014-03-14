<?php
require_once $modx->getOption('core_path',null,MODX_CORE_PATH).'components/jsonformbuilder/model/jsonformbuilder/JsonFormBuilder.class.php';

//CREATE FORM ELEMENTS
$o_fe_name      = new JsonFormBuilder_elementText('name_full','Your Name');
$o_fe_email     = new JsonFormBuilder_elementText('email_address','Email Address');
$o_fe_buttSubmit    = new JsonFormBuilder_elementButton('submit','Submit Form','submit');
  
//SET VALIDATION RULES
$a_formRules=array();
//Set required fields
$a_formFields_required = array($o_fe_name, $o_fe_email);
foreach($a_formFields_required as $field){
    $a_formRules[] = new FormRule(FormRuleType::required,$field);
}
$a_formRules[] = new FormRule(FormRuleType::email, $o_fe_email, NULL, 'Please provide a valid email address');
  
//CREATE FORM AND SETUP
$o_form = new JsonFormBuilder($modx,'contactForm');
$o_form->addRules($a_formRules);
$o_form->setJqueryValidation(true);
$o_form->setPlaceholderJavascript('JsonFormBuilder_myForm');
  
//ADD ELEMENTS TO THE FORM IN PREFERRED ORDER
$o_form->addElements(
    array(
        $o_fe_name,$o_fe_email,$o_fe_buttSubmit
    )
);

$o_form->validate();

if($o_form->isSubmitted()===true && count($o_form->getInvalidElements())===0){
    //form was submitted and is valid. Now we can do what we want and redirect elsewhere manually.
    echo 'Form was submitted and was valid.';
    exit();
}else{
    //otherwise run the form as normal (which will display the errors).
    //$o_form->output();
}
