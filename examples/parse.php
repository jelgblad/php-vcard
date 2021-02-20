<?php

// Example using PHAR-bundle, get latest from https://github.com/jelgblad/php-vcard/releases
require 'vcard.phar';

use jelgblad\VCard\VCard;

$vcard = VCard::parse(file_get_contents( __DIR__ . '/sample.vcf'));

echo $vcard;
