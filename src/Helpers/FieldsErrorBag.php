<?php

namespace TheSupportGroup\Common\Validator\Helpers;

use TheSupportGroup\Common\Validator\Contracts\Helpers\FieldsErrorBagInterface;

class FieldsErrorBag implements FieldsErrorBagInterface
{
    /** @var string magic field name */
    private $fieldName = '';

    /** @var ValidationResultProcessor error bag */
    private $errorBag = null;

    /** @var array out error messages */
    private $errorMessages = [];

    /** @var array messages passed to validator (highest priority) */
    private $userMessages = [];

    /**
     * FieldsErrorBag constructor.
     *
     * @param ValidationResultProcessor $errorBag
     */
    // public function __construct(ValidationResultProcessor $errorBag)
    // {
    //     $this->errorBag = $errorBag;
    // }

    public function setUserMessages($userMessages)
    {
        $this->userMessages = $userMessages;

        return $this;
    }

    public function getUserMessages()
    {
        return $this->userMessages;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Get first message, by query or by rule type.
     *
     * @return bool|string|array
     */
    // public function first()
    // {
    //     return $this->fails() ? $this->errorBag->first($this->fieldName) : false;
    // }

    /**
     * Get fields messages.
     *
     * @return array
     */
    // public function messages()
    // {
    //     return $this->fails() ? $this->errorBag->messages($this->fieldName) : [];
    // }

    /**
     * If result is invalid.
     *
     * @return bool
     */
    // public function fails()
    // {
    //     return $this->errorBag->has($this->fieldName);
    // }

    /**
     * If result is valid.
     *
     * @return bool
     */
    // public function passes()
    // {
    //     return !$this->fails();
    // }

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
     * Setting up magic field.
     *
     * @param $fieldName
     *
     * @return $this
     */
    public function setField($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }
}
