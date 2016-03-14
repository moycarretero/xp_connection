<?php

namespace Mocal\Bundle\ExperianBundle\Model;

use Mocal\Bundle\ExperianBundle\Model\Exception\ExperianManagerException;

use Mocal\Bundle\ExperianBundle\Model\Client\ExperianClient;
use Mocal\Bundle\ExperianBundle\Model\Client\Exception\ExperianClientException;
use Mocal\Bundle\ExperianBundle\Entity\Newsletter;

class ExperianManager
{
    protected $client;
    protected $logger;

    public function __construct(ExperianClient $client, $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function createNewsletter($data)
    {
        $newsletter = new Newsletter();
        $newsletter->setSubject($data['subject']);
        $newsletter->setBody($data['body']);
        $this->create($newsletter);

        $this->launch($newsletter->getClientId());

        return $this->approve($newsletter->getClientId());
    }

    public function updateNewsletter($data)
    {
        $newsletter = new Newsletter();
        $newsletter->setClientId($data['clientId']);
        $newsletter->setSubject($data['subject']);
        $newsletter->setBody($data['body']);
        $this->save($campId, $subject);
        return $this->change($newsletter);
    }

    public function getNewsletter($newsletterId)
    {
        return $this->client->buildGetAction($newsletterId);
    }

    public function create($newsletter)
    {
        try {
            $response = $this->client->buildPostAction($newsletter->getSubject(), $newsletter->getBody());
            $newsletter->setClientId($response['campId']);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function save($newsletter)
    {
        $emailCampaign = array(
            "emailMsgTemplate" => array(
                "campId" => $newsletter->getClientId(),
                "subject" => $newsletter->getSubject()
             )
        );

        try {
            return $this->client->buildPutAction("SAVE", $newsletter->getClientId(), $emailCampaign);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function launch($newsletterId)
    {
        try {
            return $this->client->buildPutAction("LAUNCH", $newsletterId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function proof($newsletterId)
    {
        try {
            return $this->client->buildPutAction("PROOF", $newsletterId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function audit($newsletterId)
    {
        try {
            return $this->client->buildPutAction("AUDIT", $newsletterId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function change($newsletter)
    {
        $emailCampaign = array(
            "contBodies" => array(
                array(
                    "body"=> $newsletter->getBody(),
                    "campId"=> "7897",
                    "usageMask"=> "ALL_EMAIL_STYLE_USAGE_MASK",
                    "type"=> "HTML"
                )
            )
        );

        try {
            return $this->client->buildPutAction("CHANGE", $newsletter->getClientId(), $emailCampaign);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function approve($newsletterId)
    {
        try {
            return $this->client->buildPutAction("APPROVE", $newsletterId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function pause($newsletterId)
    {
        try {
            return $this->client->buildPutAction("PAUSE", $newsletterId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function suspend($newsletterId)
    {
        try {
            return $this->client->buildPutAction("SUSPEND", $newsletterId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function resume($newsletterId)
    {
        try {
            return $this->client->buildPutAction("RESUME", $newsletterId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }

    public function cancel($newsletterId)
    {
        try {
            return $this->client->buildPutAction("CANCEL", $newsletterId);
        } catch (ExperianClientException $e) {
            $e->setMessage(__METHOD__." ".$e->getMessage());
            throw $e;
        }
    }
}
