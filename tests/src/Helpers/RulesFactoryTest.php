<?php

use PHPUnit_Framework_TestCase;
use TheSupportGroup\Common\Validator\Rules\BaseRule;
use TheSupportGroup\Common\Validator\Helpers\RulesFactory;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

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
    
        // Execute
        $result = RulesFactory::createRule(
            $ruleName,
            $config,
            $params,
            $validationProviderMock
        );
    
        // Assert Result
        $this->assertInstanceOf(BaseRule::class, $result);
    }
}