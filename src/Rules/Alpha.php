<?php

/**
 * @author: Abdul Qureshi. <abdul@easyfundraising.org.uk>
 * 
 * This file has been modified from the original source.
 * See original here:
 *
 * @link: https://github.com/progsmile/request-validator
 */

namespace TheSupportGroup\Common\Validator\Rules;

use TheSupportGroup\Common\Validator\Contracts\Rules\RuleInterface;

class Alpha extends BaseRule implements RuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->getParams()[1]);

        return $this->alpha()->validate($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return 'Field :field: may only contain letters';
    }
}
