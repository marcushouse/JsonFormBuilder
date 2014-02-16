<?php

/**
 * Creates a checkbox form element.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementCheckbox extends JsonFormBuilder_element{
	/**
	 * @ignore 
	 */
	private $_value;
	/**
	 * @ignore 
	 */
	private $_uncheckedValue;
	/**
	 * @ignore 
	 */
	private $_checked;
	/**
	 * JsonFormBuilder_elementCheckbox
	 * 
	 * Creates a checkbox form element.
	 *
	 * @param string $id ID of checkbox
	 * @param string $label Label of checkbox
	 * @param string $value Value to show if user selects the checkbox
	 * @param boolean $uncheckedValue Value to show if user does not check the checkbox
	 * @param mixed $checked The checkbox will be checked by default if true is supplied OR if the checked value is supplied. e.g. if checked value is "Agree" and this parameter is set to "Agree" the checkbox will be checked by default.
	 */
	function __construct( $id, $label, $value='Checked', $uncheckedValue='Unchecked', $checked=false) {
		parent::__construct($id,$label);
		$this->_value=$value;
		$this->_checked=$checked;
		$this->_uncheckedValue=$uncheckedValue;
        $this->_labelAfterElement=true;
	}
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		$a_uncheckedVal = $this->_uncheckedValue;
		if($this->_required===true){
			$a_uncheckedVal=''; // we do this because FormIt will not validate it as empty if unchecked value has a value.
		}
		if($this->postVal($this->_id)!==NULL){
			if($this->postVal($this->_id)==$this->_value){
				$selectedStr=' checked="checked"';
			}
		}else{
			if($this->_checked===true || $this->_value==$this->_checked){
				$selectedStr=' checked="checked"';
			}
		}
		//hidden field with same name is so we get a post value regardless of tick status
		$s_ret='<input type="hidden" name="'.htmlspecialchars($this->_id).'" value="'.htmlspecialchars($a_uncheckedVal).'" />'
		.'<input type="checkbox" id="'.htmlspecialchars($this->_id).'" name="'.htmlspecialchars($this->_id).'" value="'.htmlspecialchars($this->_value).'"'.$selectedStr.' />';
		return $s_ret;
	}
}