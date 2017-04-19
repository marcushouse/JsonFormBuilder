<?php
/**
 * Creates an HTML block for the auto generated email body (ignored by form output)
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementEmailHtml extends JsonFormBuilder_element{

	private $_html;
	function __construct( $html ) {
		$this->_html=$html;
		$this->_showInEmail=true;
	}
	public function outputHTML(){
		return '';
	}
	public function outputEmailHTML(){
		return $this->_html;
	}
}