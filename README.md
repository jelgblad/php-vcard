# php-vcard
A vCard implementation for PHP

## Install

### Install with [Composer](https://getcomposer.org/)

```
composer require jelgblad/vcard
```

### Install as PHP-archive (PHAR)
1. Download latest from https://github.com/jelgblad/php-vcard/releases/latest.
2. Copy `vcard.phar` to somwhere in your project directory.
3. Include/require it as you would any other PHP-file: `require 'path/to/vcard.phar';`.

## Example

**Write a vCard**
```php
use jelgblad\VCard\VCard;

// Create new VCard
$vcard = new VCard();

// Add some properties to VCard
$vcard->createProp('FN', 'Jonas Elgblad');
$vcard->createProp('N', ['Elgblad', 'Jonas']);
$vcard->createProp('URL', 'https://github.com/jelgblad')->createParam('TYPE', 'github');
$vcard->createProp('EXPERTISE', 'PHP')->createParam('LEVEL', 'moderate');

echo $vcard;
```

**Parse a vCard**
```php
use jelgblad\VCard\VCard;

$input = '
  BEGIN:VCARD
  VERSION:4.0
  FN:John Doe
  N:Doe;John
  END:VCARD';

// Since .vcf-files can contain more than one vCard, VCard::parse() returns an array of all the parsed vCards.
$vcards = VCard::parse($input);

foreach ($vcards as $i => $vcard) {
  printf("vCard %d:\n", $i + 1);
  printf("Name: %s\n", $vcard->getProp('FN')->getValue());
}
```

See more examples in the [examples directory](examples/).

## API documentation

Read the API documentation [here](docs/documentation.md).
