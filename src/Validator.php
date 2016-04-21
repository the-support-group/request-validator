<?php

/**
 * @author: Abdul Qureshi. <abdul@easyfundraising.org.uk>
 * 
 * This file has been modified from the original source.
 * See original here:
 *
 * @link: https://github.com/progsmile/request-validator
 */

namespace TheSupportGroup\Common\Validator;

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Common\Validator\Contracts\Helpers\RulesFactoryInterface;
use TheSupportGroup\Common\Validator\Contracts\Helpers\ValidationResultProcessorInterface;
use TheSupportGroup\Common\Validator\Rules\BaseRule;

final class Validator
{
    /**
     * @var ValidationResultProcessor
     */
    private static $ValidationResultProcessor = null;

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

    /**
     * Rules factory.
     */
    private $rulesFactory;

    /**
     * @param ValidationProviderInterface $validationProvider
     * @param ValidationResultProcessorInterface $validationResultProcessor
     * @param RulesFactoryInterface $rulesFactory
     * @param array $inputData
     * @param array $rules
     * @param array $errorMessages
     */
    public function __construct(
        ValidationProviderInterface $validationProvider,
        ValidationResultProcessorInterface $validationResultProcessor,
        RulesFactoryInterface $rulesFactory,
        array $inputData,
        array $rules = [],
        array $errorMessages = []
    ) {
        $this->rules = $rules;
        $this->inputData = $inputData;
        $this->validationProvider = $validationProvider;
        $this->validationResultProcessor = $validationResultProcessor;
        $this->validationResultProcessor->fieldsErrorBag->setUserMessages($errorMessages);
        $this->rulesFactory = $rulesFactory;
    }

    /**
     * Validate input data against the rules provided.
     */
    public function validate()
    {
        return $this->make(
            $this->inputData,
            $this->rules
        );
    }

    /**
     * Make validation.
     *
     * @param array $data user request data
     * @param array $rules validation rules
     * @param array $userMessages custom error messages
     *
     * @return ValidatorRule
     */
    private function make(
        array $data,
        array $rules
    ) {
        $data = $this->prepareData($data);
        $rules = $this->prepareRules($rules);

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
                    $ruleValue = implode(self::$ruleParamSeparator, array_slice(
                        $ruleNameParam,
                        1
                    ));
                    //for other params
                } else {
                    $ruleValue = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';
                }

                self::$config[BaseRule::CONFIG_DATA] = $data;
                self::$config[BaseRule::CONFIG_FIELD_RULES] = $fieldRules;

                $ruleInstance = $this->rulesFactory->createRule(
                    $ruleName,
                    self::$config,
                    [
                        $fieldName,                                        // The field name
                        isset($data[$fieldName]) ? $data[$fieldName] : '', // The provided value
                        $ruleValue,                                        // The rule's value
                    ],
                    $this->validationProvider
                );

                if (! $ruleInstance->isValid()) {
                    $this->validationResultProcessor->chooseErrorMessage($ruleInstance);
                }
            }
        }

        return $this->validationResultProcessor;
    }

    /**
     * Prepare user data for validator.
     *
     * @param array $data
     *
     * @return array
     */
    private function prepareData(array $data)
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
    private function prepareRules(array $rules)
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
