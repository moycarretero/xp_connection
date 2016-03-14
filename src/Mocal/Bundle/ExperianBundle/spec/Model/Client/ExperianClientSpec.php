<?php

namespace spec\Mocal\Bundle\ExperianBundle\Model\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Client;

class ExperianClientSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            17539,
            "MTc1Mzk6MTAwMjQ5",
            "cd91f919a5fd40b0b1c08d8f8eaac72c",
            100249,
            167,
            "https://api.ccmp.eu/services/authorization/oauth2/token",
            "https://api.ccmp.eu/services2/api/EmailCampaign"
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Mocal\Bundle\ExperianBundle\Model\Client\ExperianClient');
    }

    public function it_retrieves_valid_campaign(
        EntityEnclosingRequestInterface $request,
        Response $response,
        Client $client
    ) {
        $campId = "7897";
        $data = array("campId" => $campId, "campName" => "Test Newsletter");
        $json = json_encode($data);

        $client->get(Argument::any())->willReturn($request);
        $request->send()->willReturn($response);
        $response->getBody()->willReturn($json);

        $this->buildGetAction($campId)->shouldReturn($data);
    }

    public function it_throw_exception_when_retrieves_non_valid_campaign()
    {

    }
}
