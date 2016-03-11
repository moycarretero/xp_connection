<?php

namespace Mocal\Bundle\ExperianBundle\Model\Client;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;

use Mocal\Bundle\ExperianBundle\Model\Client\Exception\TokenException;
use Mocal\Bundle\ExperianBundle\Model\Client\Exception\ClientException;
use Mocal\Bundle\ExperianBundle\Model\Client\Exception\ServerException;
use Mocal\Bundle\ExperianBundle\Model\Client\Exception\BadResponseException;

class ExperianClient
{
    protected $token;
    protected $client;
    protected $logger;

    public function __construct(
        $clientId,
        $consumerKey,
        $consumerSecret,
        $custId,
        $entityId,
        $urlToken,
        $urlEmailCampaign,
        $logger
    ) {

        $this->client           = new Client();
        $this->clientId         = $clientId;
        $this->custId           = $custId;
        $this->entityId         = $entityId;
        $this->consumerSecret   = $consumerSecret;
        $this->consumerKey      = $consumerKey;
        $this->urlToken         = $urlToken;
        $this->urlEmailCampaign = $urlEmailCampaign;
        $this->logger           = $logger;

        $this->token = $this->getToken();
    }

    public function getToken()
    {
        $postBody = array(
            "grant_type" => "password",
            "client_id" => $this->clientId,
            "username" => $this->consumerKey,
            "password" => $this->consumerSecret,
            "content_type" => "application/x-www-form-urlencoded"
        );

        try {
            $request = $this->client->post($this->urlToken, null, $postBody);

            $response = $request->send();
        } catch (ClientErrorResponseException $e) {
            $message = "Client error -> ".$e->getMessage();
            $this->logger->err(__METHOD__.":".$message);

            throw new ClientException($message);
        } catch (ServerErrorResponseException $e) {
            $message = "Server error -> ".$e->getMessage();
            $this->logger->err(__METHOD__.":".$message);

            throw new ServerException($message);
        }

        $bodyResponse = $response->getBody();

        if (!$bodyResponse) {
            $message = __METHOD__.": Empty response";
            $this->logger->err($message);

            throw new BadResponseException($message);
        }

        $response = json_decode($bodyResponse, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            $message = __METHOD__.": Error decoding JSON -> ".json_last_error_msg();
            $this->logger->err($message);

            throw new BadResponseException($message);
        }

        if (!isset($response['access_token'])) {
            $message = __METHOD__.": Access Token is not returned.";
            $this->logger->err($message);

            throw new TokenException($message);
        }

        return $response['access_token'];
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function buildPostAction($subject, $body)
    {
        $emailCampaign = array(
            "CampName" => "Envio Urgente 5", // ¿Se puede repetir?
            "CustId" => $this->custId,
            "EntityId" => $this->entityId,
            "TypeId" => "REGULAR",
            "ToFilterId" => "52686", // Tengo que sacar esto de algún sitio
            "EmailMsgTemplate" => array(
                "FromName" => "El Mundo",
                "ToName" => "{(nombre)}",
                "ToAddressPropId" => "11542",
                "FromAddressId" => "8",
                "Subject" => $subject,
                "CodePageId" => "65001",
                "VmtaPoolId" => "100359",
                "ProofSubjectPrefix" => "PROOF"
            ),
            "CampParam" => array(
                "shortenLinksFlag" => "0",
                "carryOverToNextDayFlag" => "1",
                "stopNightDeliveryFlag" => "0",
                "ignoreConfirmationFlag" => "0",
                "sendSchedule" => array(
                    "timeZone" => "W_Europe_Standard_Time",
                    "dayFrequency" => array(
                        "frequencyType" => "Daily",
                        "daysInterval" => "1"
                    )
                ),
                "queueSchedule" => array("timeZone" => "W_Europe_Standard_Time")
            ),
            "linkTrackingDomainId" => "5359",
            "linkTrackingUsageMask" => "HTML_AND_TEXT",
            "CampReviewFlags" => array(
                "ContCalculationFlag" => "0",
                "PersonalizationFlag" => "0",
                "SendingFlag" => "1"
            ),
            "Obj" => array(
                "display_name" => "Envio_Urgente_5", // ¿Puede ser lo mismo que el subject?
                "type_id" => "CampaignEmail",
                "parent_obj_id" => "20174", // ¿De donde viene este numero?
                "eligibility_status_id" => "READY"
            ),
            "contBodies" => array(
                array(
                    "type" => "HTML",
                    "usageMask" => "ALL_EMAIL_STYLE_USAGE_MASK",
                    "body" => $body
                )
            ),
            "CampMetaParams" => array(
                array("optionId" => "4"),
                array("optionId" => "5"),
                array("optionId" => "8"),
                array("optionId" => "9"),
                array("optionId" => "10"),
            )
        );

        try {
            $request = $this->client->post($this->urlEmailCampaign, null, json_encode($emailCampaign));
            $request->addHeader('Accept', 'application/json');
            $request->addHeader('Content-Type', 'application/json');
            $request->addHeader('Authorization', 'Bearer '.$this->token);

            $response = $request->send();
        } catch (ClientErrorResponseException $e) {
            $message = "Client error -> ".$e->getMessage();
            $this->logger->err(__METHOD__.":".$message);

            throw new ClientException($message);
        } catch (ServerErrorResponseException $e) {
            $message = "Server error -> ".$e->getMessage();
            $this->logger->err($message);

            throw new ServerException($message);
        }

        $bodyResponse = $response->getBody();

        if (!$bodyResponse) {
            $message = __METHOD__.": Empty response";
            $this->logger->err($message);

            throw new BadResponseException($message);
        }

        $response = json_decode($bodyResponse, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            $message = __METHOD__.": Error decoding JSON -> ".json_last_error_msg();
            $this->logger->err($message);

            throw new BadResponseException($message);
        }

        return $response;
    }

    public function buildPutAction($campAction, $campId, $extraData = array())
    {
        $campaignData = array(
            "CampId" => $campId,
            "CustId" => $this->custId,
            "EntityId" => $this->entityId,
            "CampAction" => $campAction
        );

        $emailCampaign = array_merge($campaignData, $extraData);

        try {
            $request = $this->client->put($this->urlEmailCampaign.'?id='.$campId, null, json_encode($emailCampaign));
            $request->addHeader('Accept', 'application/json');
            $request->addHeader('Content-Type', 'application/json');
            $request->addHeader('Authorization', 'Bearer '.$this->token);

            $response = $request->send();
        } catch (ClientErrorResponseException $e) {
            $message = "Client error -> ".$e->getMessage();
            $this->logger->err(__METHOD__.":".$message);

            throw new ClientException($message);
        } catch (ServerErrorResponseException $e) {
            $message = "Server error -> ".$e->getMessage();
            $this->logger->err(__METHOD__.":".$message);

            throw new ServerException($message);
        }

        $bodyResponse = $response->getBody();

        if (!$bodyResponse) {
            $message = __METHOD__.": Empty response";
            $this->logger->err($message);

            throw new BadResponseException($message);
        }

        $response = json_decode($bodyResponse, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            $message = __METHOD__.": Error decoding JSON -> ".json_last_error_msg();
            $this->logger->err($message);

            throw new BadResponseException($message);
        }

        return $response;
    }

    public function buildGetAction($campId)
    {
        try {
            $request = $this->client->get($this->urlEmailCampaign.'?id='.$campId);
            $request->addHeader('Accept', 'application/json');
            $request->addHeader('Content-Type', 'application/json');
            $request->addHeader('Authorization', 'Bearer '.$this->token);

            $response = $request->send();
        } catch (ClientErrorResponseException $e) {
            $message = "Client error -> ".$e->getMessage();
            $this->logger->err(__METHOD__.":".$message);

            throw new ClientException($message);
        } catch (ServerErrorResponseException $e) {
            $message = "Server error -> ".$e->getMessage();
            $this->logger->err(__METHOD__.":".$message);

            throw new ServerException($message);
        }

        $bodyResponse = $response->getBody();

        if (!$bodyResponse) {
            $message = __METHOD__.": Empty response";
            $this->logger->err($message);

            throw new BadResponseException($message);
        }

        $response = json_decode($bodyResponse, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            $message = __METHOD__.": Error decoding JSON -> ".json_last_error_msg();
            $this->logger->err($message);

            throw new BadResponseException($message);
        }

        return $response;
    }
}
