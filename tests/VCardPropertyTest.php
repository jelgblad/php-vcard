<?php

namespace jelgblad\VCard\Tests;

use PHPUnit\Framework\TestCase;
use jelgblad\VCard\VCard;
use jelgblad\VCard\VCardProperty;

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

        $this->expectException(\Exception::class);

        $prop->getString();
    }


    public function testCannotOutputCustomPropTypeWithoutPrefix(): void
    {
        $prop = new VCardProperty($this->vcard, 'MYTYPE', 'value'); // 'MYTYPE' type name is not defined in spec.

        $this->expectException(\Exception::class);

        $prop->getString();
    }
}
