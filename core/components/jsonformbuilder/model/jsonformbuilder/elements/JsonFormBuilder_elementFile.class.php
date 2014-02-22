<?php
/**
 * JsonFormBuilder_elementFile
 * 
 * Creates a file field form element.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementFile extends JsonFormBuilder_elementText{
    private $_allowedExtentions;
    private $_maxFilesizeBytes;
	/**
	 * Creates a file field element allowing upload of file to the server (and attached to email)
	 * @param string $id The ID of the file element
	 * @param string $label The label of the file element
	 */
	function __construct( $id, $label, $max_filesize_bytes=2097152,$allowedExtensions=NULL ) {
		parent::__construct($id,$label);
		$this->_fieldType='file';
		$this->_showInEmail=true;
        $this->setAllowedExtensions($allowedExtensions);
        $this->setMaxFilesize($max_filesize_bytes);
	}
    public function isAllowedFilename($value){
        if($this->_allowedExtentions!==NULL){
            $s_filename = trim(strtolower($value));
            $a_split = explode('.',$s_filename);
            $s_ext = $a_split[count($a_split)-1];
            if(in_array($s_ext,$this->_allowedExtentions)!==true){
               return false;
            }
        }
        return true;
    }
    public function isAllowedSize($value){
        if($this->_maxFilesizeBytes!==NULL){
            if($value>$this->_maxFilesizeBytes){
               return false;
            }
        }
        return true;
    }
    public function setAllowedExtensions($value) {
        if($value===NULL){
            //any file type will be allowed
        }else{
            $this->_allowedExtentions = JsonFormBuilder::forceArray($value);
            for($i=0;$i<count($this->_allowedExtentions);$i++){
                $this->_allowedExtentions[$i] = trim(strtolower($this->_allowedExtentions[$i]));
            }
        }
	}
    public function setMaxFilesize($value) {
        $value = (int)round($value);
		$this->_maxFilesizeBytes = JsonFormBuilder::forceNumber($value);
	}
    
    public function getAllowedExtensions() { return $this->_allowedExtentions; }
    public function getMaxFilesize() { return $this->_maxFilesizeBytes; }
    public function outputHTML(){
        //Add a remove button after the field. Not shown until jQuery kicks in.
        return parent::outputHTML().' <button style="display:none;" id="'.$this->getId().'_remove" type="button">X</button>';
    }
}