<?php

namespace TheSupportGroup\Common\ValidatorTests\Rules;

use PHPUnit_Framework_TestCase;
use TheSupportGroup\Common\ValidationInterop\ValidationProviderInterface;

class RulesTest extends PHPUnit_Framework_TestCase
{
    public function testValidateAllRules()
    {
        $namespace = 'TheSupportGroup\\Common\\Validator\\Rules\\';
        $rulesDir = __DIR__ . '/../../../src/Rules';
        $ruleConfig = [];

        $files = scandir($rulesDir);
        $filesToProcess = array_diff($files, ['.', '..', 'BaseRule.php']);
        $validationProviderMock = $this->getMockBuilder(ValidationProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        foreach ($filesToProcess as $file) {
            $class = basename($file, '.php');

            $qualifiedClassNamespace = $namespace . $class;

            $rule = new $qualifiedClassNamespace($ruleConfig, $validationProviderMock);

            // Check that these methods exist on these rules.
            $this->assertTrue(method_exists($rule, 'isValid'));
            $this->assertTrue(method_exists($rule, 'getMessage'));
            $this->assertInternalType('string', $rule->getMessage());
        }
    }
}
