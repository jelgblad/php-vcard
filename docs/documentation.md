# php-vcard - Documentation

#### namespace **jelgblad\VCard**

## class **VCard**

### constructor: **__construct()**

*Create new VCard.*

```
public __construct(array|null $options) : VCard
```

#### Parameters

array|null **$options**

*Array with options.*

| Key                    | Type   | Default | Description                                                              |
| -----------------------|--------|-------- | ------------------------------------------------------------------------ |
| version                | string | 3.0     | vCard version.                                                           |
| no_empty_props         | bool   | true    | Skip empty properties when generating output string.                     |
| enforce_cardinality    | bool   | true    | Throw error, when generating output, if cardinalities are not satisfied. |
| custom_proptype_prefix | string | X-      | Allowed prefix for non-standard types.                                   |

#### Return value

VCard


### static method: **parse()**

*Parse a string and return vCards.*

```
public static parse(string $input): VCard[]
```

#### Parameters

string **$input**

*Input string.*

#### Return value

VCard[]


### method: **__toString**()

*Return class as a string. See **getString()**.*

> **Warning** It was not possible to throw an exception from within a __toString() method before PHP 7.4.0. Doing so will result in a fatal error. See https://www.php.net/manual/en/language.oop5.magic.php#object.tostring.

#### Return value

string - Serialized vCard data.



### method: **addProp**()

*Add a property to the vCard.*

```
public addProp(VCardProperty $prop, bool|null $single) : VCardProperty
```

#### Parameters

VCardProperty **$prop**

*The property to add.*

bool|null **$single**

*Single instance property. Replace other instances of same type.*

#### Return value

VCardProperty



### method: **createProp**()

*Create new property and add it to the vCard.*

```
public createProp(string $prop_type, array|string $values, bool|null $single) : VCardProperty
```

#### Parameters

string **$prop_type**

*Name of vCard property type.*

array|string **$values**

*Value or values to assign to property.*

bool|null **$single**

*Single instance property. Replace other instances of same type.*

#### Return value

VCardProperty


### method: **getProp**()

*Get single property from vCard.*

```
public getProp(string $prop_type, int|null $index) : VCardProperty|null
```

#### Parameters

string **$prop_type**

*Name of vCard property type.*

string **$index** = 0

*Index of property instance to get.*

#### Return value

VCardProperty|null


### method: **getProps**()

*Get properties from vCard.*

```
public getProps(string|null $prop_type) : VCardProperty[]
```

#### Parameters

string **$prop_type**

*Name of vCard property type.*

#### Return value

VCardProperty[]



### method: **getString**()

*Get vCard as formatted string.*

```
public getString() : string
```

#### Return value

string - Serialized vCard data.



### method: **setOption**()

*Set an option.*

```
public setOption(string $option, mixed $value) : void
```

#### Parameters

string **$option**

*See available options under `__construct()`.*

mixed **$value**

*New option value.*

#### Return value

void



## class **VCardProperty**

### constructor: **__construct()**

*Create new VCardProperty.*

```
public __construct(VCard $vcard, string $prop_type, array|string $values) : VCardProperty
```

#### Parameters

VCard **$vcard**

*Parent VCard.*

string **$prop_type**

*Name of vCard property type.*

array|string **$values**

*Value or values to assign to property.*

#### Return value

VCardProperty



### method: **__toString**()

*Return class as a string. See **getString()**.*

> **Warning** It was not possible to throw an exception from within a __toString() method before PHP 7.4.0. Doing so will result in a fatal error. See https://www.php.net/manual/en/language.oop5.magic.php#object.tostring.

#### Return value

string - Serialized vCard property data.



### method: **addValue**()

*Add a value to property.*

```
public addValue(string $value) : void
```

#### Parameters

string **$value**

*Value to add to property.*

#### Return value

void



### method: **addParam**()

*Add a parameter to the vCard property.*

```
public addParam(VCardPropertyParameter $param) : VCardPropertyParameter
```

#### Parameters

VCardPropertyParameter **$param**

*The parameter to add.*

#### Return value

VCardPropertyParameter



### method: **createParam**()

*Create new parameter and add it to the vCard property.*

```
public createParam(string $param_type, array|string|null $values) : VCardPropertyParameter
```

#### Parameters

string **$param_type**

*Name of vCard property parameter type.*

array|string|null **$values**

*Parameter values.*

#### Return value

VCardPropertyParameter


### method: **getValue**()

*Get single value from vCard property.*

```
public getValue() : string
```

#### Parameters

int|null **$index** = 0

*Index of value to get.*

#### Return value

string


### method: **getValues**()

*Get values from vCard property.*

```
public getValues() : string[]
```

#### Return value

string[]


### method: **getParam**()

*Get single parameter from vCard property.*

```
public getParam(string $param_type) : VCardPropertyParameter|null
```

#### Parameters

string **$param_type**

*Name of vCard property parameter type.*

#### Return value

VCardProperty|null


### method: **getParams**()

*Get parameters from vCard property.*

```
public getParams() : VCardPropertyParameter[]
```

#### Return value

VCardPropertyParameter[]


### method: **getString**()

*Get vCard property as formatted string.*

```
public getString() : string
```

#### Return value

string - Serialized vCard property data.



## class **VCardPropertyParameter**

### constructor: **__construct()**

*Create new VCardPropertyParameter.*

```
public __construct(VCardProperty $property, string $param_type, array|string|null $values) : VCardPropertyParameter
```

#### Parameters

VCardProperty **$property**

*Parent VCardProperty.*

string **$param_type**

*Name of vCard property parameter type.*

array|string|null **$values**

*Value or values to assign to parameter.*

#### Return value

VCardPropertyParameter



### method: **__toString**()

*Return class as a string. See **getString()**.*

> **Warning** It was not possible to throw an exception from within a __toString() method before PHP 7.4.0. Doing so will result in a fatal error. See https://www.php.net/manual/en/language.oop5.magic.php#object.tostring.

#### Return value

string - Serialized vCard property parameter data.



### method: **addValue**()

*Add value to property parameter*

```
public addValue(string $value)
```

#### Parameters

string **$value**

*Value to add to property parameter.*

#### Return value

void


### method: **getValue**()

*Get single value from vCard property parameter.*

```
public getValue(int|null $index = 0) : string
```

#### Parameters

int|null **$index** = 0

*Index of value to get.*

#### Return value

string


### method: **getValues**()

*Get values from vCard property parameter.*

```
public getValues() : string[]
```

#### Return value

string[]



### method: **getString**()

*Get vCard property parameter as formatted string.*

```
public getString() : string
```

#### Return value

string - Serialized vCard property parameter data.
