<?php

/**
 * Creates a hidden field form element.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementHidden extends JsonFormBuilder_elementText{
	/**
	 * JsonFormBuilder_elementHidden
	 * 
	 * Creates a hidden field.
	 * @param string $id The ID of the hidden field
	 * @param string $label The label of the hidden field
	 * @param string $defaultValue The default value to be written into the hidden field
	 */
	function __construct( $id, $label, $defaultValue=NULL ) {
		parent::__construct($id,$label,$defaultValue);
		$this->_fieldType='hidden';
		$this->_showInEmail=false;
	}
}