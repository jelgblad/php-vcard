<?php

namespace jelgblad\VCard;

class VCardPropertyParameter
{

  static function escape_value(string $value): string
  {

    // https://tools.ietf.org/html/rfc6350#section-5

    $value = mb_ereg_replace('\x{0022}', '', $value);

    if (
      mb_strpos($value, ':') ||
      mb_strpos($value, ';') ||
      mb_strpos($value, ',')
    ) {
      return '"' . $value . '"';
    }

    return $value;
  }



  public $property;
  public $type;
  public $values = array();



  public static function parse(VCardProperty $prop, string $input): VCardPropertyParameter
  {
    // Trim input
    $input = trim($input);

    $parts = explode('=', $input, 2);

    $values = [];

    if (isset($parts[1])) {
      $values = self::_paseSplitValues($parts[1]);
    }
  
    // Crate VCardPropertyParameter
    $param = new VCardPropertyParameter($prop, $parts[0], $values);

    return $param;
  }



  private static function _paseSplitValues(string $input): array
  {
    $chars = str_split($input);

    $inQuotes = false;

    $parts = [];
    $buffer = [];

    // Loop chars and find index where value begins
    for ($i = 0; $i < count($chars); $i++) {

      $char = $chars[$i];

      if ($inQuotes && $char === '"' && !($i - 1 >= 0  && $chars[$i - 1] === '\\')) {
        $inQuotes = false;
      } else if (!$inQuotes && $char === '"' && !($i - 1 >= 0 && $chars[$i - 1] === '\\')) {
        $inQuotes = true;
      }

      if (!$inQuotes && $char === ',' && !($i - 1 >= 0 && $chars[$i - 1] === '\\')) {
        $parts[] = join('', $buffer);
        $buffer = [];
      } else {
        $buffer[] = $char;
      }
    }

    $parts[] = join('', $buffer);
    
    $parts = array_map(function ($part) {

      // Remove quotes around string
      if (substr($part, 0, 1) === '"' && substr($part, strlen($part)-1, 1) === '"') {
        $part = substr($part, 1, strlen($part)-2);
      }

      return $part;
    }, $parts);

    return $parts;
  }



  function __construct(VCardProperty $property, string $param_type, $values = NULL)
  {

    $this->property = $property;
    $this->type = strtoupper($param_type);

    if (isset($values)) {
      if (!is_array($values)) $values = array($values);

      foreach ($values as $value) {
        $this->addValue($value);
      }
    }
  }



  public function __toString()
  {
    return $this->getString();
  }



  /**
   * Add value to property parameter
   *
   * @param string $value
   * @return void
   */
  public function addValue(string $value)
  {

    // TODO: throw error if not string

    // if (mb_strpos($value, '"')) {
    //   throw new \Exception("VCard: Quotation mark ('\"') character not allowed in parameter value.");
    // }

    array_push($this->values, $value);
  }


  /**
   * Get single value from vCard property parameter.
   * 
   * @param   int|null    $index   Index of value to get.
   * 
   * @return string
   */
  public function getValue(int $index = 0): string
  {
    $values = $this->values;

    return count($values) > $index ? $values[$index] : '';
  }


  /**
   * Get values from vCard property parameter.
   * 
   * @return string[]
   */
  public function getValues(): array
  {
    return $this->values;
  }



  /**
   * Get property parameter as formatted string
   *
   * @return string
   */
  public function getString(): string
  {

    $buffer = "";

    $buffer .= ";" . $this->type;

    for ($i = 0; $i < $num_of_values = count($this->values); $i++) {

      if ($i === 0) $buffer .= "=";

      // Add escaped value
      $buffer .= self::escape_value($this->values[$i]);

      // Add delimiter
      if ($i < $num_of_values - 1) $buffer .= ",";
    }

    return $buffer;
  }
}
