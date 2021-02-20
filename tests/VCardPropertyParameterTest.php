<?php

namespace jelgblad\VCard\Tests;

use PHPUnit\Framework\TestCase;
use jelgblad\VCard\VCard;
use jelgblad\VCard\VCardProperty;
use jelgblad\VCard\VCardPropertyParameter;

final class VCardPropertyParameterTest extends TestCase
{
    protected function setUp(): void
    {
        $this->vcard = new VCard();
        $this->property = new VCardProperty($this->vcard, 'TYPE', 'val');
    }


    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            VCardPropertyParameter::class,
            new VCardPropertyParameter($this->property, 'TYPE', 'val')
        );
    }


    public function testCanBeParsedWithoutValue(): void
    {
        $input = 'PARAM';

        $param = VCardPropertyParameter::parse($this->property, $input);

        $this->assertEquals('PARAM', $param->type);
        $this->assertEmpty($param->getValue());
    }


    public function testCanBeParsedWithSingleValue(): void
    {
        $input = 'PARAM=value';

        $param = VCardPropertyParameter::parse($this->property, $input);

        $this->assertEquals('PARAM', $param->type);
        $this->assertEquals('value', $param->getValue());
    }


    public function testCanBeParsedWithMultipleValues(): void
    {
        $input = 'PARAM=value1,value2,value3';

        $param = VCardPropertyParameter::parse($this->property, $input);

        $values = $param->getValues();

        $this->assertEquals('PARAM', $param->type);
        $this->assertCount(3, $values);
        $this->assertEquals('value1', $values[0]);
        $this->assertEquals('value2', $values[1]);
        $this->assertEquals('value3', $values[2]);
    }


    public function testCanBeParsedWithValueInQuotes(): void
    {
        $input = 'PARAM="value, in quotes",value2';

        $param = VCardPropertyParameter::parse($this->property, $input);

        $values = $param->getValues();

        $this->assertEquals('PARAM', $param->type);
        $this->assertCount(2, $values);
        $this->assertEquals('value, in quotes', $values[0]);
        $this->assertEquals('value2', $values[1]);
    }


    public function testCanGetEmptyValue(): void
    {
        $param = $this->property->createParam('PARAM1');

        $this->assertEmpty($param->getValue());
    }


    public function testCanGetValue(): void
    {
        $param = $this->property->createParam('PARAM1', ['example value 1', 'example value 2']);

        $this->assertEquals('example value 1', $param->getValue());
        $this->assertEquals('example value 1', $param->getValue(0));
        $this->assertEquals('example value 2', $param->getValue(1));
    }


    public function testCanGetAllValues(): void
    {
        $param = $this->property->createParam('PARAM1', ['example value 1', 'example value 2']);

        $values = $param->getValues();

        $this->assertCount(2, $values);
        $this->assertEquals('example value 1', $values[0]);
        $this->assertEquals('example value 2', $values[1]);
    }
}
