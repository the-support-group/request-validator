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
     * Set custom error messages.
     * 
     * @param array $userMessages The custom user messages.
     */
    public function setUserMessages($userMessages)
    {
        $this->userMessages = $userMessages;

        return $this;
    }

    /**
     * Get custom error messages.
     * 
     * @return array The user messages set.
     */
    public function getUserMessages()
    {
        return $this->userMessages;
    }

    /**
     * setErrorMessages
     *
     * @return $this
     */
    public function setErrorMessages(array $errorMessages)
    {
        $this->errorMessages = $errorMessages;

        return $this;
    }

    /**
     * Get error messages.
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
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

        return $this;
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

    /**
     * Get the fieldName set.
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
}
