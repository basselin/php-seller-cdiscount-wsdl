# PHP Seller CDiscount WSDL

Classe Seller CDiscount en cours de développement...

https://seller.cdiscount.com


## Exemple d'utilisation
```php
require './CDiscountWsdl.php';
$cdiscount = new CDiscountWsdl('login', 'pa$$word');
var_dump($cdiscount->submitProductPackage('http://mon.domaine/products.zip'));
```


## Modules PHP

* http://php.net/manual/fr/book.openssl.php
* http://php.net/manual/fr/book.soap.php

