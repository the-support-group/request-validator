<?php

namespace TheSupportGroup\Common\Validator\Contracts\Rules;

interface RuleInterface
{
    /**
     * Check if the value is valid when the rule is applied.
     * @return boolean
     */
    public function isValid();

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage();
}