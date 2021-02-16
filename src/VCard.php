<?php

namespace jelgblad\VCard;

class VCard
{

  const VCARD_3 = '3.0';      // https://tools.ietf.org/html/rfc2425
                              // https://tools.ietf.org/html/rfc2426
  const VCARD_4 = '4.0';      // https://tools.ietf.org/html/rfc6350

  // TODO: Add 
  const VCARD_VERSIONS = [

    /* vCard version schemas in ascending order.
       * New schemas inherits from previous.
       * NULL unsets the property
       */

    self::VCARD_3 => [
      'SOURCE' => [],
      'NAME' => [],
      'FN' => [
        'required' => TRUE
      ],
      'N' => [
        'required' => TRUE,
        'delimiter' => ';'
      ],
      'NICKNAME' => [],
      'PHOTO' => [],
      'BDAY' => [],
      'ADR' => [
        'delimiter' => ';'
      ],
      'LABEL' => [],
      'TEL' => [],
      'EMAIL' => [],
      'MAILER' => [],
      'TZ' => [],
      'GEO' => [],
      'TITLE' => [],
      'ROLE' => [],
      'LOGO' => [],
      'AGENT' => [],
      'ORG' => [],
      'CATEGORIES' => [],
      'NOTE' => [],
      'PRODID' => [],
      'REV' => [],
      'SORT-STRING' => [],
      'SOUND' => [],
      'URL' => [],
      'UID' => [],
      'CLASS' => [],
      'KEY' => []
    ],

    // self::VCARD_4 => [
    //     'FN' => [],
    //     'NAME' => NULL,
    //     'MAILER' => NULL,
    //     'LABEL' => NULL,
    //     'CLASS' => NULL,
    //     'KIND' => [],
    //     'GENDER' => [],
    //     'LANG' => [],
    //     'ANNIVERSARY' => [],
    //     'XML' => [],
    //     'CLIENTPIDMAP' => []
    // ]
  ];

  static $default_options = [
    'version'                   => self::VCARD_3,   // vCard version 3.0
    'no_empty_props'            => TRUE,            // Do not write properties to output without at least one value
    'enforce_required_props'    => TRUE,            // Throw error, when outputting, if required property types are missing
    'custom_proptype_prefix'    => 'X-'             // Allowed prefix for non-standard types
  ];



  // private $version;
  public $options;
  public $schema;
  private $properties = array();



  private static function getSchema(string $version)
  {

    // Merge schemas from previous versions

    $schema = array();

    foreach (self::VCARD_VERSIONS as $v => $prop_types) {

      $schema = array_merge($schema, $prop_types);

      if ($v === $version) {
        break;
      }
    }

    return $schema;
  }



  function __construct(array $options = NULL)
  {

    if (!$options || !is_array($options)) {
      $options = array();
    }

    $this->options = array_merge(self::$default_options, $options);

    $this->validateOptions();

    $this->schema = self::getSchema($this->options['version']);
  }



  public function __toString()
  {
    return $this->getString();
  }



  private function validateOptions()
  {

    $version = $this->options['version'];

    if (!array_key_exists($version, VCard::VCARD_VERSIONS)) {
      throw new \InvalidArgumentException("VCard: Unsupported version number '${version}'.");
    }
  }



  /**
   * Get vCard as formatted string
   *
   * @return string
   */
  public function getString(): string
  {

    // Get options
    $opt_enforce_required = $this->options['enforce_required_props'];

    $props_used = array();
    $props_required = array();

    if ($opt_enforce_required) {
      foreach ($this->schema as $type => $def) {
        if (isset($def['required']) && $def['required'] === TRUE) {
          $props_required[] = $type;
        }
      }
    }

    $buffer = "";

    // Begin vCard
    $buffer .= "BEGIN:VCARD\n";
    $buffer .= "VERSION:{$this->options['version']}\n";

    // Write property values to $buffer
    foreach ($this->properties as $key => $instances) {
      foreach ($instances as $prop) {
        $output = $prop->getString();
        $buffer .= $output;

        if (!empty($output)) {
          $props_used[] = $key;
        }
      }
    }

    // End vCard
    $buffer .= "END:VCARD\n";

    // Check for required props
    if ($opt_enforce_required) {
      foreach ($props_required as $type) {
        if (!in_array($type, $props_used)) {
          throw new \Exception("VCard: Required property '${type}' is missing.");
        }
      }
    }

    return $buffer;
  }



  /**
   * Add a property instance to the vCard object.
   * 
   * @param   VCardProperty   $prop       vCard property object.
   * @param   bool            $single     Single instance property. Replace other instances of same type.
   * 
   * @return VCardProperty
   */
  public function addProp(VCardProperty $prop, bool $single = FALSE): VCardProperty
  {

    // $schema = $this->getSchema();

    $prop_type = $prop->type;

    // Illegal prop_types
    $illegal = [
      'BEGIN',
      'END',
      'VERSION'
    ];

    if (in_array($prop_type, $illegal)) {

      // TODO: Print to error_log and return.

      throw new \Exception("VCard: Can't add property with reserved type '${prop_type}'.");
    }

    $custom_proptype_prefix = $this->options['custom_proptype_prefix'];

    if (
      !isset($this->schema[$prop_type]) &&
      substr($prop_type, 0, strlen($custom_proptype_prefix)) !== $custom_proptype_prefix
    ) {
      throw new \Exception("VCard: Can't add property with unknown type '${prop_type}'. Custom property types must be prefixed with '${custom_proptype_prefix}'.");
    }

    if (empty($this->properties[$prop->type]) || !is_array($this->properties[$prop->type]) || $single) {
      $this->properties[$prop->type] = array();
    }

    array_push($this->properties[$prop->type], $prop);

    return $prop;
  }



  /**
   * Create new property instance and add it to the vCard object.
   * 
   * @param   string          $prop_type  Property type.
   * @param   array|string    $values     Property values.
   * @param   bool            $single     Single instance property. Replace other instances of same type.
   * 
   * @return VCardProperty
   */
  public function createProp(string $prop_type, $values, bool $single = FALSE): VCardProperty
  {

    $prop = new VCardProperty($this, $prop_type, $values);

    return $this->addProp($prop, $single);
  }
}
