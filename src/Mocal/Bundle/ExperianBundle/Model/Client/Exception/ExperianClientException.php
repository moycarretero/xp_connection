<?php

namespace Mocal\Bundle\ExperianBundle\Model\Client\Exception;

class ExperianClientException extends \Exception
{
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
