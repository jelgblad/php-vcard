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



  public static function parse(VCard $vcard, string $input): VCardProperty
  {
    // Trim input
    $input = trim($input);

    // Get index where value starts
    $valueStartIndex = self::_parseGetValueStartIndex($input);

    // Get property definition and property values
    $definition = substr($input, 0, $valueStartIndex - 1);
    $valuesString = substr($input, $valueStartIndex);

    $definitionParts = self::_parseSplit(';', $definition);
    $values = self::_parseSplit(';', $valuesString);

    $propType = $definitionParts[0];
    $paramStrings = array_slice($definitionParts, 1);

    // Crate VCardProperty
    $prop = new VCardProperty($vcard, $propType, $values);

    // Loop params
    foreach ($paramStrings as $paramString) {

      // Parse a property parameter
      $param = VCardPropertyParameter::parse($prop, $paramString);

      $prop->addParam($param);
    }

    return $prop;
  }



  private static function _parseGetValueStartIndex(string $input): int
  {
    $chars = str_split($input);

    $inQuotes = false;

    // Loop chars and find index where value begins
    for ($i = 1; $i < count($chars); $i++) {

      $char = $chars[$i];

      if ($inQuotes && $char === '"' && $chars[$i - 1] !== '\\') {
        $inQuotes = false;
      } else if (!$inQuotes && $char === '"' && $i - 1 >= 0 && $chars[$i - 1] !== '\\') {
        $inQuotes = true;
      }

      if ($inQuotes) {
        continue;
      }

      if ($char === ':' && $chars[$i - 1] !== '\\') {
        // Value starts at next index
        return $i + 1;
      }
    }

    return 0;
  }



  private static function _parseSplit(string $delimiter, string $input): array
  {
    $chars = str_split($input);

    $inQuotes = false;

    $parts = [];
    $buffer = [];

    // Loop chars
    for ($i = 0; $i < count($chars); $i++) {

      $char = $chars[$i];

      if ($inQuotes && $char === '"' && !($i - 1 >= 0  && $chars[$i - 1] === '\\')) {
        $inQuotes = false;
      } else if (!$inQuotes && $char === '"' && !($i - 1 >= 0 && $chars[$i - 1] === '\\')) {
        $inQuotes = true;
      }

      if (!$inQuotes && $char === $delimiter && !($i - 1 >= 0 && $chars[$i - 1] === '\\')) {
        $parts[] = join('', $buffer);
        $buffer = [];
      } else {
        $buffer[] = $char;
      }
    }

    $parts[] = join('', $buffer);

    return $parts;
  }



  function __construct(VCard $vcard, string $prop_type, $values)
  {

    $this->vcard = $vcard;
    $this->type = strtoupper($prop_type);

    if (!empty($values)) {
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
   * @param   string          $param_type   Parameter type name.
   * @param   array|string    $values       Parameter values.
   * 
   * @return VCardPropertyParameter
   */
  public function createParam(string $param_type, $values = NULL): VCardPropertyParameter
  {

    $param = new VCardPropertyParameter($this, $param_type, $values);

    return $this->addParam($param);
  }


  /**
   * Get single value from vCard property.
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
   * Get values from vCard property.
   * 
   * @return string[]
   */
  public function getValues(): array
  {
    return $this->values;
  }


   /**
   * Get single parameter from vCard property.
   * 
   * @param   string    $param_type   Parameter type name.
   * 
   * @return VCardPropertyParameter|null
   */
  public function getParam(string $param_type): VCardPropertyParameter
  {
    return isset($this->parameters[$param_type]) ? $this->parameters[$param_type] : null;
  }


  /**
   * Get parameters from vCard property.
   * 
   * @return VCardPropertyParameter[]
   */
  public function getParams(): array
  {
    return array_values($this->parameters);
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
      throw new Exceptions\InvalidPropertyException("VCard: Can't write property instance of reserved type '" . $this->type . "'.");
    }

    // Check if prop type is valid or prefixed
    if (
      !isset($this->vcard->schema[$this->type]) &&
      substr($this->type, 0, strlen($opt_custom_proptype_prefix)) !== $opt_custom_proptype_prefix
    ) {
      throw new Exceptions\InvalidPropertyException("VCard: Can't write property with unknown type '" . $this->type . "'. Custom property types must be prefixed with '${opt_custom_proptype_prefix}'.");
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
