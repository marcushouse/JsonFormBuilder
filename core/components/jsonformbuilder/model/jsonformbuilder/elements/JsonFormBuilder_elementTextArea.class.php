<?php
/**
 * Creates a text area element.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementTextArea extends JsonFormBuilder_element{
	/**
	 * @ignore 
	 */
	private $_defaultVal;
	/**
	 * @ignore 
	 */
	private $_rows;
	/**
	 * @ignore 
	 */
	private $_cols;

	/**
	 * JsonFormBuilder_elementTextArea
	 * 
	 * Creates a text area element.
	 * @param string $id ID of text area
	 * @param string $label The label of text area
	 * @param int $rows The required rows (attribute value that must be set on a valid XHTML textarea tag)
	 * @param int $cols The required cols (attribute value that must be set on a valid XHTML textarea tag)
	 * @param string $defaultValue The default text to be written into the text area
	 */
	function __construct($id, $label, $rows, $cols, $defaultValue=NULL) {
		parent::__construct($id,$label);
		$this->_defaultVal = $defaultValue;
		$this->_rows = JsonFormBuilderCore::forceNumber($rows);
		$this->_cols = JsonFormBuilderCore::forceNumber($cols);
	}
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		//hidden field with same name is so we get a post value regardless of tick status
		if($this->postVal($this->_id)!==NULL){
			$selectedStr=htmlspecialchars($this->postVal($this->_id));
		}else{
			$selectedStr=htmlspecialchars($this->_defaultVal);
		}
		if($this->_required===true){
			$a_classes[]='required'; // for jquery validate (or for custom CSSing :) )
		}
		
		$s_ret='<textarea id="'.htmlspecialchars($this->_id).'" rows="'.htmlspecialchars($this->_rows).'" cols="'.htmlspecialchars($this->_cols).'" name="'.htmlspecialchars($this->_id).'"';
		//add classes last
		if(count($a_classes)>0){
			$s_ret.=' class="'.implode(' ',$a_classes).'"';
		}
		$s_ret.='>'.$selectedStr.'</textarea>';
		return $s_ret;
	}
}