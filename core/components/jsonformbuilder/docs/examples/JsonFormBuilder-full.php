<?php
require_once $modx->getOption('core_path',null,MODX_CORE_PATH).'components/jsonformbuilder/model/jsonformbuilder/JsonFormBuilder.class.php';      
 
/*--------------------*/
/*CREATE FORM ELEMENTS*/
/*--------------------*/
//Text Fields
$o_fe_name          = new JsonFormBuilder_elementText('name_full','Full Name');
$o_fe_age           = new JsonFormBuilder_elementText('age','Age');
$o_fe_dob           = new JsonFormBuilder_elementText('date_of_birth','Date of Birth');
$o_fe_username      = new JsonFormBuilder_elementText('username','Username');
$o_fe_userPass      = new JsonFormBuilder_elementPassword('user_pass','Password');
$o_fe_userPass2     = new JsonFormBuilder_elementPassword('user_pass2','Confirm Password');
$o_fe_address       = new JsonFormBuilder_elementText('address','Address');
$o_fe_city          = new JsonFormBuilder_elementText('city','City/Suburb');
$o_fe_postcode      = new JsonFormBuilder_elementText('postcode','Post Code');
$o_fe_company       = new JsonFormBuilder_elementText('company','Company Name');
$o_fe_companyPhone  = new JsonFormBuilder_elementText('company_phone','Company Phone');
$o_fe_email         = new JsonFormBuilder_elementText('email_address','Email Address');
$o_fe_file          = new JsonFormBuilder_elementFile('resume', 'Resume');
 
//Check Boxes
$o_fe_checkTerms    = new JsonFormBuilder_elementCheckbox('agree_terms','I agree to the terms & conditions', 'Agree', 'Disagree', false);
$o_fe_checkNews     = new JsonFormBuilder_elementCheckbox('agree_newsletter','Sign me up for some spam', 'Wants Spam', 'Does <strong>NOT</strong> want spam', false);
//Dropdown selects
$a_employees=array(
    '10'=>'Less than 10',
    '11 to 20'=>'11 to 20',
    '50'=>'21 to 50',
    '100'=>'51 to 100',
    '100+'=>'More than 100',
);
$o_fe_employees     = new JsonFormBuilder_elementSelect('employees','Number of Employees',$a_employees,'11 to 20');
$a_usstates = array(
    ''=>'Please select...',
    'AL'=>'Alabama',
    'AK'=>'Alaska',
    'AZ'=>'Arizona',
    'AR'=>'Arkansas',
    'CA'=>'California',
    'CO'=>'Colorado',  
    'CT'=>'Connecticut',
);
$o_fe_usstates      = new JsonFormBuilder_elementSelect('ussuate','Select a state',$a_usstates);
//radio groups
$a_performanceOptions = array(
    'opt1'=>'Poor',
    'opt2'=>'Needs Improvement',
    'opt3'=>'Average',
    'opt4'=>'Good',
    'opt5'=>'Excellent',
);
$o_fe_staff         = new JsonFormBuilder_elementRadioGroup('staff_performance','How would you rate staff performance?',$a_performanceOptions);
//Text area
$o_fe_notes         = new JsonFormBuilder_elementTextArea('notes','Additional Comments',5,30,
'Here is an example of default multiline text.
 
--- JsonFormBuilder ---
');
//Form Buttons
$o_fe_buttSubmit    = new JsonFormBuilder_elementButton('submit','Submit Form','submit');
$o_fe_buttReset     = new JsonFormBuilder_elementButton('reset','Reset Form','reset');
 
 
/*--------------------*/
/*SET VALIDATION RULES*/
/*--------------------*/
$a_formRules=array();

//Set required fields
$a_formFields_required = array($o_fe_file, $o_fe_notes, $o_fe_name, $o_fe_age, $o_fe_dob, $o_fe_username, $o_fe_userPass, $o_fe_userPass2, $o_fe_email, $o_fe_postcode);
foreach($a_formFields_required as $field){
    $a_formRules[] = new FormRule(FormRuleType::required,$field);
}
$a_formRules[] = new FormRule(FormRuleType::email, $o_fe_email, NULL, 'Please provide a valid email address');
$a_formRules[] = new FormRule(FormRuleType::numeric, $o_fe_postcode);
$a_formRules[] = new FormRule(FormRuleType::required, $o_fe_checkTerms, NULL, 'You must agree to the terms and conditions');
$a_formRules[] = new FormRule(FormRuleType::required, $o_fe_staff, NULL, 'Please select an option for staff performance');
//additional rules for postcode
$a_formRules[] = new FormRule(FormRuleType::minimumLength, $o_fe_postcode, 4);
$a_formRules[] = new FormRule(FormRuleType::maximumLength, $o_fe_postcode, 4);
//additional rules for username
$a_formRules[] = new FormRule(FormRuleType::minimumLength, $o_fe_username, 6);
$a_formRules[] = new FormRule(FormRuleType::maximumLength, $o_fe_username, 30);
//additional rules for age field
$a_formRules[] = new FormRule(FormRuleType::numeric, $o_fe_age);
$a_formRules[] = new FormRule(FormRuleType::minimumValue, $o_fe_age, 18);
$a_formRules[] = new FormRule(FormRuleType::maximumValue, $o_fe_age, 100);
//additional rules for DOB
$a_formRules[] = new FormRule(FormRuleType::date, $o_fe_dob, 'dd/mm/yyyy');
//A unique case, when checking if passwords match pass the two fields as an array into the second argument.
$a_formRules[] = new FormRule(FormRuleType::minimumLength, $o_fe_userPass, 8);
$a_formRules[] = new FormRule(FormRuleType::fieldMatch, array($o_fe_userPass2,$o_fe_userPass), NULL, 'Passwords do not match');
  
/*----------------------------*/
/*CREATE FORM AND ADD ELEMENTS*/
/*----------------------------*/
$o_form = new JsonFormBuilder($modx,'myContactForm');
$o_form->setRedirectDocument(3);
$o_form->addRules($a_formRules);
  
//specify to anf from email addresses, also see replyTo, CC and BCC options
$o_form->setEmailToName('To Name');
$o_form->setEmailToAddress($modx->getOption('emailsender'));
$o_form->setEmailFromAddress($o_form->postVal('email_address'));
$o_form->setEmailFromName($o_form->postVal('name_full'));
  
$o_form->setEmailSubject('MyCompany Contact Form Submission - From: '.$o_form->postVal('name_full'));
$o_form->setEmailHeadHtml('<p>This is a response sent by '.$o_form->postVal('name_full').' using the contact us form:</p>');
$o_form->setJqueryValidation(true);
$o_form->setPlaceholderJavascript('JsonFormBuilder_myForm');
  
//add elements to form in preferred order
$o_form->addElements(
    array(
        '<h2>Personal Information</h2>',
        $o_fe_name, $o_fe_age,  $o_fe_dob, $o_fe_username,  $o_fe_userPass, $o_fe_userPass2, $o_fe_email,
        '<h2>Address</h2>',
        $o_fe_address,  $o_fe_city, $o_fe_usstates, $o_fe_postcode,
        '<h2>Company Information</h2>',
        $o_fe_company,  $o_fe_companyPhone, $o_fe_employees, $o_fe_staff, $o_fe_notes, $o_fe_file,
        '<div class="checkboxes">',
            $o_fe_checkNews, $o_fe_checkTerms,
        '</div>',
        $o_fe_buttSubmit,   $o_fe_buttReset
    )
);