<?php

// Example autoloader; replace this with your own (or the one included in Composer)
require '_autoload.php';

use jelgblad\VCard\VCard;

// Create new VCard
$vcard = new VCard();

// Add some properties to VCard
$vcard->createProp('FN', 'Jonas Elgblad');
$vcard->createProp('N', ['Elgblad', 'Jonas']);
$vcard->createProp('URL', 'https://github.com/jelgblad')->createParam('TYPE', 'github');

echo $vcard;

// Will generate this output:
/*
BEGIN:VCARD
VERSION:3.0
FN:Jonas Elgblad
N:Elgblad;Jonas
URL;TYPE=github:https://github.com/jelgblad
END:VCARD
*/
