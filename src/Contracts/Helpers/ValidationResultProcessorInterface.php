<?php

namespace TheSupportGroup\Common\Validator\Contracts\Helpers;

use TheSupportGroup\Common\Validator\Rules\BaseRule;

interface ValidationResultProcessorInterface
{
    /**
     * Returns messages count.
     *
     * @return int
     */
    public function count();

    /**
     * If such $field contains in.
     *
     * @param $fieldName
     *
     * @return bool
     */
    public function hasErrors($fieldName);

    /**
     * Get flat messages array, or all messages from field.
     *
     * @param string $field
     *
     * @return array
     */
    public function getErrors($field = '');

    /**
     * Get 2d array with fields and messages.
     *
     * @return array
     */
    public function getRawErrors();

    /**
     * For each rule get it's first message.
     *
     * @return array
     */
    public function firsts();

    /**
     * Returns first message from $field or error messages array.
     *
     * @param string $field
     *
     * @return mixed
     */
    public function first($field = '');

    /**
     * Choosing error message: custom or default.
     *
     * @param $instance
     * 
     * @return $this
     */
    public function chooseErrorMessage(BaseRule $instance);

    /**
     * Get messages.
     *
     * @param $fieldName
     *
     * @return FieldsErrorBag
     */
    public function __get($fieldName);
}
