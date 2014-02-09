<?php
/**
 * Creates a recaptcha field with the FormIt integrated recaptcha systems.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementReCaptcha extends JsonFormBuilder_element{
	/**
	 * @ignore
	 */
	protected $_jsonConfig;
	
	/**
	 * JsonFormBuilder_elementReCaptcha
	 * 
	 * Constructor for the JsonFormBuilder_elementReCaptcha object.
	 * @param string $label 
	 */
	function __construct($label) {
		parent::__construct('recaptcha',$label);
		$this->_showInEmail=false;
	}
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		$s_ret='[[+formit.recaptcha_html]]';
		return $s_ret;
	}
	/**
	 * setJsonConfig($jsonString)
	 * 
	 * Allows the setting of reCaptcha config. See https://developers.google.com/recaptcha/docs/customization for more information
	 * @param string $jsonString 
	 */
	public function setJsonConfig($jsonString){
		$this->_jsonConfig=$jsonString;
	}
	/**
	 * getJsonConfig()
	 * 
	 * Returns the reCaptcha JSON config.
	 * @return string
	 */
	public function getJsonConfig(){
		return $this->_jsonConfig;
	}	
	
}