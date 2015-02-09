<?php
/**
 * @link      https://github.com/basselin/php-seller-cdiscount-wsdl
 * @copyright (c) 2014-2015, Benoit Asselin contact(at)ab-d.fr
 * @license   MIT Licence
 */

class GoSportWsdl extends CDiscountWsdl
{
    /**
     * URL du token
     * @const string
     */
    const URL = 'https://sts.go-sport.com/users/httpIssue.svc/?realm=https://wsvc.go-sport.com/MarketplaceAPIService.svc';

    /**
     * URL du webservice
     * @const string
     */
    const WSDL = 'https://wsvc.go-sport.com/MarketplaceAPIService.svc?wsdl';
}
