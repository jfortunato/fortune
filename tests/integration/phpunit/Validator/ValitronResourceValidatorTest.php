<?php

namespace test\Fortune\Validator;

use test\Fortune\TestCase;
use test\Fortune\Validator\fixtures\ValitronResourceValidator;

class ValitronResourceValidatorTest extends TestCase
{
    public function setUp()
    {
        $this->validator = new ValitronResourceValidator;
    }

    public function testCanPassSimpleValidation()
    {
        $this->validator->addRule('required', 'foo');

        $this->assertTrue($this->validator->validate(['foo' => 'bar']));
    }

    public function testCanFailSimpleValidation()
    {
        $this->validator->addRule('required', 'foo');

        $this->assertFalse($this->validator->validate(['foo' => '']));
    }
}
