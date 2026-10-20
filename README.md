Coercive Shop IBAN
==================

IBAN validation : a local check (length + MOD 97 checksum, free) and a client for the
[IBAN Calculator](https://www.iban-bic.com/) SOAP API (paid, per credit : bank, BIC candidates…).

Compatible PHP 7.4 → 8.5, requires the `soap` extension for the API.

Get
---
```
composer require coercive/iban
```

Local check
-----------
No network, no credit : tells if the IBAN is well formed, not if the account exists.

```php
use Coercive\Shop\Iban\Iban;

# Spaces and lowercase allowed
Iban::isValid('FR76 3000 6000 0112 3456 7890 189'); // true

# Same codes as the API return_code
Iban::check('FR76 3000 6000 0112 3456 7890 180'); // Iban::ERROR_CHECKSUM (2048)
Iban::check('FR76 3000');                          // Iban::ERROR_LENGTH (512)
Iban::check('not an iban');                        // Iban::ERROR_FORMAT (4096)

# Electronic and print formats
Iban::normalize('fr76 3000 6000 0112 3456 7890 189'); // FR7630006000011234567890189
Iban::format('FR7630006000011234567890189');          // FR76 3000 6000 0112 3456 7890 189
Iban::format('FR7630006000011234567890189', '&nbsp;');
```

API
---
```php
use Coercive\Shop\Iban\IbanAPI;

$api = new IbanAPI('user', 'password');

# Remaining credits
$api->get_balance()->hasCredits();

# validate_iban() and validate_iban_bic() check the IBAN locally first :
# a malformed IBAN does not call the API (no credit used) and returns a 'failed' result
$result = $api->validate_iban('GB82 WEST 1234 5698 7654 32');
if ($result->isValid()) {
    foreach ($result->bic_candidates as $candidate) {
        echo $candidate->bic;
    }
}
```

Every operation of the WSDL is available (`restore_iban`, `calculate_iban`, `find_bank`, `get_form`…),
with the same name and parameters, the credentials being added for you.
The responses are the classes of `Coercive\Shop\Iban\Struct` (`IbanAPI::CLASSMAP`).

A `SoapFault` is thrown when the API can't be reached. Wrong credentials don't throw :
the API answers a `result` / `message` explaining it.

Tests
-----
```
composer install
vendor/bin/phpunit
```

PHPUnit 12 requires PHP 8.3+ to run the tests ; the library itself still runs on PHP 7.4.
The tests don't call the API : the SOAP responses come from `tests/fixtures`.
