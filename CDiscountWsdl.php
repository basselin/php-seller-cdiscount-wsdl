<?php
/**
 * @link      https://github.com/basselin/php-seller-cdiscount-wsdl
 * @copyright (c) 2014, Benoit Asselin contact(at)ab-d.fr
 * @license   MIT Licence
 */

class CDiscountWsdl
{
    /**
     * URL du token
     * @return string
     */
    const URL = 'https://sts.cdiscount.com/users/httpIssue.svc/?realm=https://wsvc.cdiscount.com/MarketplaceAPIService.svc';

    /**
     * URL du webservice
     * @return string
     */
    const WSDL = 'https://wsvc.cdiscount.com/MarketplaceAPIService.svc?wsdl';

    /**
     * @var SoapClient
     */
    protected $soap;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $passw;

    /**
     * @var stdClass
     */
    protected $lastResult;

    /**
     * @param string $login
     * @param string $passw
     */
    public function __construct($login = null, $passw = null)
    {
        $this->setLogin($login)
             ->setPassw($passw);
    }

    /**
     * Conversion des tableaux en objets de maniere recursive
     * @param array $array
     * @return stdClass
     */
    protected function array2object(array $array)
    {
        return json_decode(json_encode($array));
    }

    /**
     * headerMessage
     * @return stdClass
     */
    protected function getHeaderMessage()
    {
        return $this->array2object(array(
            'Context' => array(
                'CatalogID'      => 1,
                'CustomerPoolID' => 1,
                'SiteID'         => 100,
            ),
            'Localization' => array(
                'Country'         => 'Fr',
                'Currency'        => 'Eur',
                'DecimalPosition' => '2',
                'Language'        => 'Fr',
            ),
            'Security' => array(
                'DomainRightsList' => null,
                'IssuerID'         => null,
                'SessionID'        => null,
                'SubjectLocality'  => null,
                'TokenId'          => $this->getToken(),
                'UserName'         => null,
            ),
            'Version' => '1.0',
        ));
    }

    /**
     * @param string $login
     * @return CDiscountWsdl
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @param string $passw
     * @return CDiscountWsdl
     */
    public function setPassw($passw)
    {
        $this->passw = $passw;
        return $this;
    }

    /**
     * @return SoapClient
     */
    public function getSoap()
    {
        if (!$this->soap) {
            $this->soap = new SoapClient($this::WSDL);
        }
        return $this->soap;
    }

    /**
     * @return stdClass
     */
    public function getLastResult()
    {
        return $this->lastResult;
    }

    /**
     * @return string|bool
     * @throws Exception
     */
    public function getToken()
    {
        if (null !== $this->token) {
            return $this->token;
        }

        $this->token = false;
        $url = parse_url($this::URL);
        $auth = base64_encode($this->login . ':' . $this->passw);
        $fp = @fsockopen('ssl://' . $url['host'], 443, $errno, $errstr, 30);
        if (!$fp) {
            echo "<div class=\"alert alert-danger\">$errstr ($errno)</div>\n";
        } else {
            $header  = "GET {$url['path']}?{$url['query']} HTTP/1.1\r\n";
            $header .= "Host: {$url['host']}\r\n";
            $header .= "Authorization: Basic {$auth}\r\n";
            $header .= "Connection: Close\r\n\r\n";

            $res = '';
            fputs($fp, $header);
            while (!feof($fp)) {
                $res .= fgets($fp, 1024);
            }
            fclose($fp);
            if (preg_match('/<string[^>]+>(.+?)<\/string>/', $res, $array)) {
                $this->token = $array[1];
            } else {
                throw new Exception('The TokenId was not found');
            }
        }
        return $this->token;
    }

    /**
     * Demander la creation d'un ensemble de produits
     * @param string $zipPath
     * @return stdClass
     */
    public function submitProductPackage($zipPath)
    {
        $this->lastResult = null;
        $params = array(
            'headerMessage' => $this->getHeaderMessage(),
            'productPackageRequest' => $this->array2object(array(
                'ZipFileFullPath' => $zipPath,
            )),
        );

        try {
            $this->lastResult = $this->getSoap()->SubmitProductPackage($params);
        } catch (SoapFault $exception) {
            echo '<div class="alert alert-danger">' . $exception->getMessage() . '</div>';
        }
        return $this->lastResult;
    }

    /**
     * Arborescence
     * @return stdClass
     */
    public function getAllowedCategoryTree()
    {
        $this->lastResult = null;
        $params = array(
            'headerMessage' => $this->getHeaderMessage(),
        );

        try {
            $this->lastResult = $this->getSoap()->GetAllowedCategoryTree($params);
        } catch (SoapFault $exception) {
            echo '<div class="alert alert-danger">' . $exception->getMessage() . '</div>';
        }
        return $this->lastResult;
    }

    /**
     * Liste des Model
     * @return stdClass
     */
    public function getAllModelList()
    {
        $this->lastResult = null;
        $params = array(
            'headerMessage' => $this->getHeaderMessage(),
        );

        try {
            $this->lastResult = $this->getSoap()->GetAllModelList($params);
        } catch (SoapFault $exception) {
            echo '<div class="alert alert-danger">' . $exception->getMessage() . '</div>';
        }
        return $this->lastResult;
    }

    /**
     * Liste des Model
     * @param string $categoryCode
     * @return stdClass
     */
    public function getModelList($categoryCode)
    {
        $this->lastResult = null;
        $params = array(
            'headerMessage' => $this->getHeaderMessage(),
            'modelFilter' => $this->array2object(array(
                'CategoryCodeList' => array(
                    'string' => $categoryCode,
                ),
            )),
        );

        try {
            $this->lastResult = $this->getSoap()->GetModelList($params);
        } catch (SoapFault $exception) {
            echo '<div class="alert alert-danger">' . $exception->getMessage() . '</div>';
        }
        return $this->lastResult;
    }
}
