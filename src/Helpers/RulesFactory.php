<?php

namespace TheSupportGroup\Common\Validator\Helpers;

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
    public static function createRule(
        $ruleName,
        $config,
        $params,
        $validationProvider
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
