# vCard 4.0 properties

## Cardinalities 

| Cardinality | Meaning                                          |
|-------------|--------------------------------------------------|
|      1      | Exactly one instance per vCard MUST be present.  |
|      *1     | Exactly one instance per vCard MAY be present.   |
|      1*     | One or more instances per vCard MUST be present. |
|      *      | One or more instances per vCard MAY be present.  |

*What follows is an enumeration of the standard vCard properties. See https://tools.ietf.org/html/rfc6350#section-6 for more information about how each property should be used.*

## General properties

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| BEGIN | 1 | To denote the beginning of a syntactic entity within a text/vcard content-type. |
| END | 1 | To denote the end of a syntactic entity within a text/vcard content-type. |
| SOURCE | * | To identify the source of directory information contained in the content type. |
| KIND | *1 | To specify the kind of object the vCard represents. |
| XML | * | To include extended XML-encoded vCard data in a plain vCard. |

## Identification Properties

These types are used to capture information associated with the identification and naming of the entity associated with the vCard.

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| FN | 1* | To specify the formatted text corresponding to the name of the object the vCard represents. |
| N | *1 | To specify the components of the name of the object the vCard represents. |
| NICKNAME | * | To specify the text corresponding to the nickname of the object the vCard represents. |
| PHOTO | * | To specify an image or photograph information that annotates some aspect of the object the vCard represents. |
| BDAY | *1 | To specify the birth date of the object the vCard represents. |
| ANNIVERSARY | *1 | The date of marriage, or equivalent, of the object the vCard represents. |
| GENDER | *1 | To specify the components of the sex and gender identity of the object the vCard represents. |

## Delivery Addressing Properties

These types are concerned with information related to the delivery addressing or label for the vCard object.

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| ADR | * | To specify the components of the delivery address for the vCard object. |

## Communications Properties

These properties describe information about how to communicate with the object the vCard represents.

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| TEL | * | To specify the telephone number for telephony communication with the object the vCard represents. |
| EMAIL | * | To specify the electronic mail address for communication with the object the vCard represents. |
| IMPP | * | To specify the URI for instant messaging and presence protocol communications with the object the vCard represents. |
| LANG | * | To specify the language(s) that may be used for contacting the entity associated with the vCard. |

## Geographical Properties

These properties are concerned with information associated with geographical positions or regions associated with the object the vCard represents.

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| TZ | * | To specify information related to the time zone of the object the vCard represents. |
| GEO | * | To specify information related to the global positioning of the object the vCard represents. |

## Organizational Properties

These properties are concerned with information associated with characteristics of the organization or organizational units of the object that the vCard represents.

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| TITLE | * | To specify the position or job of the object the vCard represents. |
| ROLE | * | To specify the function or part played in a particular situation by the object the vCard represents. |
| LOGO | * | To specify a graphic image of a logo associated with the object the vCard represents. |
| ORG | * | To specify the organizational name and units associated with the vCard. |
| MEMBER | * | To include a member in the group this vCard represents. |
| RELATED | * | To specify a relationship between another entity and the entity represented by this vCard. |

## Explanatory Properties

These properties are concerned with additional explanations, such as that related to informational notes or revisions specific to the vCard.

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| CATEGORIES | * | To specify application category information about the vCard, also known as "tags". |
| NOTE | * | To specify supplemental information or a comment that is associated with the vCard. |
| PRODID | *1 | To specify the identifier for the product that created the vCard object. |
| REV | *1 | To specify revision information about the current vCard. |
| SOUND | * | To specify a digital sound content information that annotates some aspect of the vCard.  This property is often used to specify the proper pronunciation of the name property value of the vCard. |
| UID | *1 | To specify a value that represents a globally unique identifier corresponding to the entity associated with the vCard. |
| CLIENTPIDMAP | * | To give a global meaning to a local PID source identifier. |
| URL | * | To specify a uniform resource locator associated with the object to which the vCard refers.  Examples for individuals include personal web sites, blogs, and social networking site identifiers. |
| VERSION | 1 | To specify the version of the vCard specification used to format this vCard. |

## Security Properties

These properties are concerned with the security of communication pathways or access to the vCard.

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| KEY | * | To specify a public key or authentication certificate associated with the object that the vCard represents. |

## Calendar Properties

| Name | Cardinality | Purpose |
| ---- | ----------- | ------- |
| FBURL | * | To specify the URI for the busy time associated with the object that the vCard represents. |
| CALADRURI | * | To specify the calendar user address [RFC5545] to which a scheduling request [RFC5546] should be sent for the object represented by the vCard. |
| CALURI | * | To specify the URI for a calendar associated with the object represented by the vCard. |