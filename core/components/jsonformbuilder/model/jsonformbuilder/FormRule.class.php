<?php
/**
 * Contains rule processor class.
 * @package JsonFormBuilder
 */

/**
 * Required Files
 */
require_once dirname(__FILE__).'/FormRuleType.class.php';

/**
 * Contains rule methods applied to an element. The JsonFormBuilder object is then assigned rules using the addRules method.
 * @package JsonFormBuilder
 */
class FormRule extends JsonFormBuilderCore{
	/**
	 * @ignore
	 */
	private $_type;
    private $_condition;
    public function getCondition() { return $this->_condition; }
    public function setCondition($value) { if(empty($value)){ JsonFormBuilder::throwError('Condition empty.'); }else{ $this->_condition = $value; } }
    private $_customRuleName;
    public function getCustomRuleName() { return $this->_customRuleName; }
    public function setCustomRuleName($value) { $this->_customRuleName = $value; }
    private $_customRuleParam;
    public function getCustomRuleParam() { return $this->_customRuleParam; }
    public function setCustomRuleParam($value) { $this->_customRuleParam = $value; }
    private $_customRuleValidateFunction;
    public function getCustomRuleValidateFunction() { return $this->_customRuleValidateFunction; }
    public function setCustomRuleValidateFunction($value) { $this->_customRuleValidateFunction = $value; }
	/**
	 * @ignore
	 */
	private $_element;
	/**
	 * @ignore
	 */
	private $_value;
	/**
	 * @ignore
	 */
	private $_validationMessage;
	/**
	 * Returns the rule type (should match a FormRuleType constant).
	 * @return string 
	 */
	public function getType() { return $this->_type; }
    public function setType($type) {
        //verify we have a single form element or an array of them
        $addedError = 'Was passed as a "'.$type.'" FormRule.';
        $element = $this->getElement();
		if(is_array($element)===false){
			JsonFormBuilder::verifyFormElement($element,$addedError);
		}else{
			foreach($element as $el){
				JsonFormBuilder::verifyFormElement($el,$addedError);
			}
		}
        $this->_type = $type;
        $this->refresh();
    }
	/**
	 * Returns a reference to the element object .
	 * @return object
	 */
	public function getElement() { return $this->_element; }
    public function setElement($value) {
        $this->_element = $value;
        $this->refresh();
    }
	/**
	 * Returns rule value.
	 * @return string
	 */
	public function getValue() { return $this->_value; }
    public function setValue($value) {
        $this->_value = $value;
        $this->refresh();
    }
	/**
	 * Returns the rule validation message.
	 * @return string 
	 */
	public function getValidationMessage() { return $this->_validationMessage; }
	/**
	 * Sets the validation message to be used if the rule fails validation.
	 * @param string $value 
	 */
	public function setValidationMessage($value) {
        $this->_validationMessage = $value;
        $this->refresh();
    }
	
    public function refresh(){
        $type = $this->getType();
        $validationMessage = $this->getValidationMessage();
        $element = $this->getElement();
        $value = $this->getValue();
        if(empty($type) || empty($element)){
            //if elements have not yet passed, don't bother setting validation messages.
            return;
        }
        
		switch($type){
			
			//form field match, password confirm etc
			case FormRuleType::fieldMatch:
                
                if($value && is_object($value)){
                    if($validationMessage===NULL){
                        $this->_validationMessage = $element->getLabel().' must match '.$value->getLabel();
                    }
                }else{
                    $this->_validationMessage = $element->getLabel().' does not match';
                }
				break;
				
			//true false type validators
			case FormRuleType::email:
				if($validationMessage===NULL){
					 $this->_validationMessage = $element->getLabel().' must be a valid email address';
				}
				break;
			case FormRuleType::numeric:
				if($validationMessage===NULL){
					 $this->_validationMessage = $element->getLabel().' must be numeric';
				}
				break;
			case FormRuleType::required:
				if($validationMessage===NULL){
					$this->_validationMessage = $element->getLabel().' is required'; 
				}
                $element->isRequired(true);
				break;
				
			//value driven number type validators
			case FormRuleType::maximumLength:
                if($value){
                    $value = JsonFormBuilder::forceNumber($value);
                    if(is_a($element, 'JsonFormBuilder_elementCheckboxGroup')){
                        $this->_validationMessage = $element->getLabel().' must have less than '.($value+1).' selected';
                    }else{
                        $this->_validationMessage = $element->getLabel().' can only contain up to '.$value.' characters';
                    }

                    $element->setMaxLength($value);
                }
				break;
			case FormRuleType::maximumValue:
                if($value){
                    $value = JsonFormBuilder::forceNumber($value);
                    if($validationMessage===NULL){
                        $this->_validationMessage = $element->getLabel().' must not be greater than '.$value;
                    }
                    $element->setMaxValue($value);
                }
				break;
			case FormRuleType::minimumLength:
                if($value){
                    $value = JsonFormBuilder::forceNumber($value);
                    if($validationMessage===NULL){
                        if(is_a($element, 'JsonFormBuilder_elementCheckboxGroup')){
                            $this->_validationMessage = $element->getLabel().' must have at least '.$value.' selected';
                        }else{
                            $this->_validationMessage = $element->getLabel().' must be at least '.$value.' characters';
                        }
                    }
                    $element->setMinLength($value);
                }
				break;
			case FormRuleType::minimumValue:
                if($value){
                    $value = JsonFormBuilder::forceNumber($value);
                    if($validationMessage===NULL){
                        $this->_validationMessage = $element->getLabel().' must not be less than '.$value;
                    }
                    $element->setMinValue($value);
                }
				break;	
			case FormRuleType::date:
				/*
				Supports any single character separator with any order of dd,mm and yyyy
				Example: yyyy-dd-mm dd$$mm$yyyy dd/yyyy/mm.
				Dates will be split and check if a real date is entered.
				*/
                if($value){
                    $value=strtolower(trim($value));
                    if(empty($value)===true){
                        JsonFormBuilder::throwError('Date type field must have a value (date format) specified.');
                    }
                    if($validationMessage===NULL){
                        $this->_validationMessage = $element->getLabel().' must be a valid date (===dateformat===).';
                    }
                }
				break;
			case FormRuleType::file:
				if($validationMessage===NULL){
					 $this->_validationMessage = $element->getLabel().' must be a valid file.';
				}
				break;
            case FormRuleType::conditionShow:
				//is only used by jQuery
				break;
            case FormRuleType::custom:
				if($validationMessage===NULL){
					 $this->_validationMessage = $element->getLabel().' is not valid.';
				}
				break;
			default:
				JsonFormBuilder::throwError('Type "'.$type.'" not valid. Recommend using FormRule constant');
				break;
		}
    }
	/**
	 * FormRule
	 * 
	 * Form Rule constructor
	 * @param string $type Recommend using FormRule constant to determine rule types
	 * @param mixed $element A single form element or an array of form elements
	 * @param mixed $value Some elements may set a value (e.g. a maximum width of 5 would use the value 5).
	 * @param string $validationMessage A validation message to be used if the rule fails validation.
	 */
	function __construct($type, $element, $value=NULL, $validationMessage=NULL, $condition=NULL ) {
        //force null if empty (so that false will end up with the same results.
        if(!empty($element)){ $this->setElement($element); }
        if(!empty($type)){ $this->setType($type); }
        if(!empty($value)){ $this->setValue($value); }
        if(!empty($validationMessage)){ $this->setValidationMessage($validationMessage); }
        if(!empty($condition)){ $this->setCondition($condition); }

		$this->refresh();
	}
}