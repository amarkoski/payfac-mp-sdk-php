<?php
/**
 * Created by PhpStorm.
 * User: cchang
 * Date: 9/24/18
 * Time: 9:46 AM
 */

namespace src\sdk;


use src\exceptions\SchemaErrorHandler;
use src\utils\Communication;
use src\utils\Utils;

class Principal
{
    const SERVICE_ROUTE1 = "/legalentity/";
    const SERVICE_ROUTE2 = "/principal";

    public function __construct($treeResponse = false, $overrides = array())
    {
        $this->config = Utils::getConfig($overrides);
        $this->communication = new Communication($treeResponse, $overrides);
        $this->schemaErrorHandler = new SchemaErrorHandler();

        // Enable user error handling
        libxml_use_internal_errors(true);
    }

    public function setCommunication($communication)
    {
        $this->communication = $communication;
    }

    ////////////////////////////////////////////////////////////////////
    //                           Principal API:                       //
    ////////////////////////////////////////////////////////////////////

    public function postPrincipal($legalEntityId, $principalCreateRequest)
    {
        $url_suffix = self::SERVICE_ROUTE1 . $legalEntityId . self::SERVICE_ROUTE2;
        $request_body = Utils::get_requestbody_from_xml('legalEntityPrincipalCreateRequest', $principalCreateRequest);

        if (Utils::validateXML($request_body)) {
            return $this->communication->httpPostRequest($url_suffix, $request_body);
        } else {
            $this->schemaErrorHandler->libxml_display_errors();
        }
    }

    public function deletePrincipal($legalEntityId, $principalId)
    {
        $url_suffix = self::SERVICE_ROUTE1 . $legalEntityId . self::SERVICE_ROUTE2 . "/" . $principalId;
        return $this->communication->httpDeleteRequest($url_suffix);
    }

}