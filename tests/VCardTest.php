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


    public function testCanBeCreatedWithVersion2(): void
    {
        new VCard(['version' => '2.1']);

        $this->assertTrue(true);
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


    public function testCanBeParsed(): void
    {
        $input = '
        BEGIN:VCARD
        VERSION:4.0
        FN:John Doe
        N:Doe;John
        END:VCARD';

        $vcards = VCard::parse($input);

        $this->assertCount(1, $vcards);
        $this->assertEquals('John Doe', $vcards[0]->getProp('FN')->getValue());
    }


    public function testCanBeParsedWithMultipleInputs(): void
    {
        $input = '
        BEGIN:VCARD
        VERSION:4.0
        FN:John Doe
        N:Doe;John
        END:VCARD
        BEGIN:VCARD
        VERSION:3.0
        FN:Jane Doe
        N:Doe;Jane
        END:VCARD';

        $vcards = VCard::parse($input);

        $this->assertCount(2, $vcards);
        $this->assertEquals('John Doe', $vcards[0]->getProp('FN')->getValue());
        $this->assertEquals('Jane Doe', $vcards[1]->getProp('FN')->getValue());
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


    public function testCanGetProp(): void
    {
        $vcard = new VCard();

        $vcard->createProp('TEL', 'example value 1');
        $vcard->createProp('EMAIL', 'example value 2');
        $vcard->createProp('EMAIL', 'example value 3');

        $this->assertEquals('example value 2', $vcard->getProp('EMAIL')->getValue());
        $this->assertEquals('example value 3', $vcard->getProp('EMAIL', 1)->getValue());
    }


    public function testCanGetAllProps(): void
    {
        $vcard = new VCard();

        $vcard->createProp('TEL', 'example value 1');
        $vcard->createProp('EMAIL', 'example value 2');
        $vcard->createProp('EMAIL', 'example value 3');

        $this->assertCount(3, $vcard->getProps());
    }


    public function testCanGetPropsOfType(): void
    {
        $vcard = new VCard();

        $vcard->createProp('TEL', 'example value 1');
        $vcard->createProp('EMAIL', 'example value 2');
        $vcard->createProp('EMAIL', 'example value 3');

        $this->assertCount(2, $vcard->getProps('EMAIL'));
    }
}
