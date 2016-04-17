<?php

namespace TheSupportGroup\Validator\Helpers;

class RulesFactory
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

        $class = 'TheSupportGroup\\Validator\\Rules\\'.$ruleName;
        $ruleInstance = new $class($config, $validationProvider);
        $ruleInstance->setParams($params);

        return $ruleInstance;
    }
}
