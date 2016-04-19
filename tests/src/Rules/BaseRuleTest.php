<?php

namespace TheSupportGroup\Common\ValidatorTests\Rules;

use PHPUnit_Framework_TestCase;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Common\Validator\Rules\BaseRule;

class BaseRuleTest extends PHPUnit_Framework_TestCase
{
    
    
    public function setup()
    {
        $validationResult = true;

        $validationProviderMock = $this->getMock(ValidationProviderInterface::class);

        $validationProviderMock->expects($this->any())
            ->method('rule')
            ->will($this->returnSelf());

        $validationProviderMock->expects($this->any())
            ->method('validate')
            ->will($this->returnValue($validationResult));

        $this->testObject = $this->getMockForAbstractClass(BaseRule::class, [
                ['fieldRules' => 'required'],
                $validationProviderMock
            ]);
    }

    /**
     * @expectedException Exception
     */
    public function testThatMagicMethodWorksAsExpected()
    {
        $this->testObject->random();
    }

    /**
     * Magic method works as expected.
     */
    public function testThatMagicMethodWorksAsExpectedWithKnownRule()
    {
        $result = $this->testObject->alpha();

        $this->assertInstanceOf(ValidationProviderInterface::class, $result);
    }

    public function testValidate()
    {
        $rule = '';
        $value = '';

        $result = $this->testObject->validate($rule, $value);

        // Assert Result
        //assert
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testGetConfig()
    {
        $type = '';

        $result = $this->testObject->getConfig($type);

        // Assert Result
        //assert
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testHasRule()
    {
        $rule = 'alpha';

        $result = $this->testObject->hasRule($rule);

        // Assert Result
        //assert
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testSetParams()
    {
        $params = [];

        $result = $this->testObject->setParams($params);

        // Assert Result
        //assert
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testGetParams()
    {
        $result = $this->testObject->getParams();

        // Assert Result
        //assert
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * testGetRuleName Test that getRuleName executes as expected.
     */
    public function testGetRuleName()
    {
        $this->testObject->alpha();
    
        // Execute
        $result = $this->testObject->getRuleName();
    
        // Assert Result
        //assert
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * testGetMessage Test that getMessage executes as expected.
     */
    public function testGetMessage()
    {
        // Prepare / Mock
        //nm
    
        // Execute
        $result = $this->testObject->getMessage();
    
        // Assert Result
        //assert
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * testIsValid Test that isValid executes as expected.
     */
    public function testIsValid()
    {
        // Prepare / Mock
        //nm
    
        // Execute
        $result = $this->testObject->isValid();
    
        // Assert Result
        //assert
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
