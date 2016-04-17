<?php

namespace TheSupportGroup\Validator;

use TheSupportGroup\Validator\Helpers\RulesFactory;
use TheSupportGroup\Validator\Helpers\ValidatorFacade;
use TheSupportGroup\Validator\Rules\BaseRule;

final class Validator
{
    /** @var ValidatorFacade */
    private static $validatorFacade = null;

    /**
     * Use to separate rules.
     *
     * firstname: max(255)|min(8)
     */
    private static $ruleSeparator = '|';

    /**
     * Use to separate field(s) and rule(s).
     *
     * firstname: max(255)
     */
    private static $ruleParamSeparator = ':';

    /**
     * Used to separate multiple field definitions for the same rules.
     *
     * firstname,lastname: max(255)
     */
    private static $multiFieldSeparator = ',';

    /**
     * Provide predefined config.
     */
    private static $config = [];

    public function __construct(array $inputData, array $rules = [], array $errorMessages = [])
    {
        $this->rules = $rules;
        $this->errorMessages = $errorMessages;
        $this->inputData = $inputData;
    }

    public function validate()
    {
        return self::make($this->inputData, $this->rules, $this->errorMessages);
    }

    /**
     * Make validation.
     *
     * @param array $data         user request data
     * @param array $rules        validation rules
     * @param array $userMessages custom error messages
     *
     * @return ValidatorFacade
     */
    private static function make(array $data, array $rules, array $userMessages = [])
    {
        self::$validatorFacade = new ValidatorFacade($userMessages);
        $data = self::prepareData($data);
        $rules = self::prepareRules($rules);

        foreach ($rules as $fieldName => $fieldRules) {
            $fieldName = trim($fieldName);
            $fieldRules = trim($fieldRules);

            if (!$fieldRules) {
                //no rules
                throw new Excpetion('No rules provided.');
            }

            $groupedRules = explode(self::$ruleSeparator, $fieldRules);

            foreach ($groupedRules as $concreteRule) {
                $ruleNameParam = explode(self::$ruleParamSeparator, $concreteRule);
                $ruleName = $ruleNameParam[0];

                // For date/time validators.
                if (count($ruleNameParam) >= 2) {
                    $ruleValue = implode(self::$ruleParamSeparator, array_slice($ruleNameParam, 1));
                    //for other params
                } else {
                    $ruleValue = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';
                }

                self::$config[BaseRule::CONFIG_DATA] = $data;
                self::$config[BaseRule::CONFIG_FIELD_RULES] = $fieldRules;

                $ruleInstance = RulesFactory::createRule($ruleName, self::$config, [
                    $fieldName,                                        // The field name
                    isset($data[$fieldName]) ? $data[$fieldName] : '', // The provided value
                    $ruleValue,                                        // The rule's value
                ]);

                if (!$ruleInstance->isValid()) {
                    self::$validatorFacade->chooseErrorMessage($ruleInstance);
                }
            }
        }

        return self::$validatorFacade;
    }

    /**
     * Prepare user data for validator.
     *
     * @param array $data
     *
     * @return array
     */
    private static function prepareData(array $data)
    {
        $newData = [];

        foreach ($data as $paramName => $paramValue) {
            if (is_array($paramValue)) {
                foreach ($paramValue as $newKey => $newValue) {
                    $newData[trim($paramName).'['.trim($newKey).']'] = trim($newValue);
                }
            } else {
                $newData[trim($paramName)] = trim($paramValue);
            }
        }

        return $newData;
    }

    /**
     * Merges all field's rules into one
     * if you have elegant implementation, you are welcome.
     *
     * @param array $rules
     *
     * @return array
     */
    private static function prepareRules(array $rules)
    {
        $mergedRules = [];

        foreach ($rules as $ruleFields => $ruleConditions) {
            if (strpos($ruleFields, self::$multiFieldSeparator) !== false) {
                foreach (explode(self::$multiFieldSeparator, $ruleFields) as $fieldName) {
                    $fieldName = trim($fieldName);
                    if (!isset($mergedRules[$fieldName])) {
                        $mergedRules[$fieldName] = $ruleConditions;
                    } else {
                        $mergedRules[$fieldName] .= self::$ruleSeparator.$ruleConditions;
                    }
                }
            } else {
                if (!isset($mergedRules[$ruleFields])) {
                    $mergedRules[$ruleFields] = $ruleConditions;
                } else {
                    $mergedRules[$ruleFields] .= self::$ruleSeparator.$ruleConditions;
                }
            }
        }

        $finalRules = [];

        // Remove duplicated rules.
        foreach ($mergedRules as $newRule => $rule) {
            $finalRules[$newRule] = implode(self::$ruleSeparator, array_unique(explode(self::$ruleSeparator, $rule)));
        }

        return $finalRules;
    }
}
