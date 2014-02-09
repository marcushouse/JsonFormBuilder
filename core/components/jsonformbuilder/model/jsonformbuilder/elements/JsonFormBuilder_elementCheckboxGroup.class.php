<?php

/**
 * Creates a group of checkbox elements.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementCheckboxGroup extends JsonFormBuilder_element{
	/**
	 * @ignore 
	 */
	private $_values;
	/**
	 * @ignore 
	 */
	private $_showIndividualLabels;
	/**
	 * @ignore 
	 */
	private $_uncheckedValue;
	/**
	 * @ignore 
	 */
	private $_maxLength;
	/**
	 * @ignore 
	 */
	private $_minLength;
	
	
	/**
	 * JsonFormBuilder_elementCheckboxGroup
	 * 
	 * Creates a group of checkboxes that allow rules such as required, minimum length (minimum number of items that must be checked) and maximum length (maximum number of items that can be checked). The list of checkbox values are specified in an array along with their default ticked state.
	 * 
	 * <code>
	 * $a_checkArray=array(
	 *	array('title'=>'Cheese','checked'=>false),
	 *	array('title'=>'Grapes','checked'=>true),
	 *	array('title'=>'Salad','checked'=>false),
	 *	array('title'=>'Bread','checked'=>true)
	 * );
	 * $o_fe_checkgroup		= new JsonFormBuilder_elementCheckboxGroup('favFoods','Favorite Foods',$a_checkArray);
	 * //Ensure at least 2 checkboxes are selected
	 * $a_formRules[] = new FormRule(FormRuleType::minimumLength,$o_fe_checkgroup,2);
	 * //Ensure no more than 3 checkboxes are selected
	 * $a_formRules[] = new FormRule(FormRuleType::maximumLength,$o_fe_checkgroup,3);
	 * </code>
	 *
	 * @param string $id Id of the element
	 * @param string $label Label of the select element
	 * @param array $values Array of title/value arrays in order of display.
	 */
	function __construct($id, $label, array $values) {
		parent::__construct($id,$label);
		$this->_name = $id.'[]';
		$this->_values = $values;
		$this->_showIndividualLabels = true;
		$this->_uncheckedValue = 'None Selected';
	}
	
	/**
	 * showIndividualLabels($value) / showIndividualLabels()
	 * 
	 * By default (true) each checkbox will have its own label. Users may wish to have checkboxes in some kind of table with custom surrounding HTML. In this case labels can be hidden.  If no value passed the method will return the current label display status. 
	 * @param boolean $value
	 * @return boolean 
	 */
	public function showIndividualLabels($value){
		if(func_num_args() == 0) {
			return $this->_showIndividualLabels;
		}else{
			$this->_showIndividualLabels = JsonFormBuilder::forceBool(func_get_arg(0));
		}
	}
	
	/**
	 * setMinLength($value)
	 * 
	 * Sets the minimum number of checkboxes that must be selected before the checkbox group will be valid (e.g. please select at least two options...).
	 * @param int $value 
	 */
	public function setMinLength($value) {
		$value = JsonFormBuilder::forceNumber($value);
		if($this->_maxLength!==NULL && $this->_maxLength<$value){
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Cannot set minimum length to "'.$value.'" when maximum length is "'.$this->_maxLength.'"');
		}else{
			//if($this->_required===false){
				//JsonFormBuilder::throwError('[Element: '.$this->_id.'] Cannot set minimum length to "'.$value.'" when field is not required.');
			//}else{
				$this->_minLength = JsonFormBuilder::forceNumber($value);
			//}
		}
	}
	/**
	 * setMaxLength($value)
	 * 
	 * Sets the maximum number of checkboxes that can be selected before the checkbox group will be valid (e.g. please select up to three options...).
	 * @param int $value 
	 */
	public function setMaxLength($value) {
		$value = JsonFormBuilder::forceNumber($value);
		if($this->_minLength!==NULL && $this->_minLength>$value){
			throw JsonFormBuilder::throwError('[Element: '.$this->_id.'] Cannot set maximum length to "'.$value.'" when minimum length is "'.$this->_minLength.'"');
		}else{
			$this->_maxLength = JsonFormBuilder::forceNumber($value);
		}
	}
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		$s_ret='<div class="checkboxGroupWrap">';
		$i=0;
		
		$a_uncheckedVal = $this->_uncheckedValue;
		if($this->_required===true){
			$a_uncheckedVal=''; // we do this because FormIt will not validate it as empty if unchecked value has a value.
		}
		//hidden field with same name is so we get a post value regardless of tick status, must use ID and not name
		$s_ret.='<input type="hidden" name="'.htmlspecialchars($this->_id).'" value="'.htmlspecialchars($a_uncheckedVal).'" />';
				
		foreach($this->_values as $value){
			$s_ret.='<div class="checkboxWrap">';
			// changed input type to checkbox
			// added [] to name
			$s_ret.='<div class="checkboxEl"><input type="checkbox" id="'.htmlspecialchars($this->_id.'_'.$i).'" name="'.htmlspecialchars($this->_name).'" value="'.htmlspecialchars($value['title']).'"';
			$selectedStr='';
			if($this->postVal($this->_id)!==NULL){
				if(in_array($value['title'],$this->postVal($this->_id))===true){
					$selectedStr=' checked="checked"';
				}
			}else{
				if(isset($value['checked'])===true && $value['checked']===true){
					$selectedStr=' checked="checked"';
				}
			}
			$s_ret.=$selectedStr.' /></div>';
			if($this->_showIndividualLabels===true){
				$s_ret.='<label for="'.htmlspecialchars($this->_id.'_'.$i).'">'.htmlspecialchars($value['title']).'</label>';
			}
			$s_ret.='</div>'."\r\n";
			$i++;
		}
		$s_ret.='</div>';
		return $s_ret;
	}
}