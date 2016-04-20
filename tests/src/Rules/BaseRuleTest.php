<?php

namespace TheSupportGroup\Common\ValidatorTests\Rules;

use PHPUnit_Framework_TestCase;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;
use TheSupportGroup\Common\Validator\Rules\BaseRule;
use TheSupportGroup\Common\Validator\Helpers\ValidationResultProcessor;

class BaseRuleTest extends PHPUnit_Framework_TestCase
{
    /**
     * Validation provider mock.
     */
    private $validationProviderMock = null;

    /**
     * The object to be tested.
     */
    private $testObject;

    /**
     * Set up the testing object.
     */
    public function setup()
    {
        $validationResult = true;

        $this->validationProviderMock = $this->getMock(ValidationProviderInterface::class);

        $this->validationProviderMock->expects($this->any())
            ->method('rule')
            ->will($this->returnSelf());

        $this->validationProviderMock->expects($this->any())
            ->method('validate')
            ->will($this->returnValue($validationResult));

        $this->testObject = $this->getMockBuilder(BaseRule::class)
            ->setConstructorArgs([
                ['fieldRules' => 'required'],
                $this->validationProviderMock
            ])
            ->setMockClassName('BaseRuleTestClass')
            ->getMockForAbstractClass();
    }

    /**
     * test_Call Test that __call executes as expected.
     */
    public function test_Call()
    {
        // Prepare / Mock
        $params = ['I am the params array'];

        $this->validationProviderMock->expects($this->once())
            ->method('getMappedMethod')
            ->with('randomizer')
            ->will($this->returnArgument(0));
        $this->validationProviderMock->expects($this->once())
            ->method('rule')
            ->with('randomizer', [$params])
            ->will($this->returnSelf());
    
        // Execute
        $result = $this->testObject->randomizer($params);
    
        // Assert Result
        $this->assertInstanceOf(BaseRule::class, $result);
    }

    public function testValidate()
    {
        $value = 'good one';

        $this->validationProviderMock->expects($this->once())
            ->method('validate')
            ->with($value)
            ->will($this->returnSelf());

        $result = $this->testObject->validate($value);

        // Assert Result
        $this->assertTrue($result);
    }

    public function testGetConfigWithNull()
    {
        $type = '';

        $result = $this->testObject->getConfig($type);

        // Assert Result
        $this->assertEquals([], $result);
    }

    public function testGetConfigWithoutType()
    {
        $result = $this->testObject->getConfig();

        // Assert Result
        $this->assertEquals(['fieldRules' => 'required'], $result);
    }

    public function testHasRule()
    {
        $rule = 'alpha';

        $result = $this->testObject->hasRule($rule);

        // Assert Result
        $this->assertFalse($result);
    }

    public function testSetGetParams()
    {
        $params = ['good' => 'one'];

        $result = $this->testObject->setParams($params);

        // Assert Result
        $this->assertInstanceOf(BaseRule::class, $result);
        $this->assertEquals($params, $this->testObject->getParams());
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
        $this->assertEquals('BaseRuleTestClass', $result);
    }
}
