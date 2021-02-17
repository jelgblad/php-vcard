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
    $value = mb_ereg_replace('\x{000A}', '\n', $value);

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

    // Illegal prop_types
    $illegal_prop_types = [
      'BEGIN',
      'END',
      'VERSION'
    ];

    // Get options
    $opt_custom_proptype_prefix = $this->vcard->options['custom_proptype_prefix'];

    // Check if prop type is illegal
    if (in_array($this->type, $illegal_prop_types)) {
      throw new \Exception("VCard: Can't write property instance of reserved type '" . $this->type . "'.");
    }

    // Check if prop type is valid or prefixed
    if (
      !isset($this->vcard->schema[$this->type]) &&
      substr($this->type, 0, strlen($opt_custom_proptype_prefix)) !== $opt_custom_proptype_prefix
    ) {
      throw new \Exception("VCard: Can't write property with unknown type '" . $this->type . "'. Custom property types must be prefixed with '${opt_custom_proptype_prefix}'.");
    }

    // Skip empty prop
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

    // Default value delimiter
    $delimiter = ' ';

    if (array_key_exists($this->type, $this->vcard->schema)) {

      // Type definition
      $schema_type = $this->vcard->schema[$this->type];

      // Type-specific value delimiter
      if (isset($schema_type['delimiter'])) {
        $delimiter = $schema_type['delimiter'];
      }
    }

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
