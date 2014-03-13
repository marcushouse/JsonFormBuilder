<?php
class JsonFormBuilder_elementMatrix extends JsonFormBuilder_element{
	private $a_rows;
	private $a_columns;
	private $s_type;
	
	function __construct($id, $label, $type, $rowLabels, $columnLabels ){
		parent::__construct($id,$label);
        if($columnLabels){ $this->setColumns($columnLabels); }
        if($rowLabels){ $this->setRows($rowLabels); }
        if($type){ $this->setType($type); }
	}
	
	/**
	 * getType()
	 * 
	 * Returns the matrix type ("select", "text", "radio" or "check")
	 * @return string
	 */
	public function getType() { return $this->s_type; }
    public function setType($type) {
        if($type!='select'&&$type!='text'&&$type!='radio'&&$type!='check'){
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Not a valid type, must be "select", "text", "radio" or "check".');
		}else{
			$this->s_type = $type;
		}
    }
	/**
	 * getRows()
	 * 
	 * Returns the array of row labels
	 * @return array 
	 */
	public function getRows() { return $this->a_rows; }
    public function setRows($value) { $this->a_rows = self::forceArray($value); }
	/**
	 * getColumns()
	 * 
	 * Returns the array of column labels
	 * @return array 
	 */
	public function getColumns() { return $this->a_columns; }
    public function setColumns($value) { $this->a_columns = self::forceArray($value); }
	
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		$s_ret.='<input type="hidden" name="'.htmlspecialchars($this->_id).'_gridInfo" value="'.count($this->a_rows).','.count($this->a_columns).'" />';
		$s_ret.='<table class="matrix '.$this->s_type.'"><tr><th class="spaceCell">&nbsp;</th>';
		foreach($this->a_columns as $column){
			$s_ret.='<th class="columnHead">'.htmlspecialchars($column).'</th>';
		}
		$s_ret.='</tr>';
		$r_cnt=0;
		foreach($this->a_rows as $row){
			$s_ret.='<tr><th class="rowHead">'.htmlspecialchars($row).'</th>';
			$c_cnt=0;
			foreach($this->a_columns as $column){
				switch($this->s_type){
					case 'text':
						$el=new JsonFormBuilder_elementText($this->_id.'_'.$r_cnt.'_'.$c_cnt,'');
						$s_cellHTML=$el->outputHTML();
						break;
					case 'radio':
                        if($this->postVal($this->_id.'_'.$r_cnt)===false){
                            $s_postVal=-1;
                        }else{
                            $s_postVal = $this->postVal($this->_id.'_'.$r_cnt);
                        }
						$s_cellHTML='<input '.($s_postVal!=='' && $s_postVal==$c_cnt?'checked="checked" ':'').'type="radio" id="'.htmlspecialchars($this->_id.'_'.$r_cnt.'_'.$c_cnt).'" name="'.htmlspecialchars($this->_id.'_'.$r_cnt).'" value="'.htmlspecialchars($c_cnt).'" />';
						break;
					case 'check':
                        $s_postVal = $this->postVal($this->_id.'_'.$r_cnt);
                        
						$s_cellHTML='<input '.($s_postVal!=='' && in_array($c_cnt,$s_postVal)===true?'checked="checked" ':'').'type="checkbox" id="'.htmlspecialchars($this->_id.'_'.$r_cnt.'_'.$c_cnt).'" name="'.htmlspecialchars($this->_id.'_'.$r_cnt.'[]').'" value="'.$c_cnt.'" />';
						break;
				}
				$c_cnt++;
				$s_ret.='<td class="optionCell">'.$s_cellHTML.'</td>';
			}			
			$s_ret.='</tr>';
			$r_cnt++;
		}
		$s_ret.='</table>';
		return $s_ret;
	}
}