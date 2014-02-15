<?php
/**
 * JsonFormBuilder_elementFile
 * 
 * Creates a file field form element.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementFile extends JsonFormBuilder_elementText{
	/**
	 * Creates a file field element allowing upload of file to the server (and attached to email)
	 * @param string $id The ID of the file element
	 * @param string $label The label of the file element
	 */
	function __construct( $id, $label ) {
		parent::__construct($id,$label);
		$this->_fieldType='file';
		$this->_showInEmail=true;
	}
}