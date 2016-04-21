<?php

namespace TheSupportGroup\Common\Validator\Contracts\Helpers;

interface FieldsErrorBagInterface
{
    /**
     * Set custom error messages.
     * 
     * @param array $userMessages The custom user messages.
     *
     * @return FieldsErrorBagInterface
     */
    public function setUserMessages($userMessages);

    /**
     * Get custom error messages.
     * 
     * @return array The user messages set.
     */
    public function getUserMessages();

    /**
     * Get error messages.
     */
    public function getErrorMessages();

    /**
     * Erase all error messages.
     * @return void
     */
    public function clear();

    /**
     * Add new message.
     *
     * @param $fieldName
     * @param $message
     *
     * @return FieldsErrorBagInterface
     */
    public function add($fieldName, $message);

    /**
     * Setting up magic field.
     *
     * @param $fieldName
     *
     * @return $this
     */
    public function setField($fieldName);
}