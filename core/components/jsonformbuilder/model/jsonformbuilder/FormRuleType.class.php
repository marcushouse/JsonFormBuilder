<?php
/**
 * Contains RuleType constants.
 * @package JsonFormBuilder
 */

/**
 * FormRuleType
 * 
 * A simple class to define strings as constants for rule types to make them more easily read.
 * @package JsonFormBuilder
 */
class FormRuleType extends JsonFormBuilderCore{
	const fieldMatch = 'fieldMath';
	const required = 'required';
	const email = 'email';
	const numeric = 'numeric';
	const minimumLength = 'minimumLength';
	const maximumLength = 'maximumLength';
	const minimumValue = 'minimumValue';
	const maximumValue = 'maximumValue';
	const file = 'file';
	const date = 'date';
}

?>
