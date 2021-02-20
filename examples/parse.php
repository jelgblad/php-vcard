<?php

// Example using PHAR-bundle, get latest from https://github.com/jelgblad/php-vcard/releases
require 'vcard.phar';

use jelgblad\VCard\VCard;

// Since .vcf-files can contain more than one vCard, VCard::parse() returns an array of all the parsed vCards.
$vcards = VCard::parse(file_get_contents(__DIR__ . '/sample.vcf'));

foreach ($vcards as $i => $vcard) {
  printf("vCard %d:\n", $i + 1);
  printf("Name: %s\n", $vcard->getProp('FN')->getValue());
}

// Will generate following output:
/*
Name: Jonas Elgblad
Name: John Doe
*/
