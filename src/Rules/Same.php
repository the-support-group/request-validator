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

class Same extends BaseRule implements RuleInterface
{
    public function isValid()
    {
        $data = $this->getConfig(BaseRule::CONFIG_DATA);

        $input = $this->getParams()[1];
        $value = $this->getParams()[2];

        if (!isset($data[$value])) {
            return false;
        }

        return $this->Equals($data[$value])->validate($input);
    }

    public function getMessage()
    {
        return 'Field :field: should have same value with :rule: field';
    }
}
