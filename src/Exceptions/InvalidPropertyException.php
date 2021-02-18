<?php

namespace jelgblad\VCard\Exceptions;

class InvalidPropertyException extends \Exception
{
  protected $message = 'Invalid or illegal vCard property';
}
