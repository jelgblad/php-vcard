<?php

namespace jelgblad\VCard\Tests;

use PHPUnit\Framework\TestCase;
use jelgblad\VCard\VCard;
use jelgblad\VCard\VCardProperty;
use jelgblad\VCard\Exceptions\InvalidPropertyException;

final class VCardPropertyTest extends TestCase
{
    protected function setUp(): void
    {
        $this->vcard = new VCard();
    }


    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            VCardProperty::class,
            new VCardProperty($this->vcard, 'PROP', 'VAL')
        );
    }


    public function testOutputEmptyStringIfValueIsEmpty(): void
    {
        $this->vcard->setOption('no_empty_props', TRUE);

        $prop = new VCardProperty($this->vcard, 'SOURCE', '');

        $this->assertEmpty($prop->getString());
    }


    public function testOutputIfValueIsEmptyIfNoEmptyPropsDisabled(): void
    {
        $this->vcard->setOption('no_empty_props', FALSE);

        $prop = new VCardProperty($this->vcard, 'SOURCE', '');

        $this->assertNotEmpty($prop->getString());
    }


    public function testCannotOutputPropOfIllegalType(): void
    {
        $prop = new VCardProperty($this->vcard, 'BEGIN', 'value'); // 'BEGIN' type name is illegal

        $this->expectException(InvalidPropertyException::class);

        $prop->getString();
    }


    public function testCannotOutputCustomPropTypeWithoutPrefix(): void
    {
        $prop = new VCardProperty($this->vcard, 'MYTYPE', 'value'); // 'MYTYPE' type name is not defined in spec.

        $this->expectException(InvalidPropertyException::class);

        $prop->getString();
    }


    public function testCanGetValue(): void
    {
        $prop = $this->vcard->createProp('TEL', ['example value 1', 'example value 2']);

        $this->assertEquals('example value 1', $prop->getValue());
        $this->assertEquals('example value 1', $prop->getValue(0));
        $this->assertEquals('example value 2', $prop->getValue(1));
    }


    public function testCanGetAllValues(): void
    {
        $prop = $this->vcard->createProp('TEL', ['example value 1', 'example value 2']);

        $values = $prop->getValues();

        $this->assertCount(2, $values);
        $this->assertEquals('example value 1', $values[0]);
        $this->assertEquals('example value 2', $values[1]);
    }


    public function testCanGetParam(): void
    {
        $prop = $this->vcard->createProp('TEL', 'example value');
        $prop->createParam('PARAM1', 'example value 1');
        $prop->createParam('PARAM2', 'example value 2');

        $this->assertEquals('example value 1', $prop->getParam('PARAM1')->getValue());
        $this->assertEquals('example value 2', $prop->getParam('PARAM2', 1)->getValue());
    }


    public function testCanGetAllParams(): void
    {   
        $prop = $this->vcard->createProp('TEL', 'example value');
        $prop->createParam('PARAM1', 'example value 1');
        $prop->createParam('PARAM2', 'example value 2');

        $this->assertCount(2, $prop->getParams());
    }
}
