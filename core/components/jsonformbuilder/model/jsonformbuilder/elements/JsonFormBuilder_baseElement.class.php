<?php
require_once dirname(__FILE__).'/JsonFormBuilder_element.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementText.class.php';

require_once dirname(__FILE__).'/JsonFormBuilder_elementButton.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementCheckbox.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementCheckboxGroup.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementDate.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementFile.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementHidden.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementMatrix.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementPassword.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementRadioGroup.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementReCaptcha.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementSelect.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementTextArea.class.php';
require_once dirname(__FILE__).'/JsonFormBuilder_elementEmailHtml.class.php';
/**
 * A primitive form element used as a base to extend into a variety of elements
 * @package JsonFormBuilder
 */
class JsonFormBuilder_baseElement extends JsonFormBuilderCore{
	/**
	 * @ignore 
	 */
	private $_customObject;
	/**
	 * setCustomObject($value)
	 * 
	 * Not used via JsonFormBuilder in anyway, however lets users attach object references or data into this property and then retrive the data via script. Very much a power user feature.
	 * @param mixed $value Can set anything here.
	 */
	public function setCustomObject($value) { $this->_customObject = $value; }
	
	/**
	 * getCustomObject()
	 * 
	 * Returns the custom object.
	 * @return mixed
	 */
	public function getCustomObject() { return $this->_customObject; }
}
