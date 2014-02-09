<?php

/**
 * Creates a password field element.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementPassword extends JsonFormBuilder_elementText{
	/**
	 * JsonFormBuilder_elementPassword
	 * 
	 * Creates a password field.
	 * @param string $id The ID of the password field
	 * @param string $label The label of the password field
	 * @param string $defaultValue The default text to be written into the password field
	 */
	function __construct( $id, $label, $defaultValue=NULL ) {
		parent::__construct($id,$label,$defaultValue);
		$this->_fieldType='password';
	}
}
