<?php

namespace jelgblad\VCard\Tests;

use PHPUnit\Framework\TestCase;
use jelgblad\VCard\VCard;
use jelgblad\VCard\VCardProperty;
use jelgblad\VCard\Exceptions\CardinalityException;

final class VCardTest extends TestCase
{
    public function testCanBeCreatedWithDefaultOptions(): void
    {
        $this->assertInstanceOf(
            VCard::class,
            new VCard()
        );
    }


    public function testCanBeCreatedWithVersion3(): void
    {
        new VCard(['version' => '3.0']);

        $this->assertTrue(true);
    }


    public function testCanBeCreatedWithVersion4(): void
    {
        new VCard(['version' => '4.0']);

        $this->assertTrue(true);
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

        $this->expectException(CardinalityException::class);

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
