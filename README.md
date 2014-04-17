# Parsley

[![Build Status](https://travis-ci.org/gajus/parsley.png?branch=master)](https://travis-ci.org/gajus/parsley)
[![Coverage Status](https://coveralls.io/repos/gajus/parsley/badge.png?branch=master)](https://coveralls.io/r/gajus/parsley?branch=master)
[![Latest Stable Version](https://poser.pugx.org/gajus/parsley/version.png)](https://packagist.org/packages/gajus/parsley)
[![License](https://poser.pugx.org/gajus/parsley/license.png)](https://packagist.org/packages/gajus/parsley)

Pattern interpreter for generating random strings.

## Generator

```php
generator = new \Gajus\Parsley\Generator();
$codes = $generator->generateFromPattern('FOO[A-Z]{10}[0-9]{2}', 100);
```

The above example will generate an array containing 100 codes, each prefixed with "FOO", followed by 10 characters from "ABCDEFGHKMNOPRSTUVWXYZ23456789" haystack and 2 numbers from "0123456789" haystack.

Parsley utilises [RandomLib](https://github.com/ircmaxell/RandomLib) to generate the random character pool.

## Supported Tokens

### Literal

Pattern can consist of literal characters, e.g. prefix of suffix of the string.

```php
$parser->tokenise('abc');
```

```php
output
```

The above pattern insists that the string is literally "abc".

### Range

Range can be either numeric or ASCII.

```php
$parser->tokenise('[a-z]');
```

In the `[a-z]` example, string must be a character from "abcdefghijklmnopqrstuvwxyz" haystack.

```php
output
```

### Range with Repetition

If the character must occur more than once, use repetition.

```
$parser->tokenise('[a-c]{3}');
```

In the `[a-z]{3}` example, string must consist of 3 characters from the "abc" haystack.

### Character Classes

Predefined character classes can be used instead of ranges.

|Character Class|Range|
|---|---|
|`\U`|"ABCDEFGHKMNOPRSTUVWXYZ23456789" (or A-Z0-9 excluding IJLQ01) describes characters that are unambiguously recognised regardless of the font or case-sensitivity. The designated use case is voucher codes.|

### Character Classes with Repetition

Similar to the Range with Repetition, Character Classes can be used with repetition, e.g.

```php
$parser->tokenise('\U{3}');
```

## Limitations

* Pattern cannot include `[]{}` characters.
* Pattern cannot include characters outside ASCII.