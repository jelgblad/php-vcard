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

    public function testCanCeate(): void
    {
        $this->assertInstanceOf(
            VCardPropertyParameter::class,
            new VCardPropertyParameter($this->property, 'TYPE', 'val')
        );
    }
}
