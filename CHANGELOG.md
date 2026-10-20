CHANGELOG
=========

0.1.0
-----

 * Compatible PHP 7.4 → 8.5 (requires PHP >= 7.4, was 7.0) and declares `ext-soap`
 * New : `Iban` local check — length by country (SWIFT registry), MOD 97-10 checksum, check digits 02 - 98 ; `check()` returns the API codes (512 length, 2048 checksum, 4096 format), plus `isValid()`, `normalize()`, `format()`
 * New : `validate_iban()` and `validate_iban_bic()` check the IBAN locally first ; a malformed IBAN no longer calls the API (no credit used) and returns a `failed` `IBANValResStruct` (balance 0, unknown)
 * `transfer_form()` is implemented (it was a `die;`), with the WSDL parameters, and returns the new `TransferResStruct`
 * `get_form()` also accepts an associative array `['key' => 'value']`
 * Classmap completed (`BankStruct`, `Field`, `TransferResStruct` : they came back as `stdClass`) and public (`IbanAPI::CLASSMAP`)
 * Fix : `IBANCalcResStruct::$country` was missing (dynamic property, deprecated in PHP 8.2+)
 * Structs allow dynamic properties (`#[\AllowDynamicProperties]`) : the WSDL is read live, a field added by the API won't raise a deprecation in PHP 8.2+
 * Fix : return types written `IbanValResStruct`, `IbanCandStruct`, `IbanCalcResStruct` instead of the real class names
 * Fix : `setWsdl()` after a first call was ignored (the client was kept)
 * Struct defaults aligned with the WSDL (`BICStruct::$wwwcount` int, `Field::$optionids` / `$options` arrays, `IBANCandStruct::$balance` int, `IBANValResStruct::$return_code` string)
 * The SOAP client is created by `protected client()`
 * composer.json : removed the non-standard `licenses` key and the `1.0-dev` branch alias
 * PHPUnit tests (SOAP responses from fixtures, no API call)
