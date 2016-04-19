<?php

namespace TheSupportGroup\Common\Validator\Contracts\Helpers;

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

interface RulesFactoryInterface
{
    /**
     * @param $ruleName
     * @param $config
     * @param $params
     * @param ValidationProviderInterface $validationProvider
     *
     * @return mixed
     */
    public static function createRule(
        $ruleName,
        $config,
        $params,
        ValidationProviderInterface $validationProvider
    );
}