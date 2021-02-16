<?php

namespace jelgblad\VCard;

class VCardProperty
{

  static function escape_value(string $value): string
  {

    // https://tools.ietf.org/html/rfc6350#section-3.4

    $value = mb_ereg_replace('\x{005C}', '\\\\', $value);
    $value = mb_ereg_replace('\x{002C}', '\,', $value);
    $value = mb_ereg_replace('\x{003B}', '\;', $value);

    return $value;
  }



  public $vcard;
  public $type;
  public $parameters = array();
  public $values = array();



  function __construct(VCard $vcard, string $prop_type, $values)
  {

    $this->vcard = $vcard;
    $this->type = strtoupper($prop_type);

    if (!empty($values)) {
      if (!is_array($values)) $values = array($values);

      foreach ($values as $value) {
        if (!empty($value)) {
          $this->addValue($value);
        }
      }
    }
  }



  public function __toString()
  {
    return $this->getString();
  }



  public function addValue(string $value)
  {

    // TODO: throw error if not string

    array_push($this->values, $value);
  }



  /**
   * Add a parameter instance to the property object.
   * 
   * @param   VCardPropertyParameter  $prop       vCard property parameter object.
   * 
   * @return  VCardPropertyParameter
   */
  public function addParam(VCardPropertyParameter $param): VCardPropertyParameter
  {

    $this->parameters[$param->type] = $param;

    return $param;
  }



  /**
   * Create new parameter instance and add it to the property object.
   * 
   * @param   VCardProperty   $prop       vCard property object.
   * @param   array|string    $values     Parameter values.
   * 
   * @return VCardPropertyParameter
   */
  public function createParam(string $param_type, $values = NULL): VCardPropertyParameter
  {

    $param = new VCardPropertyParameter($this, $param_type, $values);

    return $this->addParam($param);
  }


  /**
   * Get property as formatted string
   *
   * @return string
   */
  public function getString(): string
  {

    if ($this->vcard->options['no_empty_props'] && empty($this->values)) {
      return '';
    }

    $buffer = "";

    $buffer .= $this->type;

    if (!empty($this->parameters)) {
      foreach ($this->parameters as $param) {
        $buffer .= $param->getString();
      }
    }

    // Type definition
    $schema_type = $this->vcard->schema[$this->type];

    // Type value delimiter
    $delimiter = isset($schema_type['delimiter']) ? $schema_type['delimiter'] : ' ';

    for ($i = 0; $i < $num_of_values = count($this->values); $i++) {

      if ($i === 0) $buffer .= ":";

      // Add escaped value
      $buffer .= self::escape_value($this->values[$i]);

      // Add delimiter
      if ($i < $num_of_values - 1) $buffer .= $delimiter;
    }

    $buffer .= "\n";

    return $buffer;
  }
}
