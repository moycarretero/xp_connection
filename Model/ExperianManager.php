<?php

namespace Mocal\Bundle\ExperianBundle\Model;

use Mocal\Bundle\ExperianBundle\Model\Exception\ExperianManagerException;

use Mocal\Bundle\ExperianBundle\Model\Client\ExperianClient;
use Mocal\Bundle\ExperianBundle\Model\Client\Exception\ExperianClientException;

class ExperianManager
{
    protected $client;
    protected $logger;

    public function __construct(ExperianClient $client, $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function createNewsletter($subject, $body)
    {
        $response = $this->create($subject, $body);
        $campId = $response['campId'];

        $this->launch($campId);

        return $this->approve($campId);
    }

    public function updateNewsletter($campId, $subject, $message)
    {
        $this->save($campId, $subject);
        return $this->change($campId, $message);
    }

    public function getNewsletter($campId)
    {
        return $this->client->buildGetAction($campId);
    }

    public function create($subject, $body)
    {
        try {
            return $this->client->buildPostAction($subject, $body);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function save($campId, $subject)
    {
        $emailCampaign = array(
            "emailMsgTemplate" => array(
                "campId" => $campId,
                "subject" => $subject
             )
        );

        try {
            return $this->client->buildPutAction("SAVE", $campId, $emailCampaign);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function launch($campId)
    {
        try {
            return $this->client->buildPutAction("LAUNCH", $campId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function proof($campId)
    {
        try {
            return $this->client->buildPutAction("PROOF", $campId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function audit($campId)
    {
        try {
            return $this->client->buildPutAction("AUDIT", $campId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function change($campId, $message)
    {
        $emailCampaign = array(
            "contBodies" => array(
                array(
                    "body"=> $message,
                    "campId"=> "7897",
                    "usageMask"=> "ALL_EMAIL_STYLE_USAGE_MASK",
                    "type"=> "HTML"
                )
            )
        );

        try {
            return $this->client->buildPutAction("CHANGE", $campId, $emailCampaign);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function approve($campId)
    {
        try {
            return $this->client->buildPutAction("APPROVE", $campId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function pause($campId)
    {
        try {
            return $this->client->buildPutAction("PAUSE", $campId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function suspend($campId)
    {
        try {
            return $this->client->buildPutAction("SUSPEND", $campId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function resume($campId)
    {
        try {
            return $this->client->buildPutAction("RESUME", $campId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function cancel($campId)
    {
        try {
            return $this->client->buildPutAction("CANCEL", $campId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }
}
