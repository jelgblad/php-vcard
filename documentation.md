# php-vcard - Documentation

#### namespace **jelgblad\VCard**

## class **VCard**

### constructor: **__construct()**

*Create new VCard-object.*

```
public __construct(array|null $options) : void
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
