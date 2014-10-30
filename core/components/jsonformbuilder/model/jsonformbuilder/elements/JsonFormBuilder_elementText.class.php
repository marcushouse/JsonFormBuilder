<?php

/**
 * Creates a text field form element.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementText extends JsonFormBuilder_element{
	/**
	 * @ignore
	 */
	protected $_fieldType;
	/**
	 * @ignore
	 */
	protected $_maxLength;
	/**
	 * @ignore
	 */
	protected $_minLength;
	/**
	 * @ignore
	 */
	protected $_maxValue;
	/**
	 * @ignore
	 */
	protected $_minValue;
	/**
	 * @ignore
	 */
	protected $_dateFormat;
	/**
	 * @ignore
	 */
	protected $_defaultVal;
    public function getDefaultVal() { return $this->_defaultVal; }
    public function setDefaultVal($value) {
		$this->_defaultVal=trim($value);
	}

	/**
	 * JsonFormBuilder_elementText
	 * 
	 * Creates a text field.
	 * @param string $id The ID of the text field
	 * @param string $label The label of the text field
	 * @param string $defaultValue The default text to be written into the text field
	 */
	function __construct( $id, $label, $defaultValue=NULL ) {
		parent::__construct($id,$label);
		$this->_defaultVal = $defaultValue;
		$this->_maxLength=NULL;
		$this->_minLength=NULL;
		$this->_maxValue=NULL;
		$this->_minValue=NULL;
		$this->_fieldType='text';
	}
	/**
	 * getMaxLength()
	 * 
	 * Returns the maximum string length for the field.
	 * @return int
	 */
	public function getMaxLength() { return $this->_maxLength; }
	/**
	 * getMinLength()
	 * 
	 * Returns the minimum string length for the field.
	 * @return int
	 */
	public function getMinLength() { return $this->_minLength; }
	/**
	 * getMaxValue()
	 * 
	 * Returns the maximum value for the field.
	 * @return int
	 */
	public function getMaxValue() { return $this->_maxValue; }
	/**
	 * getMinValue()
	 * 
	 * Returns the minimum string length for the field.
	 * @return int
	 */
	public function getMinValue() { return $this->_minValue; }
	/**
	 * getDateFormat()
	 * 
	 * Returns the date format used by a field with a date FormRule.
	 * @return string
	 */
	public function getDateFormat() { return $this->_dateFormat; }
	
	/**
	 * setMaxLength($value)
	 * 
	 * Sets the maximum string length for the field.
	 * This is generally done automatically via a FormRule.
	 * @param int $value 
	 */
	public function setMaxLength($value) {
		$value = JsonFormBuilder::forceNumber($value);
		if($this->_minLength!==NULL && $this->_minLength>$value){
			throw JsonFormBuilder::throwError('[Element: '.$this->_id.'] Cannot set maximum length to "'.$value.'" when minimum length is "'.$this->_minLength.'"');
		}else{
			$this->_maxLength = JsonFormBuilder::forceNumber($value);
		}
	}
	/**
	 * setMinLength($value)
	 * 
	 * Sets the minimum string length for the field.
	 * This is generally done automatically via a FormRule.
	 * @param int $value 
	 */
	public function setMinLength($value) {
		$value = JsonFormBuilder::forceNumber($value);
		if($this->_maxLength!==NULL && $this->_maxLength<$value){
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Cannot set minimum length to "'.$value.'" when maximum length is "'.$this->_maxLength.'"');
		}else{
			//if($this->_required===false){
				//JsonFormBuilder::throwError('[Element: '.$this->_id.'] Cannot set minimum length to "'.$value.'" when field is not required.');
			//}else{
				$this->_minLength = JsonFormBuilder::forceNumber($value);
			//}
		}
	}
	/**
	 * setMaxValue($value)
	 * 
	 * Sets the maximum numeric value for a field.
	 * This is generally done automatically via a FormRule.
	 * @param int $value 
	 */
	public function setMaxValue($value) {
		$value = JsonFormBuilder::forceNumber($value);
		if($this->_minValue!==NULL && $this->_minValue>$value){
			JsonFormBuilder::throwError('Cannot set maximum value to "'.$value.'" when minimum value is "'.$this->_minValue.'"');
		}else{
			$this->_maxValue = JsonFormBuilder::forceNumber($value);
		}
	}
	/**
	 * setMinValue($value)
	 * 
	 * Sets the minimum numeric value for a field.
	 * This is generally done automatically via a FormRule.
	 * @param int $value 
	 */
	public function setMinValue($value) {
		$value = JsonFormBuilder::forceNumber($value);
		if($this->_maxValue!==NULL && $this->_maxValue<$value){
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Cannot set minimum value to "'.$value.'" when maximum value is "'.$this->_maxValue.'"');
		}else{
			$this->_minValue = JsonFormBuilder::forceNumber($value);
		}
	}
	/**
	 * setDateFormat($value)
	 * 
	 * Sets the date format used by a field with a date FormRule.
	 * This is generally done automatically via a FormRule.
	 * @param string $value 
	 */
	public function setDateFormat($value) {
		$value=trim($value);
		if(empty($value)===true){
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Date format is not valid.');
		}else{
			$this->_dateFormat=$value;
		}
	}
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		$a_classes=array();
		
		//hidden field with same name is so we get a post value regardless of tick status
		if($this->postVal($this->_id)!==false){
			$selectedStr=$this->postVal($this->_id);
		}else{
			$selectedStr=$this->_defaultVal;
		}
		
		$s_ret='<input type="'.$this->_fieldType.'" name="'.htmlspecialchars($this->_id).'" id="'.htmlspecialchars($this->_id).'"'.($this->_fieldType=='file'?'':' value="'.htmlspecialchars($selectedStr).'"');
		if($this->_maxLength!==NULL){
			$s_ret.=' maxlength="'.htmlspecialchars($this->_maxLength).'"';
		}
		if($this->_required===true){
			$a_classes[]='required'; // for jquery validate (or for custom CSSing :) )
		}
		$s_ret.=' '.$this->processExtraAttribsToStr($a_classes).' />';
		return $s_ret;
	}
}