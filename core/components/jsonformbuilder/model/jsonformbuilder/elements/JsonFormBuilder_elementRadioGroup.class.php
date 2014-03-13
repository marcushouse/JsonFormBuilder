<?php
/**
 * Creates a radio button group.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementRadioGroup extends JsonFormBuilder_element{
	/**
	 * @ignore 
	 */
	private $_values;
    public function getValues() { return $this->_values; }
    public function setValues($value) { $this->_values = $value; }
	/**
	 * @ignore 
	 */
	private $_defaultVal;
    public function getDefaultVal() { return $this->_defaultVal; }
    public function setDefaultVal($value) { $this->_defaultVal = $value;  }
	/**
	 * @ignore 
	 */
	private $_showIndividualLabels;
	
	/**
	 * JsonFormBuilder_elementRadioGroup
	 * 
	 * Creates a group of radio button elements.
	 * 
	 * <code>
	 * $a_performanceOptions = array(
	 *	'opt1'=>'Poor',
	 *	'opt2'=>'Needs Improvement',
	 *	'opt3'=>'Average',
	 *	'opt4'=>'Good',
	 *	'opt5'=>'Excellent'
	 * );
	 * $o_fe_staff = new JsonFormBuilder_elementRadioGroup('staff_performance','How would you rate staff performance?',$a_performanceOptions,'opt3');
	 * </code>
	 *
	 * @param string $id The ID of the element
	 * @param string $label The label of the select element
	 * @param array $values An array of title/value arrays in order of display
	 * @param string $defaultValue The value of the default selected radio option
	 */
	function __construct($id, $label, array $values, $defaultValue=null) {
		parent::__construct($id,$label);
		$this->_showIndividualLabels = true;
		$this->setValues($values);
		$this->setDefaultVal($defaultValue);
	}
	
	/**
	 * showIndividualLabels($value)
	 * 
	 * By default (true) each radio option will have its own label. Users may wish to have radio options in some kind of table with custom surrounding HTML. In this case labels can be hidden.  If no value passed the method will return the current label display status. 
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
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		$s_ret='<div class="radioGroupWrap">';
		$i=0;
		foreach($this->_values as $key=>$value){
			$s_ret.='<div class="radioWrap">';
			if($this->_showIndividualLabels===true){
				$s_ret.='<label for="'.htmlspecialchars($this->_id.'_'.$i).'">'.htmlspecialchars($value).'</label>';
			}
			$s_ret.='<div class="radioEl"><input type="radio" id="'.htmlspecialchars($this->_id.'_'.$i).'" name="'.htmlspecialchars($this->_id).'" value="'.htmlspecialchars($key).'"';
			$selectedStr='';
			if($this->postVal($this->_id)!==false){
				if($this->postVal($this->_id)==$key){
					$selectedStr=' checked="checked"';
				}
			}else{
				if($this->_defaultVal==$key){
					$selectedStr=' checked="checked"';
				}
			}
			$s_ret.=$selectedStr.' /></div></div>'."\r\n";
			$i++;
		}
		$s_ret.='</div>';
		return $s_ret;
	}
}