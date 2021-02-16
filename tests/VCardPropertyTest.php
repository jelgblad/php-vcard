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
}
