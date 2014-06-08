<?php
require_once dirname(__FILE__) . '/JsonFormBuilderCore.class.php';

class JsonFormBuilderFromJson extends JsonFormBuilderCore {
    
    private $modx;
    private $o_form;
    private $a_json;
    
    function __construct(&$modx) {
        if (is_a($modx, 'modX') === false) {
            JsonFormBuilder::throwError('Failed to create JsonFormBuilder form. Reference to $modx not valid.');
        }
        $this->modx = &$modx;
    }

    function setJsonData($json){
        
        //place all post values in chunk placeholders for use (sinple string types only)
        $postVars = filter_input_array(INPUT_POST);
        $a_processVars=array();
        if(!empty($postVars)){
            foreach($postVars as $key=>$val){
                $thisVal = (string)$val;
                $a_processVars['postVal.'.$key]=$thisVal;
            }
        }
        
        $uniqid = uniqid();
        $chunk = $this->modx->newObject('modChunk', array('name' => "{tmp}-{$uniqid}"));
        $chunk->setCacheable(false);        
        $output = $chunk->process($a_processVars, $json);
        
        $a_json = json_decode($output,true);
        if(!empty($a_json)){
            $this->a_json = $a_json;
        }else{
            JsonFormBuilder::throwError('Failed to decode JSON "'.$json.'".');
        }
    }
    
    function setJsonDataFromChunk($chunkName){
        $chunk = $this->modx->getObject('modChunk',array('name' => $chunkName));
        if(empty($chunk)){
            JsonFormBuilder::throwError('Failed to find chunk "'.$chunkName.'".');
        }
        $this->setJsonData($chunk->getContent());
    }
    
    function setJsonDataFromFile($filePath){
        $s_jsonContent = file_get_contents(MODX_BASE_PATH.$filePath);
        $this->setJsonData($s_jsonContent);
    }
    
    function getJsonVal($arr,$key,$required=false){
        if(isset($arr[$key]) && $arr[$key]!=''){
            return $arr[$key];
        }else{
            if($required){
                JsonFormBuilder::throwError('Failed to find required JSON property "'.$key.'".');
            }
        }
        return false;
    }
    function output(){
        $a_formElsToAdd=array();
        $a_formRulesToAdd=array();
        $this->o_form = new JsonFormBuilder($this->modx,$this->getJsonVal($this->a_json,'id',true));
        
        $a_ignore = array('id','elements');
        foreach($this->a_json as $key=>$val){
            if(in_array($key,$a_ignore)){
                continue;
            }
            $methodName = 'set'.ucfirst($key);
            $this->o_form->$methodName($val);
        }
        
        $a_elements = $this->getJsonVal($this->a_json,'elements',true);
        foreach($a_elements as $element){
            if(is_array($element)===false && empty($element)===false){
                $a_formElsToAdd[] = $element;
            }else{
                $elementMethod = 'JsonFormBuilder_element'.ucfirst($this->getJsonVal($element,'element',true));
                //required to set id and label in constructors.. all elements have id and label
                $o_el =  new $elementMethod($element['id'],$element['label']);
                $a_ignore = array('element','rules','id','label');
                foreach($element as $key=>$val){
                    if(in_array($key,$a_ignore)){
                        continue;
                    }
                    $methodName = 'set'.ucfirst($key);
                    $o_el->$methodName($val);
                }
                $a_formElsToAdd[] = $o_el;
                //add rules if needed
                $a_rules = $this->getJsonVal($element,'rules');
                if($a_rules){
                    foreach($a_rules as $rule){
                        if(is_array($rule)){
                            //rule in assoc array
                            $r = new FormRule($rule['type'],$o_el,$rule['value']);
                            $a_ruleignore = array('type');
                            foreach($rule as $key=>$val){
                                if(in_array($key,$a_ruleignore)){
                                    continue;
                                }
                                $methodName = 'set'.ucfirst($key);
                                $r->$methodName($val);
                            }
                        }else{
                            //simple rule
                            $r = new FormRule($rule,$o_el);
                        }
                        
                        $r->refresh(); // just in case
                        $a_formRulesToAdd[]=$r;
                    }
                }
                
            }
        }
        $this->o_form->addRules($a_formRulesToAdd);
        $this->o_form->addElements($a_formElsToAdd);
        return $this->o_form->output();
    }

}
