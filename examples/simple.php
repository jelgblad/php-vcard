<?php

// Example using PHAR-bundle, get latest from https://github.com/jelgblad/php-vcard/releases
require 'vcard.phar';

use jelgblad\VCard\VCard;

// Create new VCard
$vcard = new VCard();

// Add some properties to VCard
$vcard->createProp('FN', 'Jonas Elgblad');
$vcard->createProp('N', ['Elgblad', 'Jonas']);
$vcard->createProp('URL', 'https://github.com/jelgblad')->createParam('TYPE', 'github');
$vcard->createProp('EXPERTISE', 'PHP')->createParam('LEVEL', 'moderate');

echo $vcard;

// Will generate this output:
/*
BEGIN:VCARD
VERSION:3.0
FN:Jonas Elgblad
N:Elgblad;Jonas
URL;TYPE=github:https://github.com/jelgblad
EXPERTISE;LEVEL=moderate:PHP
END:VCARD
*/
