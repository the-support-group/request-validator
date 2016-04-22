<?php

namespace TheSupportGroup\Common\ValidatorTests\Helpers;

use PHPUnit_Framework_TestCase;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Common\Validator\Helpers\RulesFactory;
use TheSupportGroup\Common\Validator\Rules\BaseRule;

class RulesFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * testCreateRule Test that createRule executes as expected.
     */
    public function testCreateRule()
    {
        // Prepare / Mock
        $ruleName = 'alpha';
        $config = null;
        $params = null;

        $validationProviderMock = $this->getMockBuilder(ValidationProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rulesFactory = new RulesFactory();

        // Execute
        $result = $rulesFactory->createRule(
            $ruleName,
            $config,
            $params,
            $validationProviderMock
        );

        // Assert Result
        $this->assertInstanceOf(BaseRule::class, $result);
    }
}
