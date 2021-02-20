<?php

namespace jelgblad\VCard;

class VCard
{

  const VCARD_3 = '3.0';      // https://tools.ietf.org/html/rfc2425
                              // https://tools.ietf.org/html/rfc2426
  const VCARD_4 = '4.0';      // https://tools.ietf.org/html/rfc6350

  const VCARD_VERSIONS = [

    /* vCard version schemas in ascending order.
      * New schemas inherits from previous.
      * NULL unsets the property from a previous version
      * 
      * Cardinality
      * https://tools.ietf.org/html/rfc6350#section-3.3
      * +-------------+--------------------------------------------------+
      * | Cardinality | Meaning                                          |
      * +-------------+--------------------------------------------------+
      * |      1      | Exactly one instance per vCard MUST be present.  |
      * |      *1     | Exactly one instance per vCard MAY be present.   |
      * |      1*     | One or more instances per vCard MUST be present. |
      * |      *      | One or more instances per vCard MAY be present.  |
      * +-------------+--------------------------------------------------+
      * Default cardinality is '*'.
      */

    self::VCARD_3 => [
      'SOURCE' => [],
      'NAME' => [],
      'FN' => [
        'cardinality' => '1*'   // Marked as REQUIRED in vCard 3.0
      ],
      'N' => [
        'cardinality' => '1*',  // Marked as REQUIRED in vCard 3.0
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

    self::VCARD_4 => [
      'N' => [
        'cardinality' => '*1',
        'delimiter' => ';'
      ],
      'NAME' => NULL,
      'BDAY' => [
        'cardinality' => '*1'
      ],
      'PRODID' => [
        'cardinality' => '*1'
      ],
      'REV' => [
        'cardinality' => '*1'
      ],
      'UID' => [
        'cardinality' => '*1'
      ],
      'MAILER' => NULL,
      'LABEL' => NULL,
      'CLASS' => NULL,
      'KIND' => [
        'cardinality' => '*1'
      ],
      'GENDER' => [
        'cardinality' => '*1'
      ],
      'LANG' => [],
      'ANNIVERSARY' => [
        'cardinality' => '*1'
      ],
      'XML' => [],
      'CLIENTPIDMAP' => []
    ]
  ];

  static $default_options = [
    'version'                   => self::VCARD_3,   // vCard version 3.0
    'no_empty_props'            => TRUE,            // Skip empty properties when generating output string.
    'enforce_cardinality'       => TRUE,            // Throw error, when generating output, if cardinalities are not satisfied.
    'custom_proptype_prefix'    => 'X-'             // Allowed prefix for non-standard types.
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


  /**
   * Parse a string and return vCards.
   * 
   * @param   string    $input     Input string.
   * @return  VCard[]
   */
  public static function parse(string $input): array
  {
    // Trim input
    $input = trim($input);

    $vcards = [];

    // NOTE: Needed because VCardProperty::parse() depends on VCard.
    $vcard = new VCard();

    // Loop lines
    foreach (explode("\n", $input) as $lineString) {

      // Parse a property
      $prop = VCardProperty::parse($vcard, $lineString);

      switch ($prop->type) {
        case 'BEGIN':
          // Crate vCard
          $vcard = new VCard();
          break;
        case 'END':
          $vcards[] = $vcard;
          break;
        case 'VERSION':
          $vcard->setOption('version', $prop->values[0]);
          break;
        default:
          $vcard->addProp($prop);
      }
    }

    return $vcards;
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
   * Set option.
   * 
   * @param   string          $option     option name.
   * @param   mixed           $value      option value.
   */
  public function setOption(string $option, $value)
  {
    $this->options[$option] = $value;
    $this->validateOptions();

    if ($option === 'version') {
      $this->schema = self::getSchema($this->options['version']);
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
    $opt_enforce_cardinality = $this->options['enforce_cardinality'];

    $props_used = array();
    $props_required = array();

    // TODO: Check other cardinalities besides 1 and 1*

    if ($opt_enforce_cardinality) {
      foreach ($this->schema as $type => $def) {
        if (isset($def['cardinality']) && $def['cardinality'][0] === '1') {
          $props_required[] = $type;
        }
      }
    }

    $buffer = "";

    // Begin vCard
    $buffer .= "BEGIN:VCARD\n";
    $buffer .= "VERSION:{$this->options['version']}\n";

    // Write property values to $buffer
    foreach ($this->properties as $prop_type => $instances) {
      foreach ($instances as $prop) {

        $output = $prop->getString();
        $buffer .= $output;

        if (!empty($output)) {
          $props_used[] = $prop_type;
        }
      }
    }

    // End vCard
    $buffer .= "END:VCARD\n";

    // Check for required props
    if ($opt_enforce_cardinality) {
      foreach ($props_required as $type) {
        if (!in_array($type, $props_used)) {
          throw new Exceptions\CardinalityException("VCard: Required property '${type}' is missing.");
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


  /**
   * Get single property from vCard.
   * 
   * @param   string      $prop_type  Name of vCard property type.
   * @param   int|null    $index      Index of property instance to get.
   * 
   * @return VCardProperty|null
   */
  public function getProp(string $prop_type, $index = 0): VCardProperty
  {
    $props = $this->getProps($prop_type);

    return count($props) > $index ? $props[$index] : null;
  }


  /**
   * Get properties from vCard.
   * 
   * @param   string|null     $prop_type  Name of vCard property type.
   * 
   * @return VCardProperty[]
   */
  public function getProps(string $prop_type = null): array
  {
    $props = [];

    foreach ($this->properties as $type => $propsOfType) {

      if (!empty($prop_type) && $prop_type !== $type) {
        continue;
      }

      $props = array_merge($props, $propsOfType);
    }

    return $props;
  }
}
