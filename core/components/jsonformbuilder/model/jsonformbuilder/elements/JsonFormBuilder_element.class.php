<?php
/**
 * A less primitive form element used as a base to extend into a variety of elements containing properties and methods used by all physical form elements.
 * @package JsonFormBuilder
 */
abstract class JsonFormBuilder_element extends JsonFormBuilder_baseElement{
	/**
	 * @ignore
	 */
	protected $_id;
	/**
	 * Usually the same as the id, but not in the case of checkbox group that uses array syntax for name.
	 * @ignore
	 */
	protected $_name; //
	/**
	 * @ignore
	 */
	protected $_label;
	/**
	 * @ignore
	 */
	protected $_description;
	/**
	 * @ignore
	 */
	protected $_showLabel;
	/**
	 * @ignore
	 */
	protected $_required;
	/**
	 * @ignore
	 */
	protected $_showInEmail;
	/**
	 * @ignore
	 */
	protected $_extraClasses;
	/**
	 * @ignore
	 */
	protected $_labelAfterElement;

	/**
	 * output function called when generating the form element content.
	 * @return string
	 */
	abstract protected function outputHTML();
	
	/**
	 * JsonFormBuilder_element
	 * 
	 * Main element constructor.
	 * @param string $id
	 * @param string $label 
	 */
	function __construct( $id, $label ) {		
		$this->_required = false;
		$this->_id = $this->_name = $id;
		$this->_label = $label;
		$this->_showLabel = true;
		$this->_showInEmail = true;
		$this->_description = NULL; //must be set by setDescription
		$this->_extraClasses = NULL;
		$this->_labelAfterElement=false;
	}
	
	/**
	 * getId()
	 * 
	 * Returns the form elements ID.
	 * @return string
	 */
	public function getId() { return $this->_id; }
	/**
	 * getName()
	 * 
	 * Returns the form elements name.
	 * @return string
	 */
	public function getName() { return $this->_name; }
	/**
	 * getLabel()
	 * 
	 * Returns the form elements label.
	 * @return string 
	 */
	public function getLabel() { return $this->_label; }
	/**
	 * getDescription()
	 * 
	 * Returns the form elements description.
	 * @return string 
	 */
	public function getDescription() { return $this->_description; }
	/**
	 * getExtraClasses()
	 * 
	 * Returns the array of extra classes applied to the element.
	 * @return array
	 */
	public function getExtraClasses() { return $this->_extraClasses; }
	/**
	 * getLabelAfterElement()
	 * 
	 * Returns a boolean indicating if an element will output label HTML before (false) or after (true) the element.
	 * @return boolean
	 */
	public function getLabelAfterElement() { return $this->_labelAfterElement; }
	
	
	/**
	 * setId($value)
	 * 
	 * Sets the form elements ID.
	 * @param string $value 
	 */
	public function setId($value) { $this->_id = $value; }
	/**
	 * setName($value)
	 * 
	 * Sets the form elements name.
	 * @param string $value 
	 */
	public function setName($value) { $this->_name = $value; }
	/**
	 * setLabel($value)
	 * 
	 * Sets the form elements label.
	 * @param string $value 
	 */
	public function setLabel($value) { $this->_label = $value; }
	/**
	 * setDescription($value)
	 * 
	 * Allows a sub label (or more descriptive label) to be set within the element label. Could be shown on hover or displayed with main label.
	 * @param string $value 
	 */
	public function setDescription($value) { $this->_description = $value; }
	/**
	 * setExtraClasses($value)
	 * 
	 * Allows you to add your own classes on the wrapper element so you can apply specific CSS commands to one or more fields.
	 * @param array $value An array of class strings.
	 */
	public function setExtraClasses($value) { $this->_extraClasses = self::forceArray($value); }
	
	/**
	 * setLabelAfterElement($value)
	 * 
	 * By default all labels are output before the element itself. Although CSS can position one in from of the other it can cause extra frustration when trying to align elements in a variety of ways. By setting this property to true you can force an element to output the lable after the element.
	 * @param boolean $value If true label HTML will output after the element instead of before.
	 */
	public function setLabelAfterElement($value) { $this->_labelAfterElement = self::forceBool($value); }
	
        
	/**
	 * showLabel($value) / showLabel()
	 * 
	 * Sets wether the label should be displayed for this element or not. If no value passed the method will return the current status.
	 * @param boolean $value If true (which is in most cases default) a label will be shown next to the form element and in the email.
	 * @return boolean 
	 */
	public function showLabel($value=null){
		if(func_num_args() == 0) {
			return $this->_showLabel;
		}else{
			$this->_showLabel = JsonFormBuilder::forceBool($value);
		}
	}
	/**
	 * isRequired($value) / isRequired()
	 * 
	 * Sets wether the element is required or not.
	 * 
	 * This setting is generally toggled automatically based on FormRule settings applied in the form.
	 * 
	 * If no value passed the method will return the current required status.
	 * 
	 * @param boolean $value If true the field must be filled out.
	 * @return boolean 
	 */
	public function isRequired($value=null){
		if(func_num_args() == 0) {
			return $this->_required;
		}else{
			$this->_required = JsonFormBuilder::forceBool($value);
		}
	}
	/**
	 * showInEmail($value) / showInEmail()
	 * 
	 * Sets wether the element is shown in the email or not. 
	 * 
	 * In some cases fields may be wanted in the form, but not in the email
	 * (an example would be fields like a "Confirm Password" field).
	 * 
	 * If no value passed the method will return the current status.
	 * 
	 * @param boolean $value If true (which is in most cases default) the element will be shown in the email.
	 * @return boolean
	 */
	public function showInEmail($value=null){
		if(func_num_args() == 0) {
			return $this->_showInEmail;
		}else{
			$this->_showInEmail = JsonFormBuilder::forceBool($value);
		}
	}
}
