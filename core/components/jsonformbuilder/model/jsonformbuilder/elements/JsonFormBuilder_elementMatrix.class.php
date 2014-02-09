<?php
class JsonFormBuilder_elementMatrix extends JsonFormBuilder_element{
	private $a_rows;
	private $a_columns;
	private $s_type;
	
	function __construct($id, $label, $type, $rowLabels, $columnLabels ){
		parent::__construct($id,$label);
		$this->a_columns = self::forceArray($columnLabels);
		$this->a_rows  = self::forceArray($rowLabels);
		if($type!='select'&&$type!='text'&&$type!='radio'&&$type!='check'){
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Not a valid type, must be "select", "text", "radio" or "check".');
		}else{
			$this->s_type = $type;
		}
	}
	
	/**
	 * getType()
	 * 
	 * Returns the matrix type ("select", "text", "radio" or "check")
	 * @return string
	 */
	public function getType() { return $this->s_type; }
	/**
	 * getRows()
	 * 
	 * Returns the array of row labels
	 * @return array 
	 */
	public function getRows() { return $this->a_rows; }
	/**
	 * getColumns()
	 * 
	 * Returns the array of column labels
	 * @return array 
	 */
	public function getColumns() { return $this->a_columns; }
	
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
						$s_cellHTML='<input '.($this->postVal($this->_id.'_'.$r_cnt)!==NULL && $this->postVal($this->_id.'_'.$r_cnt)==$c_cnt?'checked="checked" ':'').'type="radio" id="'.htmlspecialchars($this->_id.'_'.$r_cnt.'_'.$c_cnt).'" name="'.htmlspecialchars($this->_id.'_'.$r_cnt).'" value="'.htmlspecialchars($c_cnt).'" />';
						break;
					case 'check':
						$s_cellHTML='<input '.($this->postVal($this->_id.'_'.$r_cnt)!==NULL && in_array($c_cnt,$this->postVal($this->_id.'_'.$r_cnt))===true?'checked="checked" ':'').'type="checkbox" id="'.htmlspecialchars($this->_id.'_'.$r_cnt.'_'.$c_cnt).'" name="'.htmlspecialchars($this->_id.'_'.$r_cnt.'[]').'" value="'.$c_cnt.'" />';
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
/**
 * Creates three combined form elements to allow users to enter a date using three dropdown lists.
 * @package JsonFormBuilder
 */
class JsonFormBuilder_elementDate extends JsonFormBuilder_element{
	private $_dateFormat;
	private $_yearStart;
	private $_yearEnd;
	private $_defaultVal0;
	private $_defaultVal1;
	private $_defaultVal2;
	
	/**
	 * JsonFormBuilder_elementDate
	 * 
	 * Creates three combined form elements to allow users to enter a date using three dropdown lists.
	 *
	 * @param type $id The id of the field (three modified ids that add _day, _month, _year to the end of this id will result.
	 * @param type $label The label of the data element.
	 * @param type $year_start The start year to show in the year dropdown (default=current year - 90).
	 * @param type $year_end  Then end year to show in dropdown (default=current year + 10).
	 * @param type $defaultValue Pass in a unix timestamp to set a default date.
	 */
	function __construct($id, $label, $format='dd/mm/yyyy', $year_start=NULL, $year_end=NULL, $defaultValue=NULL ){
		parent::__construct($id,$label);
		$this->setDateFormat($format);
		$this->setYearStart($year_start);
		$this->setYearEnd($year_end);

		$this->_defaultVal0 = date('jS',$defaultValue);
		$this->_defaultVal1 = date('F',$defaultValue);
		$this->_defaultVal2 = date('Y',$defaultValue);
	}
	/**
	 * setDateFormat($value)
	 * 
	 * Sets the order of the date select elements. Default dd/mm/yyyy but can be any combination.
	 * @param string $value 
	 */
	public function setDateFormat($value) {
		$a_dateBits=explode('/',trim($value));
		
		$day = array_search('dd', $a_dateBits);
		$month = array_search('mm', $a_dateBits);
		$year = array_search('yyyy', $a_dateBits);
		
		$ar1 = array($day,$month,$year);
		$ar2 = array('day','month','year');
		array_multisort($ar1, $ar2);
		
		if(strlen($value)!==10 || $day===false || $month===false || $year===false){
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Date format "'.$value.'" is not valid.');
		}else{
			$this->_dateFormat=$ar2;
		}
	}
	/**
	 * Sets the year start for the year portion of date elements.
	 * @param int $value The start year (default=current year - 90)
	 */
	public function setYearStart($value){
		if($value===NULL){
			$this->_yearStart=date('Y')-90;
		}else{
			$this->_yearStart=$value;
		}		
	}
	/**
	 * Sets the year end for the year portion of date elements.
	 * @param int $value The end year (default=current year + 10).
	 */
	public function setYearEnd($value){
		if($value===NULL){
			$this->_yearEnd=date('Y')+10;
		}else{
			$this->_yearEnd=$value;
		}	
	}
	/**
	 * Gets the year start.
	 * @return int
	 */
	public function getYearStart(){return $this->_yearStart;}
	/**
	 * Gets the year end.
	 * @return int 
	 */
	public function getYearEnd($value){return $this->_yearEnd;}
	
	/**
	 * getDateFormat()
	 * 
	 * Returns the date format used by a field with a date FormRule.
	 * @return string
	 */
	public function getDateFormat() { return $this->_dateFormat; }
	
	/**
	 * outputHTML()
	 * 
	 * Outputs the HTML for the element.
	 * @return string 
	 */
	public function outputHTML(){
		//day options
		$a_days=array(''=>'');
		for($a=1;$a<32;$a++){
			$ordinalSuffix = date('S',strtotime('2000-01-'.$a));
			$a_days[$a.$ordinalSuffix]=$a.$ordinalSuffix;
		}
		//month options
		$s_monthStr=',January,February,March,April,May,June,July,August,September,October,November,December';
		$a_temp=explode(',',$s_monthStr);
		$a_months=array(''=>'');
		foreach($a_temp as $opt){
			$a_months[$opt]=$opt;
		}
		//year options
		$a_years=array();
		if($this->_yearStart>$this->_yearEnd){
			JsonFormBuilder::throwError('[Element: '.$this->_id.'] Date start "'.$this->_yearStart.'" is greater than the end year "'.$this->_yearEnd.'".');
		}
		for($a=$this->_yearStart;$a<$this->_yearEnd+1;$a++){
			$a_years[' '.$a]=$a; //blank space here to be a number instead of an index
		}
		$a_years['']='';
		$a_years=array_reverse($a_years);
		$cnt=0;
		foreach($this->_dateFormat as $datePart){
			$default = NULL;
			if($datePart=='day'){ $selectArray=$a_days;	$default=$this->_defaultVal0; }
			if($datePart=='month'){ $selectArray=$a_months; $default=$this->_defaultVal1; }
			if($datePart=='year'){ $selectArray=$a_years; $default=$this->_defaultVal2; }
			$drop = new JsonFormBuilder_elementSelect($this->_id.'_'.$cnt, '', $selectArray, $default);
			$s_ret.='<span class="elementDate_'.$cnt.'">'.$drop->outputHTML().'</span>';
			$cnt++;
		}
		
		return $s_ret;
	}
}