<?php

namespace jelgblad\VCard\Exceptions;

class CardinalityException extends \Exception
{
  protected $message = 'Cardinalities are not satisfied';
}
