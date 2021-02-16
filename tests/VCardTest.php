<?php

namespace jelgblad\VCard\Tests;

use PHPUnit\Framework\TestCase;
use jelgblad\VCard\VCard;

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
}
