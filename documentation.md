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



### method: **addProp**()

*Add a property instance to the vCard object.*

```
public addProp(VCardProperty $prop, bool|null $single) : VCardProperty
```

#### Parameters

string **$prop**

*vCard property object.*

bool|null **$single**

*Single instance property. Replace other instances of same type.*

#### Return value

VCardProperty



### method: **createProp**()

*Create new property instance and add it to the vCard object.*

```
public createProp(string $prop_type, array|string $values, bool|null $single) : VCardProperty
```

#### Parameters

string **$prop_type**

*Property type name.*

array|string **$values**

*Property values.*

bool|null **$single**

*Single instance property. Replace other instances of same type.*

#### Return value

VCardProperty



### method: **getString**()

*Get vCard as formatted string.*

```
public getString() : string
```

#### Return value

string - vCard data.



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

*Name of property type.*

array|string **$values**

*Value or values to assign to property.*

#### Return value

VCardProperty


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

*Add a parameter instance to the property object.*

```
public addParam(VCardPropertyParameter $param) : VCardPropertyParameter
```

#### Parameters

VCardPropertyParameter **$param**

*Property type name.*

#### Return value

VCardPropertyParameter


### method: **createParam**()

*Create new parameter instance and add it to the property object.*

```
public createParam(string $param_type, array|string|null $values) : VCardPropertyParameter
```

#### Parameters

string **$param_type**

*Parameter type name.*

array|string|null **$values**

*Parameter values.*

#### Return value

VCardPropertyParameter


### method: **getString**()

*Get property as formatted string.*

```
public getString() : string
```

#### Return value

string - vCard property data.


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

*Name of parameter type.*

array|string|null **$values**

*Value or values to assign to parameter.*

#### Return value

VCardPropertyParameter


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


### method: **getString**()

*Get property parameter as formatted string.*

```
public getString() : string
```

#### Return value

string - vCard property parameter data.
