<?php

namespace TheSupportGroup\Validator\Helpers;

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Validator\Rules\BaseRule;
use TheSupportGroup\Validator\Validator;

class ValidatorFacade
{
    /** @var array messages passed to validator (highest priority) */
    private $userMessages = [];

    /** @var array out error messages */
    private $errorMessages = [];

    /** @var array|null all errors bag */
    private $fieldsErrorBag = null;

    /**
     * @param ValidationProviderInterface $validationProvider
     * @param array $userMessages
     */
    public function __construct(
        ValidationProviderInterface $validationProvider,
        array $userMessages = []
    ) {
        $this->fieldsErrorBag = new FieldsErrorBag($this);
        $this->validationProvider = $validationProvider;
        $this->userMessages = $userMessages;
    }

    /**
     * @param array $inputData
     * @param array $rules
     * @param array $errorMessages
     */
    public function validate(
        array $inputData,
        array $rules = [],
        array $errorMessages = []
    ) {
        return (new Validator(
            $this->validationProvider,
            $inputData,
            $rules,
            $errorMessages
        ))->validate();
    }

    /**
     * Erase all error messages.
     */
    public function clear()
    {
        $this->errorMessages = [];
    }

    /**
     * Add new message.
     *
     * @param $fieldName
     * @param $message
     */
    public function add($fieldName, $message)
    {
        $this->errorMessages[$fieldName][] = $message;
    }

    /**
     * Returns messages count.
     *
     * @return int
     */
    public function count()
    {
        return count($this->errorMessages);
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
        return (bool) count($this->getErrorMessages($fieldName));
    }

    /**
     * Get flat messages array, or all messages from field.
     *
     * @param string $field
     *
     * @return array
     */
    public function getErrorMessages($field = '')
    {
        if ($field) {
            return isset($this->errorMessages[$field]) ? $this->errorMessages[$field] : [];
        }
        $messages = [];
        array_walk_recursive($this->errorMessages, function ($message) use (&$messages) {
            $messages[] = $message;
        });

        return $messages;
    }

    /**
     * Get 2d array with fields and messages.
     *
     * @return array
     */
    public function raw()
    {
        return $this->errorMessages;
    }

    /**
     * For each rule get it's first message.
     *
     * @return array
     */
    public function firsts()
    {
        $messages = [];
        foreach ($this->errorMessages as $fieldsMessages) {
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
        if (isset($this->errorMessages[$field])) {
            $message = reset($this->errorMessages[$field]);
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
     */
    public function chooseErrorMessage(BaseRule $instance)
    {
        list($fieldName, $ruleValue, $ruleParams) = $instance->getParams();
        $ruleErrorFormat = $fieldName.'.'.lcfirst($instance->getRuleName());

        if (isset($this->userMessages[$ruleErrorFormat])) {
            $ruleErrorMessage = $this->userMessages[$ruleErrorFormat];
        } else {
            $ruleErrorMessage = $instance->getMessage();
        }

        $this->add(
            $fieldName,
            strtr(
                $ruleErrorMessage,
                [
                    ':field:' => $fieldName,
                    ':value:' => $ruleValue,
                    ':param:' => $ruleParams,
                ]
            )
        );
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
