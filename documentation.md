# php-vcard - Documentation

### namespace **jelgblad\VCard**

## class **VCard**

### function **__construct**(array $options)

Create new VCard-object.

#### Arguments

##### array **$options**

| Key                    | Type   | Default | Description                                                              |
| -----------------------|--------|-------- | ------------------------------------------------------------------------ |
| version                | string | 3.0     | vCard version.                                                           |
| no_empty_props         | bool   | true    | Skip empty properties when generating output string.                     |
| enforce_cardinality    | bool   | true    | Throw error, when generating output, if cardinalities are not satisfied. |
| custom_proptype_prefix | string | X-      | Allowed prefix for non-standard types.                                   |
