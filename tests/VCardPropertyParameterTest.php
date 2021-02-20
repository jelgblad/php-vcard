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
