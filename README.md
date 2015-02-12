# PHP Seller CDiscount WSDL

Classe Seller CDiscount permettant de soumettre des offres et des produits.

https://seller.cdiscount.com

*À noter: Cette classe peut être utilisée avec Go-Sport marketplace (https://seller.go-sport.com)*


## Exemple d'utilisation
```php
require './CDiscountWsdl.php';
$cdiscount = new CDiscountWsdl('login', 'pa$$word');
var_dump($cdiscount->submitProductPackage('http://mon.domaine/products.zip'));
var_dump($cdiscount->submitOfferPackage('http://mon.domaine/offers.zip'));
```


## Modules PHP

* http://php.net/manual/fr/book.openssl.php
* http://php.net/manual/fr/book.soap.php

