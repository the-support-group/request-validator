<?php

/**
 * @author: Abdul Qureshi. <abdul@easyfundraising.org.uk>
 * 
 * This file has been modified from the original source.
 * See original here:
 *
 * @link: https://github.com/progsmile/request-validator
 */
namespace TheSupportGroup\Common\Validator\Helpers;

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Common\Validator\Contracts\Helpers\FieldsErrorBagInterface;
use TheSupportGroup\Common\Validator\Contracts\Helpers\ValidationResultProcessorInterface;
use TheSupportGroup\Common\Validator\Rules\BaseRule;
use TheSupportGroup\Common\Validator\Validator;

class ValidationResultProcessor implements ValidationResultProcessorInterface
{
    /** @var array|null all errors bag */
    public $fieldsErrorBag = null;

    /**
     * @param ValidationProviderInterface $validationProvider
     * @param array $userMessages
     */
    public function __construct(
        FieldsErrorBagInterface $fieldsErrorBag,
        array $userMessages = []
    ) {
        $this->fieldsErrorBag = $fieldsErrorBag;
        $this->fieldsErrorBag->setUserMessages($userMessages);
    }

    /**
     * Returns messages count.
     *
     * @return int
     */
    public function count()
    {
        return count($this->fieldsErrorBag->getErrorMessages());
    }

    /**
     * If such $field contains in.
     *
     * @param $fieldName
     *
     * @return bool
     */
    public function hasErrors($fieldName)
    {
        return (bool) count($this->fieldsErrorBag->getErrorMessages($fieldName));
    }

    /**
     * Get flat messages array, or all messages from field.
     *
     * @param string $field
     *
     * @return array
     */
    public function getErrors($field = '')
    {
        if ($field) {
            return isset($this->fieldsErrorBag->getErrorMessages()[$field]) ? $this->fieldsErrorBag->getErrorMessages()[$field] : [];
        }

        $messages = [];

        // Pass in the variable.
        $errorMessages = $this->fieldsErrorBag->getErrorMessages();
        array_walk_recursive($errorMessages, function ($message) use (&$messages) {
            $messages[] = $message;
        });

        return $messages;
    }

    /**
     * Get 2d array with fields and messages.
     *
     * @return array
     */
    public function getRawErrors()
    {
        return $this->fieldsErrorBag->getErrorMessages();
    }

    /**
     * For each rule get it's first message.
     *
     * @return array
     */
    public function firsts()
    {
        $messages = [];
        foreach ($this->fieldsErrorBag->getErrorMessages() as $fieldsMessages) {
            foreach ($fieldsMessages as $fieldMessage) {
                $messages[] = $fieldMessage;
                break;
            }
        }

        return $messages;
    }

    /**
     * Returns first message from $field or error messages array.
     *
     * @param string $field
     *
     * @return mixed
     */
    public function first($field = '')
    {
        if (isset($this->fieldsErrorBag->getErrorMessages()[$field])) {
            $message = reset($this->fieldsErrorBag->getErrorMessages()[$field]);
        } else {
            $firstMessages = $this->firsts();
            $message = reset($firstMessages);
        }

        return $message;
    }

    /**
     * Choosing error message: custom or default.
     *
     * @param $instance
     * 
     * @return $this
     */
    public function chooseErrorMessage(BaseRule $instance)
    {
        list($fieldName, $ruleValue, $ruleParams) = $instance->getParams();
        $ruleErrorFormat = $fieldName.'.'.lcfirst($instance->getRuleName());

        if (isset($this->fieldsErrorBag->getUserMessages()[$ruleErrorFormat])) {
            $ruleErrorMessage = $this->fieldsErrorBag->getUserMessages()[$ruleErrorFormat];
        } else {
            $ruleErrorMessage = $instance->getMessage();
        }

        $this->fieldsErrorBag->add(
            $fieldName,
            strtr(
                $ruleErrorMessage,
                [
                    ':field:' => $fieldName,
                    ':rule:' => $ruleValue,
                    ':param:' => $ruleParams,
                ]
            )
        );

        return $this;
    }

    /**
     * Get messages.
     *
     * @param $fieldName
     *
     * @return FieldsErrorBag
     */
    public function __get($fieldName)
    {
        return $this->fieldsErrorBag->setField($fieldName);
    }
}
