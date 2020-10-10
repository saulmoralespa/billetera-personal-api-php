Billetera Personal API PHP SDk
============================================================

## Installation

Use composer package manager

```bash
composer require saulmoralespa/billetera-personal-api-php
```

```php
// ... please, add composer autoloader first
include_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// import client class
use BilleteraPersonal\Client;

$user = getenv('USER');
$password = getenv('PASSWORD');

$billeteraPersonal = new Client($user, $password);
```
