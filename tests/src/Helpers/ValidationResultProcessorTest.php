<?php

namespace TheSupportGroup\Common\ValidatorTests\Helpers;

use PHPUnit_Framework_TestCase;
use TheSupportGroup\Common\Validator\Helpers;
use TheSupportGroup\Common\Validator\Rules;

class ValidationResultProcessorTest extends PHPUnit_Framework_TestCase
{
    /**
     * The object to be tested.
     */
    private $testObject;

    /**
     * Set up the testing object.
     */
    public function setUp()
    {
        $userMessages = [
            'password' => 'This is just wrong!',
            'fname' => 'Too short man, change your name'
        ];

        $fieldsErrorBagMock = $this->getMockBuilder(Helpers\FieldsErrorBag::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fieldsErrorBagMock->expects($this->once())
            ->method('setUserMessages')
            ->with($userMessages)
            ->will($this->returnSelf());
        $fieldsErrorBagMock->expects($this->any())
            ->method('getUserMessages')
            ->will($this->returnValue($userMessages));

        $this->testObject = new Helpers\ValidationResultProcessor($fieldsErrorBagMock,
            $userMessages
        );
    }

    /**
     * testCount Test that count executes as expected.
     */
    public function testCount()
    {    
        // Execute
        $result = $this->testObject->count();
    
        // Assert Result
        $this->assertEquals(0, $result);

        $this->testObject->fieldsErrorBag->expects($this->any())
            ->method('getErrorMessages')
            ->will($this->returnValue(['one', 'two']));

        // Execute
        $result = $this->testObject->count();
    
        // Assert Result
        $this->assertEquals(2, $result);
    }

    /**
     * testHasErrors Test that hasErrors executes as expected.
     */
    public function testHasErrors()
    {
        // Prepare / Mock
        $fieldName = 'password';
        
        $this->testObject->fieldsErrorBag->expects($this->any())
            ->method('getErrorMessages')
            ->with($fieldName)
            ->will($this->returnValue(['one', 'two']));
    
        // Execute
        $result = $this->testObject->hasErrors($fieldName);
    
        // Assert Result
        $this->assertTrue($result);
    }

    /**
     * testGetErrors Test that getErrors executes as expected.
     */
    public function testGetErrorsWithField()
    {
        // Prepare / Mock
        $field = 'username';
        $usernameMessage = 'bad username';
        
        $this->testObject->fieldsErrorBag->expects($this->atleastOnce())
            ->method('getErrorMessages')
            ->will($this->returnValue([
                'username' => $usernameMessage,
                'two' => 'whatever'
            ]));
    
        // Execute
        $result = $this->testObject->getErrors($field);
    
        // Assert Result
        $this->assertEquals($usernameMessage, $result);
    }

    /**
     * testGetErrors Test that getErrors executes as expected.
     */
    public function testGetErrorsWithoutField()
    {
        // Prepare / Mock
        $errors = [
            'username' => [
                'min' => 'bad username'
            ],
            'two' => [
                'min' => 'whatever'
            ]
        ];

        $expectedErrors = [
            'bad username',
            'whatever'
        ];
        
        $this->testObject->fieldsErrorBag->expects($this->atleastOnce())
            ->method('getErrorMessages')
            ->will($this->returnValue($errors));
    
        // Execute
        $result = $this->testObject->getErrors();
    
        // Assert Result
        $this->assertEquals($expectedErrors, $result);
    }

    /**
     * testRaw Test that raw executes as expected.
     */
    public function testRaw()
    {
        // Prepare / Mock
        $errors = [
            'username' => [
                'min' => 'bad username',
                'eq' => 'something else'
            ],
            'two' => [
                'min' => 'whatever'
            ]
        ];
        
        $this->testObject->fieldsErrorBag->expects($this->atleastOnce())
            ->method('getErrorMessages')
            ->will($this->returnValue($errors));

        // Execute
        $result = $this->testObject->getRawErrors();
    
        // Assert Result
        $this->assertEquals($errors, $result);
    }

    /**
     * testFirsts Test that firsts executes as expected.
     */
    public function testFirsts()
    {
        // Prepare / Mock
        $errors = [
            'username' => [
                'min' => 'bad username',
                'eq' => 'something else'
            ],
            'two' => [
                'min' => 'whatever'
            ]
        ];

        $expectedErrors = [
            'bad username',
            'whatever'
        ];
        
        $this->testObject->fieldsErrorBag->expects($this->atleastOnce())
            ->method('getErrorMessages')
            ->will($this->returnValue($errors));

        // Execute
        $result = $this->testObject->firsts();
    
        // Assert Result
        $this->assertEquals($expectedErrors, $result);
    }

    /**
     * testFirst Test that first executes as expected.
     */
    public function testFirstWithField()
    {
        // Prepare / Mock
        $field = 'username';

        $errors = [
            'username' => [
                'min' => 'bad username',
                'eq' => 'something else'
            ],
            'two' => [
                'min' => 'whatever'
            ]
        ];

        $this->testObject->fieldsErrorBag->expects($this->exactly(2))
            ->method('getErrorMessages')
            ->will($this->returnValue($errors));
    
        // Execute
        $result = $this->testObject->first($field);
    
        // Assert Result
        $this->assertEquals('bad username', $result);
    }

    /**
     * testFirst Test that first executes as expected.
     */
    public function testFirstWithoutField()
    {
        // Prepare / Mock
        $field = 'username';

        $errors = [
            'username' => [
                'min' => 'bad username',
                'eq' => 'something else'
            ],
            'two' => [
                'min' => 'whatever'
            ]
        ];

        $expectedErrors = 'bad username';

        $this->testObject->fieldsErrorBag->expects($this->exactly(2))
            ->method('getErrorMessages')
            ->will($this->returnValue($errors));
    
        // Execute
        $result = $this->testObject->first();
    
        // Assert Result
        $this->assertEquals($expectedErrors, $result);
    }

    /**
     * testChooseErrorMessage Test that chooseErrorMessage executes as expected.
     */
    public function testChooseErrorMessage()
    {
        // Prepare / Mock
        $ruleName = 'min';
        $params = [
            'username',
            $ruleName,
            15
        ];

        $message = 'This is a great message, resulting from the :field: field, rule is :rule:, params are :param:';

        $errors = [
            'username' => [
                'min' => 'bad username',
                'eq' => 'something else'
            ],
            'username.min' => [],
            'two' => [
                'min' => 'whatever'
            ]
        ];

        $this->testObject->fieldsErrorBag->expects($this->any())
            ->method('getErrorMessages')
            ->will($this->returnValue($errors));

        $this->testObject->fieldsErrorBag->expects($this->once())
            ->method('add')
            ->with('username', 'This is a great message, resulting from the username field, rule is min, params are 15')
            ->will($this->returnValue($errors));

        $instanceMock = $this->getMockBuilder(Rules\Alpha::class)
            ->disableOriginalConstructor()
            ->getMock();
        $instanceMock->expects($this->any())
            ->method('getParams')
            ->will($this->returnValue($params));
        $instanceMock->expects($this->any())
            ->method('getMessage')
            ->will($this->returnValue($message));
        $instanceMock->expects($this->any())
            ->method('getRuleName')
            ->will($this->returnValue($ruleName));
    
        // Execute
        $result = $this->testObject->chooseErrorMessage($instanceMock);
    
        // Assert Result
        $this->assertInstanceOf(Helpers\ValidationResultProcessor::class, $result);
    }

    /**
     * test_Get Test that __get executes as expected.
     */
    public function test_Get()
    {
        // Prepare / Mock
        $this->testObject->fieldsErrorBag->expects($this->once())
            ->method('setField')
            ->with('bla')
            ->will($this->returnSelf());
    
        // Execute
        $this->testObject->bla;
    }
}