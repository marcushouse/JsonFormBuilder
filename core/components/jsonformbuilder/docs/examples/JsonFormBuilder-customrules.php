<?php
require_once $modx->getOption('core_path',null,MODX_CORE_PATH).'components/jsonformbuilder/model/jsonformbuilder/JsonFormBuilder.class.php';

//CREATE FORM ELEMENTS
$o_fe_name      = new JsonFormBuilder_elementText('name_full','Your Name');
$o_fe_phone      = new JsonFormBuilder_elementText('phone','Phone');
$o_fe_buttSubmit    = new JsonFormBuilder_elementButton('submit','Submit Form','submit');

//SET VALIDATION RULES
$a_formRules=array();

////////////////
//CUSTOM RULES//
////////////////
/*
There are a few parts to this if you want to add custom validation well. Firstly, if using the jQuery validate functionality,
ideally you want to have your custom rule validating before server side processing (as well as of course during server side processing).

As long as the following javascript is run after jQuery validate had been included, you can add as many validation methods as you like.
If you have a collection of common rules it would be beneficial to have them in a separate javascript file.
See docs/examples/JsonFormBuilder-template.php for an example of the customPhoneNum validation method.
*/

//Add the custom validation method to your form element by doing the following

//1. Create a custom rule.
$numBlocks = array(3,3,4);
$rule = new FormRule(FormRuleType::custom, $o_fe_phone, NULL, 'Phone number must be in format '.str_repeat("#",$numBlocks[0]).'-'.str_repeat("#",$numBlocks[1]).'-'.str_repeat("#",$numBlocks[2]));
//2. For jQuery validate to file off we need to tell it what rule to match
$rule->setCustomRuleName('customPhoneNum');
//3. Optionally we can pass a value to it as well if the validation method needs to know a variable (e.g. minLength, or the example in the template).
$rule->setCustomRuleParam($numBlocks);

//4. Create an anonymous validate function that will be used to determine value at the server side, and specify to use this via the setCustomRuleValidateFunction method.
//$val will contain the form element value, $var will contain the contents of the param specified in step 3 above.
$func = function($val=null,$var=null){
    if(empty($val)===false){
        $phoneVal = $val;
        $foundMatches = preg_match ('/^\(?(\d{'.$var[0].'})\)?[- ]?(\d{'.$var[1].'})[- ]?(\d{'.$var[2].'})$/',$phoneVal);
        if($foundMatches!==1){
            return false;
        }
    }
    return true;
};
$rule->setCustomRuleValidateFunction($func);

//Finally, add the rule to the form
$a_formRules[] = $rule;

  
//CREATE FORM AND SETUP
$o_form = new JsonFormBuilder($modx,'contactForm');
$o_form->setRedirectDocument(3);
$o_form->addRules($a_formRules);

//SETUP EMAIL
//Note, this is not required, you may want to not send an email and record the data to a database.
$o_form->setEmailToAddress($modx->getOption('emailsender'));
$o_form->setEmailFromAddress('no@where.com');
$o_form->setEmailFromName('No One');
$o_form->setEmailSubject('JsonFormBuilder Custom Validate Test');

//Set jQuery validation on and to be output
//$o_form->setJqueryValidation(true);
//You can specify that the javascript is sent into a placeholder for those that have jquery scripts just before body close. If jquery scripts are in the head, no need for this.
$o_form->setPlaceholderJavascript('JsonFormBuilder_myForm');
  
//ADD ELEMENTS TO THE FORM IN PREFERRED ORDER
$o_form->addElements(
    array(
       $o_fe_phone,$o_fe_buttSubmit
    )
);

//The form HTML will now be available via
//$o_form->output();
//This can be returned in a snippet or passed to any other script to handle in any way.