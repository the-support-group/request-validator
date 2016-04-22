<?php

namespace TheSupportGroup\Common\Validator\Contracts\Helpers;

use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

interface RulesFactoryInterface
{
    /**
     * @param string $ruleName
     * @param array $config
     * @param array $params
     * @param ValidationProviderInterface $validationProvider
     *
     * @return mixed
     */
    public function createRule(
        $ruleName,
        $config,
        $params,
        ValidationProviderInterface $validationProvider
    );
}
