<?php

/**
 * A primitive form element used only to inject raw html and place between other elements.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_htmlBlock extends JsonFormBuilder_baseElement{
	/**
	 * @ignore 
	 */
	private $_html;
	/**
	 * JsonFormBuilder_htmlBlock
	 * 
	 * Creates a segment of the specified html. This is great for introducing your own separators or wrappers around other elements in the form.
	 * @param string $html The html code to use as the element
	 */
	function __construct( $html ) {		
		$this->_html=$html;
	}
	
	/**
	 * outputHTML()
	 * 
	 * output function called when generating the form elements content.
	 * @return string
	 */
	public function outputHTML(){
		return $this->_html;
	}
}