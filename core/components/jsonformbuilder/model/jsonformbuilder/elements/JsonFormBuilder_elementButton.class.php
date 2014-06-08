<?php
/**
 * Creates a form button element (e.g button, image, reset, submit)
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementButton extends JsonFormBuilder_element{
	/**
	 * @ignore
	 */
	protected $_type;
    public function getType() { return $this->_type; }
    public function setType($value) { $this->_type = $value; }
	/**
	 * @ignore
	 */
	protected $_buttonLabel;
	/**
	 * @ignore
	 */
	protected $_src;

	/**
	 * JsonFormBuilder_elementButton
	 * 
	 * Creates a form button element
	 *
	 * @param string $id The ID of the button
	 * @param string $buttonLabel The label of the button
	 * @param string $type The button type, e.g button, image, reset, submit etc.
	 */
	function __construct($id, $buttonLabel, $type=null ) {
		parent::__construct($id,$buttonLabel);
		$this->_showLabel = false;
		$this->_showInEmail = false;
        /*
		if($type=='button' || $type=='image' || $type=='reset' || $type=='submit'){
			//ok -- valid type
		}else{
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Button "'.htmlspecialchars($type).'" must be of type "button", "reset", "image" or "submit"');
		}
        */
		$this->_type = $type;
	}
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		$s_ret='<input id="'.htmlspecialchars($this->_id).'" type="'.htmlspecialchars($this->_type).'" value="'.htmlspecialchars($this->_label).'"';
		if($this->_type=='image'){
			if($this->_src===NULL){
				JsonFormBuilder::throwError('[Element: '.$this->_id.'] Button of type "image" must have a src set.');
			}else{
				$s_ret.=' src="'.htmlspecialchars($this->_src).'"';
			}
		}
		$s_ret.=' '.$this->processExtraAttribsToStr().' />';
		return $s_ret;
	}
}