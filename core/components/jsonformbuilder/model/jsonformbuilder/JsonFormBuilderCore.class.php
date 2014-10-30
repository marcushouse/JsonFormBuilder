<?php

/**
 * Contains methods that can be used from any part of JsonFormBuilder.
 * @package JsonFormBuilder
 */
/**
 * Required Files
 */
require_once dirname(__FILE__) . '/elements/JsonFormBuilder_baseElement.class.php';
require_once dirname(__FILE__) . '/JsonFormBuilder.class.php';
require_once dirname(__FILE__) . '/FormRule.class.php';

/**
 * The core class for JsonFormBuilder every other class extends this class which allows easy access to the common static methods.
 * @package JsonFormBuilder
 */
class JsonFormBuilderCore {
    /**
     * Setter function. This should always be overwritten, if not throw an error (forces extending classes to define setters).
     * @param string $name Name of the property
     * @param string $value Value of the property
     * @ignore
     */
    public function __set($name, $value) {
        self::throwError('Attempt to set a non-existing property: ' . $name . ' with value ' . $value);
    }

    /**
     * Getter function. This should always be overwritten, if not throw an error (forces extending classes to define getters)
     * @param string $name Name of the property
     * @ignore
     */
    public function __get($name) {
        self::throwError('Attempt to get a non-existing property: ' . $name);
    }

    /**
     * Testing function that verifies that the value is a boolean or throw an error.
     * @param boolean $value Value to test if boolean
     * @return boolean
     * @ignore
     */
    public static function forceBool($value) {
        if (is_bool($value) === true) {
            return $value;
        } else {
            self::throwError('Value "' . $value . '" must be type (bool) - true/false');
        }
    }

    /**
     * Testing function that verifies that the value is an integer or throw an error.
     * @param integer $value Value to test if integer
     * @return integer
     * @ignore
     */
    public static function forceNumber($value) {
        if (is_int($value) === true) {
            return $value;
        } else {
            self::throwError('Value "' . $value . '" must be an type (int) - (Pass only integer values not string numbers)');
        }
    }

    /**
     * Testing function that verifies that the value is an array or throw an error.
     * @param array $value Value to test if array
     * @return array
     * @ignore
     */
    public static function forceArray($value) {
        if (is_array($value) === true) {
            return $value;
        } else {
            self::throwError('Value "' . $value . '" must be type (array)');
        }
    }
    
    public static function forceEmail($value,$addedError=''){
        if(filter_var($value, FILTER_VALIDATE_EMAIL ) !== false) {
            return $value;
        } else {
            self::throwError('Value "' . $value . '" must be an email address.'.$addedError);
        }
        
    }

    /**
     * Throws an error (probably should be logged to modx instead of an Exception, but makes for easier debugging).
     * @param string $errorString String to output in the error
     * @ignore
     */
    public static function throwError($errorString) {
        throw new Exception($errorString . "\r\n");
    }

    /**
     * Verify that the specified object is a valid form element object (if not an error is thrown).
     * @param JsonFormBuilder_baseElement $element Element object
     * @ignore
     */
    public static function verifyFormElement(JsonFormBuilder_baseElement $element,$addedError=NULL) {
        if (is_a($element, 'JsonFormBuilder_baseElement') === false) {
            self::throwError('Element "' . $element . '" is not a valid JsonFormBuilder element. '.$addedError);
        }
    }

    public function postVal($field) {

        $a_postArray = $_POST;
        
        //safe get value and allow the linebreak (for nl2br)
        if(isset($a_postArray[$field])===false){
            return false;
        }else{
            if(is_array($a_postArray[$field])===true){
                return $a_postArray[$field];
            }else{
                return trim($a_postArray[$field]);
            }
        }
    }

    public static function is_matrix_required_valid($o_el) {
        $elID = $o_el->getId();
        $type = $o_el->getType();
        $returnStatus = true;
        switch ($type) {
            case 'text':
                //make sure no posted values inside grid are filled out
                $invalCount = 0;
                $gridInfo = explode(',', self::postVal($elID . '_gridInfo'));
                $rows = $gridInfo[0];
                $columns = $gridInfo[1];
                for ($a = 0; $a < $rows; $a++) {
                    for ($b = 0; $b < $columns; $b++) {
                        if (self::postVal($elID . '_' . $a . '_' . $b) !== NULL) {
                            if (trim(self::postVal($elID . '_' . $a . '_' . $b)) == '') {
                                $invalCount++;
                            }
                        } else {
                            $invalCount++;
                        }
                    }
                }
                if ($invalCount > 0) {
                    $returnStatus = false;
                }
                break;
            case 'radio':
                //make sure no posted values inside grid are filled out
                $invalCount = 0;
                $gridInfo = explode(',', self::postVal($elID . '_gridInfo'));
                $rows = $gridInfo[0];
                $columns = $gridInfo[1];
                for ($a = 0; $a < $rows; $a++) {
                    if (self::postVal($elID . '_' . $a) !== NULL) {
                        if (self::postVal($elID . '_' . $a) == '') {
                            $invalCount++;
                        }
                    } else {
                        $invalCount++;
                    }
                }
                if ($invalCount > 0) {
                    $returnStatus = false;
                }
                break;
            case 'check':
                //make sure no posted values inside grid are filled out
                $invalCount = 0;
                $gridInfo = explode(',', self::postVal($elID . '_gridInfo'));
                $rows = $gridInfo[0];
                $columns = $gridInfo[1];
                for ($a = 0; $a < $rows; $a++) {
                    $itemsInRowTicked = 0;
                    for ($b = 0; $b < $columns; $b++) {
                        if (self::postVal($elID . '_' . $a) !== '' && count(self::postVal($elID . '_' . $a)) > 0) {
                            $itemsInRowTicked++;
                        } else {
                            $invalCount++;
                        }
                    }
                    if ($itemsInRowTicked == 0) {
                        $invalCount++;
                    }
                }
                if ($invalCount > 0) {
                    $returnStatus = false;
                }
                break;
        }
        return $returnStatus;
    }

    /**
     * Test if a string is a valid date against the specified format and return useful information about the date (such as a unix timestamp and processed version of the same date).
     * @param string $value String to check for valid date format
     * @param string $format Format can be a combination of dd mm yyyy in any order with a single character separator (default mm/dd/yyyy), or also just the year (yyyy)
     * @return array Returns an array of elements containing return status (Boolean status), processed date (String value) and timestamp (number) 
     * @ignore
     */
    public static function is_valid_date($value, $format = 'mm/dd/yyyy') {
        $b_retStatus = false;
        $s_retValue = '';
        $n_retTimestamp = 0;
        if (strlen($value) == strlen($format)) {
            if ($format == "yyyy") {
                //allow just yyyy
                if (@checkdate(1, 1, $value)) {
                    $b_retStatus = true;
                    $s_retValue = $value;
                    $n_retTimestamp = strtotime($year . '-01-01');
                }
            } else {
                // find separator. Remove all other characters from $format 
                $separator_only = str_replace(array('m', 'd', 'y'), '', $format);
                $separator = $separator_only[0]; // separator is first character 
                if ($separator && strlen($separator_only) == 2) {

                    $newStr = $format;

                    $dayPos = strpos($format, 'dd');
                    $day = substr($value, $dayPos, 2);
                    $newStr = str_replace('dd', $day, $newStr);

                    $monthPos = strpos($format, 'mm');
                    $month = substr($value, $monthPos, 2);
                    $newStr = str_replace('mm', $month, $newStr);

                    $yearPos = strpos($format, 'yyyy');
                    $year = substr($value, $yearPos, 4);
                    $newStr = str_replace('yyyy', $year, $newStr);

                    if (@checkdate($month, $day, $year)) {
                        $b_retStatus = true;
                        $s_retValue = $newStr;
                        $n_retTimestamp = strtotime($year . '-' . $month . '-' . $year);
                    }
                }
            }
        }
        return array('status' => $b_retStatus, 'value' => $s_retValue, 'timestamp' => $n_retTimestamp);
    }

}