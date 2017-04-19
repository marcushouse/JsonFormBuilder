<?php

/**
 * Contains the classes used for the main JsonFormBuilder object.
 * @package JsonFormBuilder
 */
/**
 * Required Files
 */
require_once dirname(__FILE__) . '/JsonFormBuilderCore.class.php';

/**
 * The main JsonFormBuilder methods. Most of the program bulk lives within this class and handles a great number of set/get methods and output methods.
 * @package JsonFormBuilder
 */
class JsonFormBuilder extends JsonFormBuilderCore {

    protected $b_validated=false;
    protected $_attachmentIncluded=false;
    /**
     * @ignore
     */
    protected $modx;

    /**
     * @ignore
     */
    protected $_method;

    /**
     * @ignore
     */
    protected $_id;

    /**
     * @ignore
     */
    protected $_redirectDocument;

    /**
     * @ignore
     */
    protected $_redirectParams;

    /**
     * @ignore
     */
    protected $_jqueryValidation;

    /**
     * @ignore
     */
    protected $_formElements;

    /**
     * @ignore
     */
    protected $_emailFromAddress;

    /**
     * @ignore
     */
    protected $_emailSubject;

    /**
     * @ignore
     */
    protected $_emailToAddress;

    /**
     * @ignore
     */
    protected $_emailFontSize;

    /**
     * @ignore
     */
    protected $_emailFontFamily;

    /**
     * @ignore
     */
    protected $_emailHeadHtml;

    /**
     * @ignore
     */
    protected $_emailFootHtml;

    /**
     * @ignore
     */
    protected $_rules;

    /**
     * @ignore
     */
    protected $_validate;

    /**
     * @ignore
     */
    protected $_customValidators;

    /**
     * @ignore
     */
    protected $_store;

    /**
     * @ignore
     */
    protected $_placeholderJavascript;

    /**
     * @ignore
     */
    protected $_emailFromName;

    /**
     * @ignore
     */
    protected $_emailToName;

    /**
     * @ignore
     */
    protected $_emailReplyToAddress;

    /**
     * @ignore
     */
    protected $_emailReplyToName;

    /**
     * @ignore
     */
    protected $_emailCCAddress;

    /**
     * @ignore
     */
    protected $_emailBCCAddress;

    /**
     * @ignore
     */
    protected $_autoResponderSubject;

    /**
     * @ignore
     */
    protected $_autoResponderToAddress;

    /**
     * @ignore
     */
    protected $_autoResponderFromAddress;

    /**
     * @ignore
     */
    protected $_autoResponderFromName;

    /**
     * @ignore
     */
    protected $_autoResponderReplyTo;

    /**
     * @ignore
     */
    protected $_autoResponderReplyToName;

    /**
     * @ignore
     */
    protected $_autoResponderCC;

    /**
     * @ignore
     */
    protected $_autoResponderBCC;

    /**
     * @ignore
     */
    protected $_autoResponderEmailContent;

     /**
     * @ignore
     */
    protected $_formAction;

    /**
     * @ignore
     */
    protected $_submitVar;

    protected $_isSubmitted;
    protected function setIsSubmitted($value) {
        $this->_isSubmitted = self::forceBool($value);
    }
    public function getIsSubmitted() {
        return $this->_isSubmitted;
    }
    public function isSubmitted() {
        return $this->_isSubmitted;
    }

    protected $_invalidElements=array();
    public function getInvalidElements() {
        return $this->_invalidElements;
    }
    /**
    * @ignore
    */
    protected $_spamProtection;
    public function setSpamProtection($value) {
        $this->_spamProtection = $value;
    }
    public function getSpamProtection() {
        return $this->_spamProtection;
    }

    protected $_fieldProps_jqValidate=array();
    protected $_fieldProps_jqValidateGroups=array();
    protected $_fieldProps_errstringJq=array();
    protected $_footJavascript=array();

    /**
    * @ignore
    */
    protected $_submitHandler;

    /**
     * JsonFormBuilder
     *
     * The main construction for JsonFormBuilder. All elements and rules are attached to this object.
     * @param modx &$modx Reference to the core modX object
     * @param string $id Id of the form
     */
    function __construct(&$modx, $id) {
        if(is_a($modx,'modX')===false){
            JsonFormBuilder::throwError('Failed to create JsonFormBuilder form. Reference to $modx not valid.');
        }
        $this->modx = &$modx;

        $this->_method = 'post';
        $this->_id = $id;
        $this->_store = true;
        $this->_formElements = array();
        $this->_rules = array();
        $this->setSpamProtection(true);
        //should have the option to not redirect at all, removing default of current page.
        //$this->_redirectDocument = $this->modx->resource->get('id');
        $this->_redirectParams = NULL;
        $this->_submitVar = NULL;
        $this->_jqueryValidation = false;
        $this->_autoResponderEmailContent = '<p>Thank you for your submission. A copy of the information you sent is shown below.</p>{{tableContent}}';
        $this->_emailFontSize = '13px';
        $this->_emailFontFamily = 'Helvetica,Arial,sans-serif';
        $this->_emailFootHtml = '<p>You can use this link to reply: <a href="mailto:{{replyToEmailAddress}}?subject=RE: {{subject}}">{{replyToEmailAddress}}</a></p>';
    }

    /**
     * addRule(FormRule $formRule)
     *
     * Adds a single rule to the JsonFormBuilder object.
     * @param FormRule $formRule
     */
    public function addRule(FormRule $formRule) {
        if (is_a($formRule, 'FormRule') === false) {
            JsonFormBuilder::throwError('Form rule "' . $formRule . '" is not a valid FormRule type. Recommend using FormRuleType constants to define rule type.');
        } else {
            $this->_rules[] = $formRule;
        }
    }

    /**
     * addRules($rules)
     *
     * Adds multiple FormRule objects to the JsonFormBuilder object.
     * @param array $rules
     */
    public function addRules($rules) {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * getMethod()
     *
     * Returns the form method (get, post etc)
     * @return string
     */
    public function getMethod() {
        return $this->_method;
    }

    /**
     * getId()
     *
     * Returns the form ID.
     * @return string
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * getRedirectDocument()
     *
     * Returns the forms redirectDocument setting.
     * @return string
     */
    public function getRedirectDocument() {
        return $this->_redirectDocument;
    }

    /**
     * getRedirectParams()
     *
     * Returns the forms redirectDocument parameters.
     * @return string
     */
    public function getRedirectParams() {
        return $this->_redirectParams;
    }

    /**
     * getSubmitVar()
     *
     * Returns any custom set submitVar. Will return NULL by default as normally this is an automated post variable.
     * @return string
     */
    public function getSubmitVar() {
        return $this->_submitVar;
    }

    /**
     * getJqueryValidation()
     *
     * Returns the forms jQuery validate setting.
     * @return boolean
     */
    public function getJqueryValidation() {
        return $this->_jqueryValidation;
    }

    /**
     * getEmailFromAddress()
     *
     * Returns the FROM email address used when sending email.
     * @return string
     */
    public function getEmailFromAddress() {
        return $this->_emailFromAddress;
    }

    /**
     * getEmailToAddress()
     *
     * Returns the TO email address used when sending email.
     * @return string
     */
    public function getEmailToAddress() {
        return $this->_emailToAddress;
    }

    /**
     * getEmailSubject()
     *
     * Returns the email subject used when sending email.
     * @return string
     */
    public function getEmailSubject() {
        return $this->_emailSubject;
    }

    /**
     * getEmailHeadHtml()
     *
     * Returns the header HTML used in the email.
     * @return string
     */
    public function getEmailHeadHtml() {
        return $this->_emailHeadHtml;
    }

    /**
     * getEmailFootHtml()
     *
     * Returns the footer HTML used in the email.
     * @return string
     */
    public function getEmailFootHtml() {
        return $this->_emailFootHtml;
    }

    /**
     * getValidate()
     *
     * Returns the custom validation methods used (doesn't include the validation rules automatically set by rules etc).
     * @return string
     */
    public function getValidate() {
        return $this->_validate;
    }

    /**
     * getCustomValidators()
     *
     * Returns any customValidator settings used by the form. (Used with the FormIT "validate" command to create custom validation. See customValidators in FormIt documentation for more information).
     * @return string
     */
    public function getCustomValidators() {
        return $this->_customValidators;
    }

    /**
     * getEmailFromName()
     *
     * Returns the FROM email name used when sending email.
     * @return string
     */
    public function getEmailFromName() {
        return $this->_emailFromName;
    }

    /**
     * getEmailToName()
     *
     * Returns the TO email name used when sending email.
     * @return string
     */
    public function getEmailToName() {
        return $this->_emailToName;
    }

    /**
     * getEmailReplyToAddress()
     *
     * Returns the REPLY-TO email address used when sending email.
     * @return string
     */
    public function getEmailReplyToAddress() {
        return $this->_emailReplyToAddress;
    }

    /**
     * getEmailReplyToName()
     *
     * Returns the REPLY-TO email name used when sending email.
     * @return string
     */
    public function getEmailReplyToName() {
        return $this->_emailReplyToName;
    }

    /**
     * getEmailCCAddress()
     *
     * Returns the CC email address used when sending email.
     * @return string
     */
    public function getEmailCCAddress() {
        return $this->_emailCCAddress;
    }

    /**
     * getEmailBCCAddress()
     *
     * Returns the BCC email address used when sending email.
     * @return string
     */
    public function getEmailBCCAddress() {
        return $this->_emailBCCAddress;
    }

    public function getFormAction() {
        return $this->_formAction;
    }

    /**
     * getStore()
     *
     * Returns the store option used. See the FormIt "store" option.
     * @return boolean
     */
    public function getStore() {
        return $this->_store;
    }

    /**
     * getPlaceholderJavascript()
     *
     * Returns javascript placeholder setting used.
     * @return string
     */
    public function getPlaceholderJavascript() {
        return $this->_placeholderJavascript;
    }

    /*
     * getAutoResponderSubject()
     *
     * Auto Responder - Returns the Auto Responder subject used in the email.
     * @return string
     */

    public function getAutoResponderSubject() {
        return $this->_autoResponderSubject;
    }

    /**
     * getAutoResponderToAddress()
     *
     * Auto Responder - Returns the Auto Responder TO email address used in the email.
     * @return string
     */
    public function getAutoResponderToAddress() {
        return $this->_autoResponderToAddress;
    }

    /**
     * getAutoResponderFromAddress()
     *
     * Auto Responder - Returns the Auto Responder FROM email address used in the email.
     * @return string
     */
    public function getAutoResponderFromAddress() {
        return $this->_autoResponderFromAddress;
    }

    /**
     * getAutoResponderFromName()
     *
     * Auto Responder - Returns the Auto Responder FROM email name used in the email.
     * @return string
     */
    public function getAutoResponderFromName() {
        return $this->_autoResponderFromName;
    }

    /**
     * getAutoResponderReplyTo()
     *
     * Auto Responder - Returns the Auto Responder REPLY-TO email address used in the email.
     * @return string
     */
    public function getAutoResponderReplyTo() {
        return $this->_autoResponderReplyTo;
    }

    /**
     * getAutoResponderCC()
     *
     * Auto Responder - Returns the Auto Responder CC email address used in the email.
     * @return string
     */
    public function getAutoResponderCC() {
        return $this->_autoResponderCC;
    }

    /**
     * getAutoResponderBCC()
     *
     * Auto Responder - Returns the Auto Responder BCC email address used in the email.
     * @return string
     */
    public function getAutoResponderBCC() {
        return $this->_autoResponderBCC;
    }

    /**
     * getAutoResponderEmailContent()
     *
     * Auto Responder - Returns the Auto Responder email content.
     * @return string
     */
    public function getAutoResponderEmailContent() {
        return $this->_autoResponderEmailContent;
    }

    /**
     * getSubmitHandler()
     *
     * jQuery Validate - Returns the submit handler.
     * @return string
     */
    public function getSubmitHandler(){
        return $this->_submitHandler;
    }
    
    /**
     * setMethod($value)
     *
     * Sets the form method (get, post etc)
     * @param string $value
     */
    public function setMethod($value) {
        $this->_method = $value;
    }

    public function setFormAction($value) {
        $this->_formAction = $value;
    }

    /**
     * setRedirectDocument($value)
     *
     * Sets the forms redirectDocument setting.
     * @param string $value The resource ID of the page to redirect to post success.
     */
    public function setRedirectDocument($value) {
        $this->_redirectDocument = $value;
    }

    /**
     * setSubmitVar($value)
     *
     * Sets the submission variable. This
     * @param string $value A JSON object of parameters to pass in the redirect URL. e.g. {"user":"123","success":"1"}
     */
    public function setSubmitVar($value) {
        $this->_submitVar = $value;
    }

    /**
     * setRedirectParams($value)
     *
     * If set, will not begin form processing if this POST variable is not passed. Useful to disable automatic processing. Normally this process is handled automatically.
     * @param string $value Required post variable before form processing will occur.
     */
    public function setRedirectParams($value) {
        $this->_redirectParams = $value;
    }

    /**
     * setJqueryValidation($value)
     *
     * Sets the forms jQuery validate setting. When set to true extra javascript is output for "jQuery Validate" to use. If jQuery Validate is installed correctly, forms should validate with inline javascript (jQuery) as well as with FormIt validation with PHP.
     * @param boolean $value
     */
    public function setJqueryValidation($value) {
        $this->_jqueryValidation = self::forceBool($value);
    }

    /**
     * setEmailFromAddress($value)
     *
     * Sets the FROM email address used when sending email.
     * @param string $value
     */
    public function setEmailFromAddress($value) {
        $this->_emailFromAddress = $value;
    }

    /**
     * setEmailToAddress($value)
     *
     * Sets the TO email address used when sending email.
     * @param string $value
     */
    public function setEmailToAddress($value) {
        $this->_emailToAddress = $value;
    }

    /**
     * setEmailFromName($value)
     *
     * Sets the FROM email address used when sending email.
     * @param string $value
     */
    public function setEmailFromName($value) {
        $this->_emailFromName = $value;
    }

    /**
     * setEmailToName($value)
     *
     * Sets the TO email name used when sending email.
     * @param string $value
     */
    public function setEmailToName($value) {
        $this->_emailToName = $value;
    }

    /**
     * setEmailReplyToAddress($value)
     *
     * Sets the REPLY-TO email address used when sending email.
     * @param string $value
     */
    public function setEmailReplyToAddress($value) {
        $this->_emailReplyToAddress = $value;
    }

    /**
     * setEmailReplyToName($value)
     *
     * Sets the REPLY-TO email name used when sending email.
     * @param string $value
     */
    public function setEmailReplyToName($value) {
        $this->_emailReplyToName = $value;
    }

    /**
     * setEmailCCAddress($value)
     *
     * Sets the CC email address used when sending email.
     * @param string $value
     */
    public function setEmailCCAddress($value) {
        $this->_emailCCAddress = $value;
    }

    /**
     * setEmailBCCAddress($value)
     *
     * Sets the BCC email address used when sending email.
     * @param string $value
     */
    public function setEmailBCCAddress($value) {
        $this->_emailBCCAddress = $value;
    }

    /**
     * setEmailSubject($value)
     *
     * Sets the email subject used when sending email.
     * @param string $value
     */
    public function setEmailSubject($value) {
        $this->_emailSubject = $value;
    }

    /**
     * setEmailHeadHtml($value)
     *
     * Sets the header HTML used in the email.
     * @param string $value
     */
    public function setEmailHeadHtml($value) {
        $this->_emailHeadHtml = $value;
    }

    /**
     * setEmailFootHtml($value)
     *
     * Sets the footer HTML used in the email.
     * @param string $value
     */
    public function setEmailFootHtml($value) {
        $this->_emailFootHtml = $value;
    }

    /**
     * setValidate($value)
     *
     * Sets the custom validation methods used (doesn't include the validation rules automatically set by rules etc).
     * @param string $value
     */
    public function setValidate($value) {
        $this->_validate = $value;
    }

    /**
     * setCustomValidators($value)
     *
     * Sets the customValidator settings used by the form. (Used with the FormIT "validate" command to create custom validation. See customValidators in FormIt documentation for more information).
     * @param string $value
     */
    public function setCustomValidators($value) {
        $this->_customValidators = $value;
    }

    /**
     * setStore($value)
     *
     * Sets the store option used by FormIt. If true, will store the data in the cache for retrieval using the FormItRetriever snippet.
     * @param boolean $value
     */
    public function setStore($value) {
        $this->_store = self::forceBool($value);
    }

    /**
     * setPlaceholderJavascript($value)
     *
     * Sets a placeholder to use to inject any javascript used to control the form (jQuery Validate etc). By default any javascript required for forms is output inline after the closeure of the form (as you can see when viewing the HTML source). This may not be desirable for some developers as javascript order may be an issue. For simplicity it has been set this way by default for the majority of users that want to get their form up and running with minimal fuss.
     *
     * To do this add the following code to your form object.
     * <code>
     * $o_form->setPlaceholderJavascript('FormItBuilder_javascript_myForm');
     * </code>
     * The string used in this method will be your placeholder name. Simply add the placeholder code to your template like so
     * <code>
     * [[+FormItBuilder_javascript_myForm]]
     * </code>
     * @param string $value
     */
    public function setPlaceholderJavascript($value) {
        $this->_placeholderJavascript = $value;
    }

    /**
     * setAutoResponderSubject($value)
     *
     * Auto Responder - The subject of the email.
     * @param string $value
     */
    public function setAutoResponderSubject($value) {
        $this->_autoResponderSubject = $value;
    }

    /**
     * setAutoResponderToAddress($value)
     *
     * Auto Responder - The to address to use to send the email.
     * @param string $value
     */
    public function setAutoResponderToAddress($value) {
        $this->_autoResponderToAddress = $value;
    }

    /**
     * setAutoResponderFromAddress($value)
     *
     * Auto Responder - Optional. If set, will specify the From: address for the email. Defaults to the `emailsender` system setting.
     * @param string $value
     */
    public function setAutoResponderFromAddress($value) {
        $this->_autoResponderFromAddress = $value;
    }

    /**
     * setAutoResponderFromName($value)
     *
     * Auto Responder - Optional. If set, will specify the From: name for the email.
     * @param string $value
     */
    public function setAutoResponderFromName($value) {
        $this->_autoResponderFromName = $value;
    }

    /**
     * setAutoResponderReplyTo($value)
     *
     * Auto Responder - An email to set as the reply-to.
     * @param string $value
     */
    public function setAutoResponderReplyTo($value) {
        $this->_autoResponderReplyTo = $value;
    }

    /**
     * setAutoResponderReplyToName($value)
     *
     * Auto Responder - Optional. The name for the Reply-To field.
     * @param string $value
     */
    public function setAutoResponderReplyToName($value) {
        $this->_autoResponderReplyToName = $value;
    }

    /**
     * setAutoResponderCC($value)
     *
     * Auto Responder - Optional. A comma-separated list of emails to send via cc.
     * @param string $value
     */
    public function setAutoResponderCC($value) {
        $this->_autoResponderCC = $value;
    }

    /**
     * setAutoResponderBCC($value)
     *
     * Auto Responder - Optional. A comma-separated list of emails to send via bcc.
     * @param string $value
     */
    public function setAutoResponderBCC($value) {
        $this->_autoResponderBCC = $value;
    }

    /**
     * setAutoResponderEmailContent($value)
     *
     * Auto Responder - Sets the email content.
     * @param string $value
     */
    public function setAutoResponderEmailContent($value) {
        $this->_autoResponderEmailContent = $value;
    }

    /**
     * setSubmitHandler($value)
     *
     * jQuery Validate - Sets the submit handler.
     * @param string $value
     */
    public function setSubmitHandler($value){
        $this->_submitHandler = $value;
    }
    
    /**
     * addElement(JsonFormBuilder_baseElement $o_formElement)
     *
     * Adds a single element object to the main JsonFormBuilder object.
     * @param $o_formElement
     */
    public function addElement($o_formElement) {
        if(empty($o_formElement)){
            $msg = 'Tried to add empty or NULL element to form "'.$this->getId().'". Number of elements already added="'.count($this->_formElements).'"';
            if(count($this->_formElements)>0){
                if($this->_formElements[count($this->_formElements)-1]){
                    if(method_exists($this->_formElements[count($this->_formElements)-1],'getId')===true){
                        $msg .= ' - last element id = "'.$this->_formElements[count($this->_formElements)-1]->getId().'"';
                    }
                }
                JsonFormBuilder::throwError($msg);
            }
        }
        $this->_formElements[] = $o_formElement;
    }

    /**
     * addElements($a_elements)
     *
     * Adds multiple element objects to the main JsonFormBuilder object.
     * @param array $a_elements An array of objects that extend JsonFormBuilder_baseElement.
     */
    public function addElements($a_elements) {
        foreach ($a_elements as $o_formElement) {
            $this->addElement($o_formElement);
        }
    }

    /**
     * addToDatabase($s_ObjName,$a_mapping)
     *
     * Adds the form element objects content to an XPDO table.
     * @param string $s_ObjName
     * @param array $a_mapping
     * @return boolean
     * @ignore
     */
    protected function addToDatabase($s_ObjName, $a_mapping) {
        //inspired by http://bobsguides.com/custom-db-tables.html
        $fields = array();
        foreach ($a_mapping as $a) {
            $o_formObj = $a[0];
            $s_keyName = $a[1];
            $fields[$s_keyName] = $this->postVal($o_formObj->getId());
        }
        $newObj = $this->modx->newObject($s_ObjName, $fields);
        $res = $newObj->save();
        if ($res === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * getFormTableContent()
     *
     * Gets the form value TABLE HTML content.
     * @return string
     * @ignore
     */
    protected function getFormTableContent() {
        $s_style = 'font-size:' . $this->_emailFontSize . '; font-family:' . $this->_emailFontFamily . ';';
        $bgCol1 = '#FFFFFF';
        $bgCol2 = '#e4edf9';
        $bgColDarkerBG = '#cddaeb';
        $colOutline = '#8a99ae';
        $rowCount = 0;
        $s_ret = '<table cellpadding="5" cellspacing="0" style="' . $s_style . '">';
        foreach ($this->_formElements as $o_el) {
            if (is_object($o_el)===false) {
                //do nothing.. this is a simple text element for form html block etc.
            } else {
                if ($o_el->showInEmail() === true) {

                    $bgCol = $bgCol1;
                    if ($rowCount % 2 == 0) {
                        $bgCol = $bgCol2;
                    }

                    $elType = get_class($o_el);
                    $elId = $o_el->getId();

                    switch ($elType) {
                        case 'JsonFormBuilder_elementMatrix':
                            $type = $o_el->getType();
                            $cols = $o_el->getColumns();
                            $rows = $o_el->getRows();
                            $r_cnt = 0;
                            $s_val = '<table cellpadding="5" cellspacing="0" style="' . $s_style . ' font-size:10px;"><tr><td>&nbsp;</td>';
                            $c_cnt = 0;
                            foreach ($cols as $column) {
                                $s_val.='<td style="' . ($c_cnt == 0 ? 'border-left:1px solid ' . $colOutline . '; ' : '') . 'background-color:' . $bgColDarkerBG . '; border-right:1px solid ' . $colOutline . '; border-bottom:1px solid ' . $colOutline . '; border-top:1px solid ' . $colOutline . ';"><em>' . htmlspecialchars($column) . '</em></td>';
                                $c_cnt++;
                            }
                            $s_val.='</tr>';
                            foreach ($rows as $row) {
                                $c_cnt = 0;
                                $s_val.='<tr><td style="' . ($r_cnt == 0 ? 'border-top:1px solid ' . $colOutline . '; ' : '') . 'background-color:' . $bgColDarkerBG . '; border-right:1px solid ' . $colOutline . '; border-left:1px solid ' . $colOutline . '; border-bottom:1px solid ' . $colOutline . ';"><em>' . htmlspecialchars($row) . '</em></td>';
                                foreach ($cols as $column) {
                                    $s_val.='<td style="text-align:center; border-right:1px solid ' . $colOutline . '; border-bottom:1px solid ' . $colOutline . ';">';
                                    switch ($type) {
                                        case 'text':
                                            $s_val.=htmlspecialchars($this->postVal($elId . '_' . $r_cnt . '_' . $c_cnt));
                                            break;
                                        case 'radio':
                                            $s_val.=($c_cnt == $this->postVal($elId . '_' . $r_cnt) ? '&#10004;' : '-');
                                            break;
                                        case 'check':
                                            $s_postVal = $this->postVal($elId . '_' . $r_cnt);
                                            if (empty($s_postVal) === false && in_array($c_cnt, $s_postVal) === true) {
                                                $s_val.='&#10004;';
                                            } else {
                                                $s_val.='-';
                                            }
                                            break;
                                    }
                                    $s_val.='</td>';
                                    $c_cnt++;
                                }
                                $r_cnt++;
                                $s_val.='</tr>';
                            }
                            $s_val.='</table>';
                            break;
                        case 'JsonFormBuilder_elementFile':
                            if (isset($_FILES[$elId])) {
                                $s_val = $_FILES[$elId]['name'];
                                if ($_FILES[$elId]['size'] == 0) {
                                    $s_val = 'None';
                                }
                            }
                            break;
                        case 'JsonFormBuilder_elementCheckboxGroup':
                            $s_val = implode(', ',$this->postVal($o_el->getId()));
                            break;
                        case 'JsonFormBuilder_elementDate':
                            $s_val = $this->postVal($o_el->getId() . '_0') . ' ' . $this->postVal($o_el->getId() . '_1') . ' ' . $this->postVal($o_el->getId() . '_2');
                            break;
                        default:
                            $s_val = nl2br(htmlspecialchars($this->postVal($o_el->getId())));
                            break;
                    }

                    if($elType==='JsonFormBuilder_elementEmailHtml'){
                        $s_ret.=$o_el->outputEmailHTML();
                    }else{
                        if(empty($s_val)===false){
                            $s_ret.='<tr valign="top" bgcolor="' . $bgCol . '"><td><b>' . htmlspecialchars(strip_tags($o_el->getLabel())) . ':</b></td><td>' . $s_val . '</td></tr>';
                            $rowCount++;
                        }
                    }

                }
            }
        }
        $s_ret.='</table>';
        return $s_ret;
    }
    
    /**
     * getPlainTextEmailContent()
     *
     * Gets the form value PLAIN TEXT content.
     * @return string
     * @ignore
     */
   protected function getPlainTextEmailContent() {
        $rowCount = 0;
        $s_ret = '';
        foreach ($this->_formElements as $o_el) {
            if (is_object($o_el)===false) {
                //do nothing.. this is a simple text element for form html block etc.
            } else {
                if ($o_el->showInEmail() === true) {

                    $elType = get_class($o_el);
                    $elId = $o_el->getId();

                    switch ($elType) {
                        case 'JsonFormBuilder_elementMatrix':
                            $type = $o_el->getType();
                            $cols = $o_el->getColumns();
                            $rows = $o_el->getRows();
                            $r_cnt = 0;
                            $s_val = '';
                            $c_cnt = 0;
                            foreach ($cols as $column) {
                                $s_val.= $column . '';
                                $c_cnt++;
                            }
                            $s_val.="\r\n";
                            foreach ($rows as $row) {
                                $c_cnt = 0;
                                $s_val.= $row . ' ';
                                foreach ($cols as $column) {
                                    switch ($type) {
                                        case 'text':
                                            $s_val.=$this->postVal($elId . '_' . $r_cnt . '_' . $c_cnt);
                                            break;
                                        case 'radio':
                                            $s_val.=($c_cnt == $this->postVal($elId . '_' . $r_cnt) ? 'YES' : 'NO');
                                            break;
                                        case 'check':
                                            $s_postVal = $this->postVal($elId . '_' . $r_cnt);
                                            if (empty($s_postVal) === false && in_array($c_cnt, $s_postVal) === true) {
                                                $s_val.='YES';
                                            } else {
                                                $s_val.='NO';
                                            }
                                            break;
                                    }
                                    $s_val.=' ';
                                    $c_cnt++;
                                }
                                $r_cnt++;
                                $s_val.= "\r\n";
                            }
                            $s_val.= "\r\n";
                            break;
                        case 'JsonFormBuilder_elementFile':
                            if (isset($_FILES[$elId])) {
                                $s_val = $_FILES[$elId]['name'];
                                if ($_FILES[$elId]['size'] == 0) {
                                    $s_val = 'None';
                                }
                            }
                            break;
                        case 'JsonFormBuilder_elementCheckboxGroup':
                            $s_val = implode(', ',$this->postVal($o_el->getId()));
                            break;
                        case 'JsonFormBuilder_elementDate':
                            $s_val = $this->postVal($o_el->getId() . '_0') . ' ' . $this->postVal($o_el->getId() . '_1') . ' ' . $this->postVal($o_el->getId() . '_2');
                            break;
                        default:
                            $s_val = $this->postVal($o_el->getId());
                            break;
                    }
                    if(empty($s_val)===false){
                        $s_ret.= strip_tags($o_el->getLabel()) . ' : ' . strip_tags($s_val) . "\r\n";
                    }
                    $rowCount++;
                }
            }
        }
        $s_ret.="\r\n";
        return $s_ret;
    }

    /**
     * toJSON()
     *
     * Returns form data as JSON array.
     * @return string
     * @ignore
     */
    public function toJSON() {
        $a_data = array();
        foreach ($this->_formElements as $o_el) {
            if (is_object($o_el)===false) {
                //do nothing.. this is a simple text element for form html block etc.
            } else {
                    $elType = get_class($o_el);
                    $elId = $o_el->getId();

                    switch ($elType) {
                        case 'JsonFormBuilder_elementMatrix':
                            $type = $o_el->getType();
                            $cols = $o_el->getColumns();
                            $rows = $o_el->getRows();
                            $r_cnt = 0;
                            $s_val = array();
                            $c_cnt = 0;
                            foreach ($rows as $row) {
                                $a_row = array();
                                $c_cnt = 0;
                                foreach ($cols as $column) {
                                    switch ($type) {
                                        case 'text':
                                            $a_row[] =$this->postVal($elId . '_' . $r_cnt . '_' . $c_cnt);
                                            break;
                                        case 'radio':
                                            $a_row[] =($c_cnt == $this->postVal($elId . '_' . $r_cnt) ? true : false);
                                            break;
                                        case 'check':
                                            $s_postVal = $this->postVal($elId . '_' . $r_cnt);
                                            if (empty($s_postVal) === false && in_array($c_cnt, $s_postVal) === true) {
                                                $s_val.='&#10004;';
                                                $a_row[] = true;
                                            } else {
                                                $a_row[] = false;
                                            }
                                            break;
                                    }
                                    $c_cnt++;
                                }
                                $r_cnt++;
                            }
                            break;

                        case 'JsonFormBuilder_elementFile':
                            //Don't add File Element to JSON output.
                            continue 2;
                        case 'JsonFormBuilder_elementDate':
                            $s_val = $this->postVal($o_el->getId() . '_0') . ' ' . $this->postVal($o_el->getId() . '_1') . ' ' . $this->postVal($o_el->getId() . '_2');
                            break;
                        default:
                            $s_val = $this->postVal($o_el->getId());
                            break;
                    }
                    if(empty($s_val)===false){
                        $a_data[] = array('el_id'=>$elId,'label'=>$o_el->getLabel(),'value'=>$s_val);
                    }
            }
        }
        $s_ret=  json_encode($a_data);
        return $s_ret;
    }

    /**
     * autoResponderEmailStr()
     *
     * Gets the Auto Responder email content.
     * @return string
     * @ignore
     */
    protected function autoResponderEmailStr() {
        $NL = "\r\n";
        $s_style = 'font-size:' . $this->_emailFontSize . '; font-family:' . $this->_emailFontFamily . ';';

        $s_emailContent = str_replace('{{tableContent}}', $this->getFormTableContent(), $this->_autoResponderEmailContent);

        $s_ret = '<div style="' . $s_style . '">' . $NL . $s_emailContent . $NL . '</div>';
        return $s_ret;
    }

    public function getEmailContent(){
        $NL = "\r\n";
        $s_style = 'font-size:' . $this->_emailFontSize . '; font-family:' . $this->_emailFontFamily . ';';
        if(!empty($this->_emailReplyToAddress)){
            $replyToAddress = $this->_emailReplyToAddress;
        }else{
            $replyToAddress = $this->_emailFromAddress;
        }
        $s_footHTML = str_replace(
                array('{{replyToEmailAddress}}', '{{subject}}'), array(htmlspecialchars($replyToAddress), htmlspecialchars($this->_emailSubject)), $this->_emailFootHtml
        );

        $s_emailContent = '<!DOCTYPE HTML><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head><body><div style="' . $s_style . '">' . $NL . $this->_emailHeadHtml . $NL
                . $this->getFormTableContent() . $NL
                . $s_footHTML . $NL
                . '</div></body></html>';
        return $s_emailContent;
    }
    function sendEmails() {
        $b_success = true;
        $this->modx->getService('mail', 'mail.modPHPMailer');


        $s_emailContent = $this->getEmailContent();
        $s_plainTextEmailContent = $this->getPlainTextEmailContent();
        if(!empty($this->_emailToAddress)){
            $this->modx->mail->set(modMail::MAIL_BODY, $s_emailContent);
            $this->modx->mail->set(modMail::MAIL_BODY_TEXT, $s_plainTextEmailContent);
            $this->modx->mail->set(modMail::MAIL_FROM, self::forceEmail($this->_emailFromAddress,' Issue with emailFromAddress.'));
            if (empty($this->_emailFromName) === false) {
                $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->_emailFromName);
            }
            $this->modx->mail->set(modMail::MAIL_SUBJECT, $this->_emailSubject);
            //Set to address/addresses
            if(is_array($this->_emailToAddress)===true){
                foreach($this->_emailToAddress as $add){
                    $this->modx->mail->address('to', self::forceEmail($add,' Issue with emailToAddress (ARRAY).'));
                }
            }else{
                $this->modx->mail->address('to', self::forceEmail($this->_emailToAddress,' Issue with emailToAddress.'));
            }
            //Set cc address/addresses
            if(!empty($this->_emailCCAddress)){
                if(is_array($this->_emailCCAddress)===true){
                    foreach($this->_emailCCAddress as $add){
                        $this->modx->mail->address('cc', self::forceEmail($add,' Issue with emailCCAddress (ARRAY).'));
                    }
                }else{
                    $this->modx->mail->address('cc', self::forceEmail($this->_emailCCAddress,' Issue with emailCCAddress.'));
                }
            }
            //Set bcc address/addresses
            if(!empty($this->_emailBCCAddress)){
                if(is_array($this->_emailBCCAddress)===true){
                    foreach($this->_emailBCCAddress as $add){
                        $this->modx->mail->address('bcc', self::forceEmail($add,' Issue with emailBCCAddress (ARRAY).'));
                    }
                }else{
                    $this->modx->mail->address('bcc', self::forceEmail($this->_emailBCCAddress,' Issue with emailBCCAddress.'));
                }
            }
            //reply address
            if(!empty($this->_emailReplyToAddress)){
                $this->modx->mail->address('reply-to', self::forceEmail($this->_emailReplyToAddress,' Issue with replyToAddress.'));
            }else{
                $this->modx->mail->address('reply-to', self::forceEmail($this->_emailFromAddress,' Issue with replyToAddress.'));
            }
            /* handle file fields */
            foreach ($this->_formElements as $o_el) {
                if (is_object($o_el) && get_class($o_el) == 'JsonFormBuilder_elementFile' && $o_el->showInEmail() === true) {
                    if(isset($_FILES[$o_el->getId()])===true){
                        $file = $_FILES[$o_el->getId()];
                        $this->modx->mail->mailer->AddAttachment($file['tmp_name'],$file['name'],'base64',!empty($file['type']) ? $file['type'] : 'application/octet-stream');
                    }
                }
            }
            $this->modx->mail->setHTML(true);
            if (!$this->modx->mail->send()) {
                $b_success = false;
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: ' . $this->modx->mail->mailer->ErrorInfo);
            }
            $this->modx->mail->reset();
        }

        //Handle auto responders if needed.
        if(!empty($this->_autoResponderToAddress)){
            $this->modx->mail->set(modMail::MAIL_BODY, $this->autoResponderEmailStr());
            $this->modx->mail->set(modMail::MAIL_FROM, self::forceEmail($this->_autoResponderFromAddress,' Issue with autoresponderFromAddress.'));
            if(empty($this->_autoResponderFromName) === false) {
                $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->_autoResponderFromName);
            }
            $this->modx->mail->set(modMail::MAIL_SUBJECT, $this->_autoResponderSubject);
            //Set to address/addresses
            if(is_array($this->_autoResponderToAddress)===true){
                foreach($this->_autoResponderToAddress as $add){
                    $this->modx->mail->address('to', self::forceEmail($add,' Issue with autoResponderToAddress (ARRAY).'));
                }
            }else{
                $this->modx->mail->address('to', self::forceEmail($this->_autoResponderToAddress,' Issue with autoResponderToAddress.'));
            }
            //CC
            if(!empty($this->_autoResponderCC)){
                if(is_array($this->_autoResponderCC)===true){
                    foreach($this->_autoResponderCC as $add){
                        $this->modx->mail->address('cc', self::forceEmail($add,' Issue with autoResponderCC (ARRAY).'));
                    }
                }else{
                    $this->modx->mail->address('cc', self::forceEmail($this->_autoResponderCC,' Issue with autoResponderCC.'));
                }
            }
            //BCC
            if(!empty($this->_autoResponderBCC)){
                if(is_array($this->_autoResponderBCC)===true){
                    foreach($this->_autoResponderBCC as $add){
                        $this->modx->mail->address('bcc', self::forceEmail($add,' Issue with autoResponderBCC (ARRAY).'));
                    }
                }else{
                    $this->modx->mail->address('bcc', self::forceEmail($this->_autoResponderBCC,' Issue with autoResponderBCC (ARRAY).'));
                }
            }

            $this->modx->mail->address('reply-to', self::forceEmail($this->_autoResponderFromAddress,' Issue with autoResponderFromAddress.'));
            $this->modx->mail->setHTML(true);
            if (!$this->modx->mail->send()) {
                $b_success = false;
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the auto repsonder email: ' . $this->modx->mail->mailer->ErrorInfo);
            }
            $this->modx->mail->reset();
        }
        return $b_success;
    }


    public function getElementById($id){
        foreach ($this->_formElements as $o_el){
            if(is_object($o_el) && $o_el->getId()===$id){
                return $o_el;
            }
        }
        return false;
    }
    public function addError($o_el,$s_validationMessage){
        $this->_invalidElements[] = $o_el;
        $o_el->errorMessages[] = $s_validationMessage;
    }
    /**
     * jqueryValidateJSON($jqFieldProps,$jqFieldMessages,$jqFormRules,$jqFormMessages)
     *
     * Processes the fields into jQueryValidate output
     * @param string $jqFieldProps
     * @param string $jqFieldMessages
     * @param string $jqFormRules
     * @param string $jqFormMessages
     * @return string
     * @ignore
     */
    protected function jqueryValidateJSON($jqFieldProps, $jqFieldMessages, $jqGroups) {
        $a_ruleSegs = array();
        $a_msgSegs = array();
        foreach ($jqFieldProps as $fieldID => $a_fieldProp) {
            if (count($a_fieldProp) > 0) {
                $a_ruleSegs[] = '\'' . $fieldID . '\':{' . implode(',', $a_fieldProp) . '}';
            }
        }
        foreach ($jqFieldMessages as $fieldID => $a_fieldMsg) {
            if (count($a_fieldMsg) > 0) {
                $a_msgSegs[] = '\'' . $fieldID . '\':{' . implode(',', $a_fieldMsg) . '}';
            }
        }
        $s_js = 'rules:{  ' . "\r\n  " . implode(",\r\n  ", $a_ruleSegs) . "\r\n" . '},' .
                'messages:{  ' . "\r\n  " . implode(",\r\n  ", $a_msgSegs) . "\r\n" . '}'
        ;
        if (empty($jqGroups) === false) {
            $s_js.=',groups:{' . "\r\n";
            $a_groupLines = array();
            foreach ($jqGroups as $key => $val) {
                $a_groupLines[] = $key . ':"' . $val . '"';
            }
            $s_js.=implode(",\r\n", $a_groupLines);
            $s_js.='}' . "\r\n";
        }

        return $s_js;
    }

    protected function spamDetectExit($code){
        if($this->getSpamProtection()){
            echo 'Form was unable to submit (CODE: '.$code.').'; exit();
        }
    }
    public function validate(){
        //Prepare can be called multiple times for simplicity, but should only run once.
        if($this->b_validated===true){
            return;
        }else{
            $this->b_validated=true;
        }

        //If security field has been filled, kill script with a false thank you.
        $secVar = $this->postVal($this->_id.'_fke' . date('Y') . 'Sp' . date('m') . 'Blk');
        //This field's value is set with javascript. If the field does not equal the second value, it will exit.
        $secVar2 = $this->postVal($this->_id.'_fke' . date('Y') . 'Sp' . date('m') . 'Blk2');
        if(strlen($secVar)>0){
            $this->spamDetectExit(1);
        }
        if($secVar2!==false && $secVar2!='1962'){
            $this->spamDetectExit(2);
        }

        $this->setIsSubmitted(false);
        $s_submittedVal = $this->postVal('submitVar_' . $this->_id);
        if (empty($s_submittedVal) === false) {
            $this->setIsSubmitted(true);
        }

        //Process and add form rules
        $a_fieldProps_jqValidate = array();
        $a_fieldProps_jqValidateGroups = array();
        $a_fieldProps_errstringJq = array();
        $a_footJavascript = array();

        //Keep tally of all validation errors. If posted and 0, form will continue.
        foreach ($this->_rules as $rule) {
            $o_elFull = $rule->getElement();
            //Verify this element is actually in the form
            $b_found=false;
            foreach ($this->_formElements as $o_el){
                if($o_elFull===$o_el){
                    $b_found=true;
                    break;
                }
            }
            if($b_found===false){
                JsonFormBuilder::throwError('Rule "'.$rule->getType().'" for element "'.$o_elFull->getId().'" specified, but element is not in form.');
            }

            if (is_array($o_elFull) === true) {
                $o_el = $o_elFull[0];
            } else {
                $o_el = $o_elFull;
            }
            $elId = $o_el->getId();
            //used to test simple single post values
            $s_postedValue = $this->postVal($o_el->getId());
            $elName = $o_el->getName();
            if (isset($a_fieldProps_jqValidate[$elId]) === false) {
                $a_fieldProps_jqValidate[$elId] = array();
            }
            if (isset($a_fieldProps_errstringJq[$elId]) === false) {
                $a_fieldProps_errstringJq[$elId] = array();
            }

            $s_validationMessage = $rule->getValidationMessage();
            switch ($rule->getType()) {
                case FormRuleType::email:
                    $a_fieldProps_jqValidate[$elName][] = 'email:true';
                    $a_fieldProps_errstringJq[$elName][] = 'email:' . json_encode($s_validationMessage);
                    if(!empty($s_postedValue)){
                        if (filter_var($s_postedValue, FILTER_VALIDATE_EMAIL) === false) {
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_validationMessage;
                        }
                    }
                    break;
                case FormRuleType::url:
                    $a_fieldProps_jqValidate[$elName][] = 'url:true';
                    $a_fieldProps_errstringJq[$elName][] = 'url:'.json_encode($s_validationMessage);
                    if(!empty($s_postedValue)){
                        if (filter_var($s_postedValue, FILTER_VALIDATE_URL) === false) {
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_validationMessage;
                        }
                    }
                    break;
                case FormRuleType::fieldMatch:
                    $val = $rule->getValue();
                    if(is_a($val,'JsonFormBuilder_baseElement')===false){
                        $val = $this->getElementById($val);
                    }
                    if(is_a($val,'JsonFormBuilder_baseElement')===false){
                        JsonFormBuilder::throwError('Element for fieldMatch not found for "'.htmlspecialchars($elName).'" rule. Specify a valid ID or Element Object');
                    }
                    $a_fieldProps_jqValidate[$elName][] = 'equalTo:"#' . $val->getId() . '"';
                    $a_fieldProps_errstringJq[$elName][] = 'equalTo:'.json_encode($s_validationMessage);

                    //validation check
                    $val1 = $this->postVal($o_elFull->getId());
                    $val2 = $this->postVal($val->getId());
                    if ($val1!==$val2) {
                        $this->_invalidElements[] = $o_el;
                        $o_el->errorMessages[] = $s_validationMessage;
                    }

                    break;
                case FormRuleType::maximumLength:
                    $val = (int) $rule->getValue();
                    $a_fieldProps_jqValidate[$elName][] = 'maxlength:' . $val;
                    $a_fieldProps_errstringJq[$elName][] = 'maxlength:'.json_encode($s_validationMessage);
                    if (is_a($o_el, 'JsonFormBuilder_elementCheckboxGroup')) {
                        //validation check
                        $a_elementsSelected = $this->postVal($o_el->getId());
                        if(is_array($a_elementsSelected)===true && count($a_elementsSelected)>0){ //ignore if not selected at all as "required" should pick this up.
                            if (is_array($a_elementsSelected)===false || count($a_elementsSelected) > $val) {
                                $this->_invalidElements[] = $o_el;
                                $o_el->errorMessages[] = $s_validationMessage;
                            }
                        }
                    } else {
                        //validation check
                        if (strlen($s_postedValue) > $val) {
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_validationMessage;
                        }
                    }
                    break;
                case FormRuleType::maximumValue:
                    $val = (int) $rule->getValue();
                    $a_fieldProps_jqValidate[$elName][] = 'max:' . $val;
                    $a_fieldProps_errstringJq[$elName][] = 'max:'.json_encode($s_validationMessage);

                    //validation check
                    if ($s_postedValue!='' && (int) $s_postedValue > $val) {
                        $this->_invalidElements[] = $o_el;
                        $o_el->errorMessages[] = $s_validationMessage;
                    }

                    break;
                case FormRuleType::minimumLength:
                    $val = (int) $rule->getValue();
                    $a_fieldProps_jqValidate[$elName][] = 'minlength:' . $val;
                    $a_fieldProps_errstringJq[$elName][] = 'minlength:'.json_encode($s_validationMessage);
                    if (is_a($o_el, 'JsonFormBuilder_elementCheckboxGroup')) {
                        //validation check
                        $a_elementsSelected = $this->postVal($o_el->getId());
                        if(is_array($a_elementsSelected)===true && count($a_elementsSelected)>0){ //ignore if not selected at all as "required" should pick this up.
                            if (is_array($a_elementsSelected)===false || count($a_elementsSelected) < $val) {
                                $this->_invalidElements[] = $o_el;
                                $o_el->errorMessages[] = $s_validationMessage;
                            }
                        }
                    } else {
                        //validation check
                        if (strlen($s_postedValue) > 0 && strlen($s_postedValue) < $val) {
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_validationMessage;
                        }
                    }
                    break;
                case FormRuleType::minimumValue:
                    $val = (int) $rule->getValue();

                    $a_fieldProps_jqValidate[$elName][] = 'min:' . $val;
                    $a_fieldProps_errstringJq[$elName][] = 'min:'.json_encode($s_validationMessage);

                    //validation check
                    if ($s_postedValue!='' && (int) $s_postedValue < $val) {
                        $this->_invalidElements[] = $o_el;
                        $o_el->errorMessages[] = $s_validationMessage;
                    }
                    break;
                case FormRuleType::numeric:
                    $a_fieldProps_jqValidate[$elName][] = 'digits:true';
                    $a_fieldProps_errstringJq[$elName][] = 'digits:'.json_encode($s_validationMessage);
                    //validation check
                    if ($s_postedValue!='' && ctype_digit($s_postedValue) === false) {
                        $this->_invalidElements[] = $o_el;
                        $o_el->errorMessages[] = $s_validationMessage;
                    }
                    break;
                case FormRuleType::required:
                    $jqRequiredVal='true';

                    $ruleCondition = $rule->getCondition();
                    if(!empty($ruleCondition) && is_a($ruleCondition[0],'JsonFormBuilder_baseElement')===false){
                        $ruleCondition[0] = $this->getElementById($ruleCondition[0]);
                    }
                    $b_validateRequiredPost=true;
                    if(!empty($ruleCondition)){
                        $this_elID = $ruleCondition[0]->getId();
                         if(is_a($ruleCondition[0],'JsonFormBuilder_elementRadioGroup')){
                             $s_valJq = 'jQuery("input[type=radio][name='.$this_elID.']")';
                         }else{
                             $s_valJq = 'jQuery("#'.$this_elID.'")';
                         }
                        if(is_a($ruleCondition[0],'JsonFormBuilder_elementCheckbox')){
                            $jqRequiredVal='{depends:function(element){var $_this = '.$s_valJq.'; var v=$_this.val(); var c=$_this.prop("checked"); return (v=='.json_encode($ruleCondition[1]).'?c:!c); }}';
                        }else{
                            $jqRequiredVal='{depends:function(element){var v='.$s_valJq.'.val(); return (v=='.json_encode($ruleCondition[1]).'?true:false); }}';
                        }
                        $b_validateRequiredPost=false;
                        if($this->postVal($this_elID)==$ruleCondition[1]){
                            $b_validateRequiredPost=true;
                        }
                    }
                    if (is_a($o_el, 'JsonFormBuilder_elementMatrix')) {
                        $s_type = $o_el->getType();
                        $a_rows = $o_el->getRows();
                        $a_columns = $o_el->getColumns();
                        $a_namesForGroup = array();
                        switch ($s_type) {
                            case 'text':
                                for ($row_cnt = 0; $row_cnt < count($a_rows); $row_cnt++) {
                                    for ($col_cnt = 0; $col_cnt < count($a_columns); $col_cnt++) {
                                        $a_namesForGroup[] = $elName . '_' . $row_cnt . '_' . $col_cnt;
                                        $a_fieldProps_jqValidate[$elName . '_' . $row_cnt . '_' . $col_cnt][] = 'required:'.$jqRequiredVal;
                                        $a_fieldProps_errstringJq[$elName . '_' . $row_cnt . '_' . $col_cnt][] = 'required:'.json_encode($s_validationMessage);
                                    }
                                }
                                break;
                            case 'radio':
                                for ($row_cnt = 0; $row_cnt < count($a_rows); $row_cnt++) {
                                    $a_namesForGroup[] = $elName . '_' . $row_cnt;
                                    $a_fieldProps_jqValidate[$elName . '_' . $row_cnt][] = 'required:'.$jqRequiredVal;
                                    $a_fieldProps_errstringJq[$elName . '_' . $row_cnt][] = 'required:'.json_encode($s_validationMessage);
                                }
                                break;
                            case 'check':
                                for ($row_cnt = 0; $row_cnt < count($a_rows); $row_cnt++) {
                                    $s_fieldName = $elName . '_' . $row_cnt . '[]';
                                    $a_namesForGroup[] = $s_fieldName;
                                    $a_fieldProps_jqValidate[$s_fieldName][] = 'required:'.$jqRequiredVal;
                                    $a_fieldProps_errstringJq[$s_fieldName][] = 'required:'.json_encode($s_validationMessage);
                                }
                                break;
                        }
                        $a_fieldProps_jqValidateGroups[$elName] = implode(' ', $a_namesForGroup);

                        //validation check
                        $b_isMatrixValid = JsonFormBuilder::is_matrix_required_valid($o_el);
                        if ($b_validateRequiredPost && $b_isMatrixValid===false) {
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_validationMessage;
                        }
                    } else if (is_a($o_el, 'JsonFormBuilder_elementCheckboxGroup')) {
                        //validation check
                        $a_elementsSelected = $this->postVal($o_el->getId());
                        if ($b_validateRequiredPost && (is_array($a_elementsSelected)===false || count($a_elementsSelected)===0)) {
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_validationMessage;
                        }

                    } else if (is_a($o_el, 'JsonFormBuilder_elementFile')) {
                        //validation check
                        if(isset($_FILES[$o_el->getId()])===true && $_FILES[$o_el->getId()]['size']!=0){
                            //file is uploaded
                        }else{
                            if($b_validateRequiredPost){
                                $this->_invalidElements[] = $o_el;
                                $o_el->errorMessages[] = $s_validationMessage;
                            }
                        }
                    } else if (is_a($o_el, 'JsonFormBuilder_elementDate')) {
                        $a_fieldProps_jqValidate[$elName . '_0'][] = 'required:'.$jqRequiredVal.',dateElementRequired:true';
                        $a_fieldProps_errstringJq[$elName . '_0'][] = 'required:'.json_encode($s_validationMessage).',dateElementRequired:'.json_encode($s_validationMessage);

                        //validation check
                        $elID = $o_el->getId();
                        $postVal0 = $this->postVal($elID.'_0');
                        $postVal1 = $this->postVal($elID.'_1');
                        $postVal2 = $this->postVal($elID.'_2');
                        if(empty($postVal0)===false && empty($postVal1)===false && empty($postVal2)===false){
                            //all three date elements must be selected
                        }else{
                            if($b_validateRequiredPost){
                                $this->_invalidElements[] = $o_el;
                                $o_el->errorMessages[] = $s_validationMessage;
                            }
                        }

                    } else {
                        $a_fieldProps_jqValidate[$elName][] = 'required:'.$jqRequiredVal;
                        $a_fieldProps_errstringJq[$elName][] = 'required:'.json_encode($s_validationMessage);

                        //validation check
                        if ($b_validateRequiredPost && strlen($s_postedValue) < 1) {
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_validationMessage;
                        }
                    }
                    break;
                case FormRuleType::date:
                    $s_thisVal = $rule->getValue();
                    $s_thisErrorMsg = str_replace('===dateformat===', $s_thisVal, $s_validationMessage);
                    $a_fieldProps_jqValidate[$elName][] = 'dateFormat:\'' . $s_thisVal . '\'';
                    $a_fieldProps_errstringJq[$elName][] = 'dateFormat:'.json_encode($s_thisErrorMsg);
                    //validation check
                    $a_formatInfo = JsonFormBuilder::is_valid_date($s_postedValue,$s_thisVal);
                    if ($b_validateRequiredPost && $a_formatInfo['status']===false) {
                        $this->_invalidElements[] = $o_el;
                        $o_el->errorMessages[] = $s_thisErrorMsg;
                    }
                    break;
                case FormRuleType::custom:
                    $custRuleName = $rule->getCustomRuleName();
                    if(empty($custRuleName)){
                        JsonFormBuilder::throwError('CustomRuleName for custom rule not set (Element "'.htmlspecialchars($elName).'" '.self::fieldMatch.'").');
                    }else{
                        $custval = 'true';
                        $custRuleParam = $rule->getCustomRuleParam();
                        if(!empty($custRuleParam)){
                            $custval = json_encode($custRuleParam);
                        }

                        $a_fieldProps_jqValidate[$elName][] = $custRuleName.':'.$custval;
                        $a_fieldProps_errstringJq[$elName][] = $custRuleName.':'.json_encode($s_validationMessage);
                        //validation check
                        $func = $rule->getCustomRuleValidateFunction();
                        if(!empty($func)){
                            //validate server side
                            if(!empty($custRuleParam)){
                                $valid = $func($s_postedValue,$custRuleParam);
                            }else{
                                $valid = $func($s_postedValue);
                            }
                            if($valid!==true){
                                $this->_invalidElements[] = $o_el;
                                $o_el->errorMessages[] = $s_validationMessage;
                            }
                        }
                    }
                    break;
                case FormRuleType::conditionShow:
                    $jqRequiredVal='true';

                    $ruleCondition = $rule->getCondition();
                    if(!empty($ruleCondition) && is_a($ruleCondition[0],'JsonFormBuilder_baseElement')===false){
                        $ruleCondition[0] = $this->getElementById($ruleCondition[0]);
                    }

                    $b_validateRequiredPost=true;
                    if(!empty($ruleCondition)){
                        $this_elID = $ruleCondition[0]->getId();
                        if(count($a_footJavascript)==0){
                            $a_footJavascript[]='';  //var a; var e; var w; var v;  var b_s;
                        }
                        
                        //input[type=radio][name=bedStatus]
                        $a_footJavascript[]=''
                            . 'var b_v=false;'
                            . 'var v;'
                            . (is_a($ruleCondition[0],'JsonFormBuilder_elementRadioGroup')?' var a=jQuery("input[type=radio][name='.$this_elID.']"); if(a.is(":checked")===false){v="";}else{v=a.val();}':'var a=jQuery("#'.$this_elID.'"); v=a.val();')
                            . (is_a($ruleCondition[0],'JsonFormBuilder_elementCheckbox')?'var e=jQuery("[name='.$o_elFull->getId().']"); var w=e.parents(".formSegWrap"); var c=$("input[type=checkbox][name='.$this_elID.']").prop("checked"); var s = ($("input[type=checkbox][name='.$this_elID.']").val()=='.json_encode($ruleCondition[1]).'?c:!c);  if(s){ b_v=true;  }else{ b_v=false;  }':'if(v=='.json_encode($ruleCondition[1]).'){ b_v=true; }')
                            . 'var e=jQuery("[name='.$o_elFull->getId().']");'
                            . 'var p=e.parents(".formSegWrap");'
                            . 'if(b_v){p.show();}else{ p.hide(); }'
                            . (is_a($ruleCondition[0],'JsonFormBuilder_elementCheckbox')?'a.change(function(){ var e=jQuery("[name='.$o_elFull->getId().']"); var w=e.parents(".formSegWrap"); var c=$(this).prop("checked"); var s = (jQuery(this).val()=='.json_encode($ruleCondition[1]).'?c:!c); if(s){ w.show(); }else{ w.hide(); } });':'a.change(function(){ var e=jQuery("[name='.$o_elFull->getId().']"); var w=e.parents(".formSegWrap"); if(jQuery(this).val()=='.json_encode($ruleCondition[1]).'){ w.show(); }else{ w.hide(); } });')
                            . '';
                    }
                    break;
            }
        }
        //validate on elements themselves
        foreach ($this->_formElements as $o_el) {
            if(is_object($o_el)===false){
                //
            }else{
                $s_elClass = get_class($o_el);
                $s_postedValue = $this->postVal($o_el->getId());
                if ($s_elClass == 'JsonFormBuilder_elementFile') {
                    $this->_attachmentIncluded = true;
                    $id = $o_el->getId();

                    $a_allowedExtenstions = $o_el->getAllowedExtensions();
                    $i_maxSize = $o_el->getMaxFilesize();
                    if($a_allowedExtenstions){
                        $s_extInvalidMessage = 'Only '.strtoupper(implode(', ',$a_allowedExtenstions)).' files allowed.';
                        $a_fieldProps_jqValidate[$id][] = 'fileExtValid:'.json_encode($a_allowedExtenstions);
                        $a_fieldProps_errstringJq[$id][] = 'fileExtValid:"' . $s_extInvalidMessage . '"';
                    }
                    if($i_maxSize){
                        $mbOrkb = 'mb';
                        $size = round($i_maxSize/1024/1024,2);
                        if($size<1){
                            $size = round($i_maxSize/1024);
                            $mbOrkb = 'kb';
                        }
                        $s_sizeInvalidMessage = 'File exceeds size limit ('.$size.$mbOrkb.').';
                        $a_fieldProps_jqValidate[$id][] = 'fileSizeValid:'.$i_maxSize;
                        $a_fieldProps_errstringJq[$id][] = 'fileSizeValid:"' . $s_sizeInvalidMessage . '"';
                    }

                    //validation
                    if(isset($_FILES[$id]['size'])===true && $_FILES[$id]['size']>0){

                        if($o_el->isAllowedFilename($_FILES[$id]['name'])===false){
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_extInvalidMessage;
                        }

                        if($o_el->isAllowedSize($_FILES[$id]['size'])===false){
                            $this->_invalidElements[] = $o_el;
                            $o_el->errorMessages[] = $s_sizeInvalidMessage;
                        }
                    }
                }
            }
        }

        $this->_fieldProps_jqValidate=$a_fieldProps_jqValidate;
        $this->_fieldProps_jqValidateGroups=$a_fieldProps_jqValidateGroups;
        $this->_fieldProps_errstringJq=$a_fieldProps_errstringJq;
        $this->_footJavascript=$a_footJavascript;
    }

    protected function buildForm(){
        //build inner form HTML

        $nl = "\r\n";
        $s_recaptchaJS = '';
        $fieldThatNeedsToBeFilled = $this->_id.'_fke' . date('Y') . 'Sp' . date('m') . 'Blk2';

        $s_form = '<div>' . $nl
                . $nl . '<div class="process_errors_wrap"><div class="process_errors">[[!+fi.error_message:notempty=`[[!+fi.error_message]]`]]</div></div>'
                . $nl . '<input type="hidden" name="submitVar_'.$this->_id.'" value="1" />'
                . $nl . '<input type="hidden" name="'.$this->_id.'_fke' . date('Y') . 'Sp' . date('m') . 'Blk" value="" /><input type="hidden" name="'.$fieldThatNeedsToBeFilled. '" id="'.$fieldThatNeedsToBeFilled. '" value="" /><script type="text/javascript">var el = document.getElementById("'.$fieldThatNeedsToBeFilled.'"); el.value = "1962";</script>'
                . $nl;

        foreach ($this->_formElements as $o_el) {
            if(is_object($o_el)===false){
                $s_form.=$o_el; //plain text or html
            }else{
                $s_elClass = get_class($o_el);
                if (is_a($o_el, 'JsonFormBuilder_elementHidden')) {
                    $s_form.=$o_el->outputHTML();
                } else {
                    $s_typeClass = substr($s_elClass, 16, strlen($s_elClass) - 16);
                    $forId = $o_el->getId();
                    if(is_a($o_el, 'JsonFormBuilder_elementRadioGroup') === true || is_a($o_el, 'JsonFormBuilder_elementCheckboxGroup') === true || is_a($o_el, 'JsonFormBuilder_elementDate') === true) {
                        $forId = $o_el->getId() . '_0';
                    }elseif(is_a($o_el, 'JsonFormBuilder_elementMatrix') === true) {
                       $forId = $o_el->getId() . '_0_0';
                    }

                    $s_forStr = ' for="' . htmlspecialchars($forId) . '"';

                    if (is_a($o_el, 'JsonFormBuilder_elementReCaptcha') === true) {
                        $s_forStr = ''; // don't use for attrib for Recaptcha (as it is an external program outside control of JsonFormBuilder
                        $s_recaptchaJS = $o_el->getJsonConfig();
                    }

                    $s_extraClasses = '';
                    $a_exClasses = $o_el->getExtraClasses();
                    if (count($a_exClasses) > 0) {
                        $s_extraClasses = ' ' . implode(' ', $o_el->getExtraClasses());
                    }

                    $b_required = $o_el->isRequired();

                    $s_errorContainer = '<div class="errorContainer">';
                    if ($this->getIsSubmitted()) {
                        if (count($o_el->errorMessages) > 0) {
                            $s_errorContainer.='<label class="postederror" ' . $s_forStr . '>' . implode('<br />', $o_el->errorMessages) . '</label>';
                        }
                    }
                    $s_errorContainer.='</div>';

                    $s_form.='<div title="' . $o_el->getLabel() . '" class="formSegWrap formSegWrap_' . htmlspecialchars($o_el->getId()) . ' ' . $s_typeClass . ($b_required === true ? ' required' : '') . $s_extraClasses . '">';
                    $s_labelHTML = '';
                    if ($o_el->showLabel() === true) {
                        $s_desc = $o_el->getDescription();
                        if (empty($s_desc) === false) {
                            $s_desc = '<span class="description">' . $s_desc . '</span>';
                        }
                        $s_labelHTML = '<label class="mainElLabel"' . $s_forStr . '><span class="before"></span><span class="mainLabel">' . $o_el->getLabel() . '</span>' . $s_desc . '<span class="after"></span></label>';
                        if ($o_el->getLabelAfterElement()===true){
                            $s_labelHTML.=$s_errorContainer;
                        }
                    }

                    $s_element = '<div class="elWrap">' . $nl . '    <span class="before"></span>' . $o_el->outputHTML() . '<span class="after"></span>';
                    if ($o_el->getLabelAfterElement()===false && $o_el->showLabel() === true) {
                        $s_element.=$s_errorContainer;
                    }
                    $s_element.='</div>';

                    if ($o_el->getLabelAfterElement() === true) {
                        $s_form.=$s_element . $s_labelHTML;
                    } else {
                        $s_form.=$s_labelHTML . $s_element;
                    }
                    $s_form.=$nl . '</div>' . $nl;
                }
            }
        }
        $s_form.=$nl . '</div>';

        //wrap form elements in form tags
        $formAction = '[[~[[*id]]]]';
        if(empty($this->_formAction)===false){
            $formAction = $this->_formAction;
        }
        $s_form = ''
            . '<form '.($this->_spamProtection===true?'style="display:none;"':'').' action="'.($this->_spamProtection===true?'/':$formAction).'" method="' . htmlspecialchars($this->_method) . '"' . ($this->_attachmentIncluded ? ' enctype="multipart/form-data"' : '') . ' class="form" id="' . htmlspecialchars($this->_id) . '">' . $nl
            . $s_form . $nl
            . '</form>';
        if($this->_spamProtection===true){
            $s_form .='<script type="text/javascript">var form = document.getElementById("'.$this->_id.'"); form.setAttribute("action","'.$formAction.'"); form.style.display = "block";</script><noscript>Your browser does not support JavaScript! - You need JavaScript to view this form.</noscript>';
        }
        return $s_form;
    }

    protected function buildFormJavascript(){
        $s_js='';
        if ($this->_jqueryValidation === true) {
            $s_js .= '
jQuery().ready(function() {

jQuery.validator.addMethod("dateFormat", function(value, element, format) {
	var b_retStatus=false;
	var s_retValue="";
	var n_retTimestamp=0;
	if(value.length==format.length){
		var separator_only = format;
		var testDate;
		if(format.toLowerCase()=="yyyy"){
			//allow just yyyy
			testDate = new Date(value, 1, 1);
			if(testDate.getFullYear()==value){
				b_retStatus=true;
			}
		}else{
			separator_only = separator_only.replace(/m|d|y/g,"");
			var separator = separator_only.charAt(0)

			if(separator && separator_only.length==2){
				var dayPos; var day; var monthPos; var month; var yearPos; var year;
				var s_testYear;
				var newStr = format;

				dayPos = format.indexOf("dd");
				day = parseInt(value.substr(dayPos,2),10)+"";
				if(day.length==1){day="0"+day;}
				newStr=newStr.replace("dd",day);

				monthPos = format.indexOf("mm");
				month = parseInt(value.substr(monthPos,2),10)+"";
				if(month.length==1){month="0"+month;}
				newStr=newStr.replace("mm",month);

				yearPos = format.indexOf("yyyy");
				year = parseInt(value.substr(yearPos,4),10);
				newStr=newStr.replace("yyyy",year);

				testDate = new Date(year, month-1, day);

				var testDateDay=(testDate.getDate())+"";
				if(testDateDay.length==1){testDateDay="0"+testDateDay;}

				var testDateMonth=(testDate.getMonth()+1)+"";
				if(testDateMonth.length==1){testDateMonth="0"+testDateMonth;}

				if (testDateDay==day && testDateMonth==month && testDate.getFullYear()==year) {
					b_retStatus = true;
					jQuery(element).val(newStr);
				}
			}
		}
	}
	return this.optional(element) || b_retStatus;
}, "Please enter a valid date.");

jQuery.validator.addMethod("dateElementRequired", function(value, element) {
	b_retStatus=true;
	var elBaseId=element.id.substr(0,element.id.length-2);
	if(jQuery("#"+elBaseId+"_0").val()=="" || jQuery("#"+elBaseId+"_1").val()=="" || jQuery("#"+elBaseId+"_2").val()==""){
		b_retStatus=false;
	}
	return this.optional(element) || b_retStatus;
}, "Date element is required.");

jQuery.validator.addMethod("fileExtValid", function(value, element, a_ext) {
    var b_retStatus=true;
    if(value!==""){
        b_retStatus=false;
        var a_valSplit = value.split(".");
        var ext = a_valSplit[a_valSplit.length-1];
        var i; for(i=0;i<a_ext.length;i++){ if(a_ext[i].toLowerCase()==ext.toLowerCase()){ b_retStatus=true; break; } }
    }
	return this.optional(element) || b_retStatus;
}, "File type not allowed.");

jQuery.validator.addMethod("fileSizeValid", function(value, element, size) {
    var b_retStatus=true;
    if(element.files.length>0){
        if(element.files[0].size>size){
            b_retStatus=false;
        }
    }
	return this.optional(element) || b_retStatus;
}, "File too large.");

//Main validate call
var thisFormEl=jQuery("#' . $this->_id . '");
thisFormEl.validate({errorPlacement:function(error, element) {
	var labelEl = element.parents(".formSegWrap").find(".errorContainer");
	error.appendTo( labelEl );
},success: function(element) {
	element.addClass("valid");
	var formSegWrapEl = element.parents(".formSegWrap");
	formSegWrapEl.children(".mainElLabel").removeClass("mainLabelError");
},highlight: function(el, errorClass, validClass) {
	var element= jQuery(el);
	element.addClass(errorClass).removeClass(validClass);
	element.parents(".formSegWrap").children(".mainElLabel").addClass("mainLabelError");
},invalidHandler: function(form, validator){
	var jumpEl = jQuery("#"+validator.errorList[0].element.id).parents(".formSegWrap");
	jQuery("html,body").animate({scrollTop: jumpEl.offset().top});
},ignore:":hidden",' .
$this->jqueryValidateJSON(
        $this->_fieldProps_jqValidate, $this->_fieldProps_errstringJq, $this->_fieldProps_jqValidateGroups
) .(!empty($this->_submitHandler)?',submitHandler:function(form){'.$this->_submitHandler.'}':'') .'});

var hiddenFields = thisFormEl.find(".formSegWrap.elementFile input");
hiddenFields.each(function(){
    var elId = $(this).attr("id");
    var fileEl = $("#"+elId);
    var removeButt = $("#"+elId+"_remove");
    removeButt.click(function(){
        fileEl.val(""); $(this).hide();
    });
});
hiddenFields.change(function(){
    var elId = $(this).attr("id");
    var removeButt = $("#"+elId+"_remove");
    removeButt.show();
});
' .implode("\r\n",$this->_footJavascript).
//Force validation on load if already posted
($this->getIsSubmitted()? 'thisFormEl.valid();' : '')
. '
});
';
        }
        return $s_js;
    }
    /**
     * getJsonFormBuilderOutput()
     *
     * Constructs the JsonFormBuilder source. This is the main processing function.
     * @return string
     * @ignore
     */
    protected function getJsonFormBuilderOutput() {
        //prepare if not already done
        $this->validate();

        //If form is posted and valid, no need to continue output, send email and redirect.
        $s_timerVar = 'jsonFormBuilderTimerVar_' . $this->_id;
        if($this->getIsSubmitted()){
            if (count($this->_invalidElements) === 0) {

                //If form submitted very quickly, assume robot.
                $minimumTimeSecs=2; //was set to 5, but kept tripping it myself when testing basic form. 2 seems to be better.
                $secsSinceFormOpen = time()-$_SESSION[$s_timerVar];
                if($secsSinceFormOpen<$minimumTimeSecs){ $this->spamDetectExit(3); }

                //If form is posted and valid, no need to continue output, send email and redirect.
                $b_result = $this->sendEmails();
                $a_args = array();
                if(!$b_result){
                    $a_args['errors'] = 'mailSending';
                }
                if($this->_redirectDocument){
                    $url = $this->modx->makeUrl($this->_redirectDocument,'',$a_args);
                    $this->modx->sendRedirect($url);
                }
            }
        }else{
            //User has not yet posted, set session variable and track time it took to fill out form.
            $_SESSION[$s_timerVar]=time();
        }
        $s_js = $this->buildFormJavascript();
        $s_form = $this->buildForm();
        if (empty($this->_placeholderJavascript) === false) {
            $this->modx->setPlaceholder($this->_placeholderJavascript, $s_js);
            return $s_form;
        } else {
            return $s_form .
            '<script type="text/javascript">
            // <![CDATA[
            ' . $s_js . '
            // ]]>
            </script>';
        }

    }

    /**
     * output()
     *
     * Output the FormItBuilder source.
     * @return string
     */
    public function output() {
        return $this->getJsonFormBuilderOutput();
    }

}
