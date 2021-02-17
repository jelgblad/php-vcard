# php-vcard
A vCard implementation for PHP

## Install

### Install as PHP-archive (PHAR)
1. Download latest from https://github.com/jelgblad/php-vcard/releases/latest.
2. Copy `vcard.phar` to somwhere in your project directory.
3. Include/require it as you would any other PHP-file: `require 'path/to/vcard.phar';`.

### Install with [Composer](https://getcomposer.org/)
> Not implemented yet.

## Example

```php
use jelgblad\VCard\VCard;

// Create new VCard
$vcard = new VCard();

// Add some properties to VCard
$vcard->createProp('FN', 'Jonas Elgblad');
$vcard->createProp('N', ['Elgblad', 'Jonas']);
$vcard->createProp('URL', 'https://github.com/jelgblad')->createParam('TYPE', 'github');

echo $vcard;
```

See more examples in the [examples directory](examples/).

## API documentation

Read the API documentation [here](documentation.md).
