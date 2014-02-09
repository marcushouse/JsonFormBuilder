<?php
/**
 * Creates a select (dropdown) field element.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementSelect extends JsonFormBuilder_element{
	/**
	 * @ignore 
	 */
	private $_values;
	/**
	 * @ignore 
	 */
	private $_defaultVal;
	
	/**
	 * JsonFormBuilder_elementSelect
	 * 
	 * Creates a select dropdown element.
	 *
	 * <code>
	 * $a_usstates = array(
	 *	''=>'Please select...',
	 *	'AL'=>'Alabama',
	 *	'AK'=>'Alaska',
	 *	'AZ'=>'Arizona',
	 *	'AR'=>'Arkansas',
	 *	'CA'=>'California',
	 *	'CO'=>'Colorado',
	 *	'CT'=>'Connecticut'
	 * );
	 * $o_fe_usstates = new JsonFormBuilder_elementSelect('ussuate','Select a state',$a_usstates,'AR');
	 * </code>
	 * 
	 * @param string $id The ID of the element
	 * @param string $label The label of the select element
	 * @param array $values An array of title/value arrays in order of display
	 * @param string $defaultValue The default value to select in the dropdown field 
	 */
	function __construct($id, $label, array $values, $defaultValue=null) {
		parent::__construct($id,$label);
		$this->_values = $values;
		$this->_defaultVal = $defaultValue;
	}
	
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		if($this->postVal($this->_id)!==NULL){
			$selectedVal=$this->postVal($this->_id);
		}else{
			$selectedVal=$this->_defaultVal;
		}
		$s_ret='<select id="'.htmlspecialchars($this->_id).'" name="'.htmlspecialchars($this->_id).'">'."\r\n";
		foreach($this->_values as $key=>$value){
			$selectedStr='';
			if($this->postVal($this->_id)!==NULL){
				if($this->postVal($this->_id)==$key){
					$selectedStr=' selected="selected"';
				}
			}else{
				if($this->_defaultVal==$key){
					$selectedStr=' selected="selected"';
				}
			}
			//trims here so we can force space
			$s_ret.='<option value="'.htmlspecialchars(trim($key)).'"'.$selectedStr.'>'.htmlspecialchars($value).'</option>'."\r\n";
		}
		$s_ret.='</select>';
		return $s_ret;
	}
}