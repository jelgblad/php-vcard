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
