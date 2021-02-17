# php-vcard - Documentation

#### namespace **jelgblad\VCard**

## class **VCard**

### Methods

#### __construct()

*Create new VCard-object.*

```
public __construct(array|null $options) : void
```

##### Parameters

array|null **$options**

*Optional array with options.*

| Key                    | Type   | Default | Description                                                              |
| -----------------------|--------|-------- | ------------------------------------------------------------------------ |
| version                | string | 3.0     | vCard version.                                                           |
| no_empty_props         | bool   | true    | Skip empty properties when generating output string.                     |
| enforce_cardinality    | bool   | true    | Throw error, when generating output, if cardinalities are not satisfied. |
| custom_proptype_prefix | string | X-      | Allowed prefix for non-standard types.                                   |

#### function **setOption**(string $option, $value)

*Set an option.*

```
public setOption(string $option, mixed $value) : void
```

##### Parameters

string **$option**

*See opssible options under `function __construct`.*

mixed **$value**

*New value.*
