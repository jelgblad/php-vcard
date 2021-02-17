<?php

namespace jelgblad\VCard\Tests;

use PHPUnit\Framework\TestCase;
use jelgblad\VCard\VCard;
use jelgblad\VCard\VCardProperty;

final class VCardTest extends TestCase
{
    public function testCanBeCreatedWithDefaultOptions(): void
    {
        $this->assertInstanceOf(
            VCard::class,
            new VCard()
        );
    }


    public function testCannotBeCreatedWithInvalidVersion(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new VCard(['version' => 'invalid']);
    }


    public function testCanAddCustomTypeWithPrefix(): void
    {
        $vcard = new VCard();
        $prop = new VCardProperty($vcard, 'X-MYTYPE', 'value'); // 'X-MYTYPE' type name is not defined in spec.

        $vcard->addProp($prop);

        $this->assertTrue(true);
    }


    public function testCanAddCustomTypeWithCustomPrefix(): void
    {
        $vcard = new VCard([
            'custom_proptype_prefix' => 'CUSTOM-'
        ]);
        $prop = new VCardProperty($vcard, 'CUSTOM-MYTYPE', 'value'); // 'CUSTOM-MYTYPE' type name is not defined in spec.

        $vcard->addProp($prop);

        $this->assertTrue(true);
    }


    public function testCannotOutputWithoutRequiredProps(): void
    {
        $vcard = new VCard();

        $this->expectException(\Exception::class);

        $vcard->getString();
    }


    public function testCanOutputWithoutRequiredPropsIfEnforceDisabled(): void
    {
        $vcard = new VCard([
            'enforce_cardinality' => FALSE
        ]);

        $vcard->getString();

        $this->assertTrue(true);
    }
}
