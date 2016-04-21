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
use TheSupportGroup\Common\Validator\Contracts\Helpers\RulesFactoryInterface;

class RulesFactory implements RulesFactoryInterface
{
    /**
     * @param $ruleName
     * @param $config
     * @param $params
     *
     * @return mixed
     */
    public function createRule(
        $ruleName,
        $config,
        $params,
        ValidationProviderInterface $validationProvider
    ) {
        $ruleName = ucfirst($ruleName);

        if (!file_exists(__DIR__.'/../Rules/'.$ruleName.'.php')) {
            trigger_error('Such rule doesn\'t exists: '.$ruleName, E_USER_ERROR);
        }

        $class = 'TheSupportGroup\\Common\\Validator\\Rules\\'.$ruleName;
        $ruleInstance = new $class($config, $validationProvider);
        $ruleInstance->setParams($params);

        return $ruleInstance;
    }
}
