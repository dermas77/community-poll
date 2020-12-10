<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiMjE1MTllNmQ1ZjFlNTJiYTQ5NjYyYWYyYTZhNjMwMDYyYzBjNGRiNjQ4Y2JjOTE0OWVlYTFjYjI0ZWU2NTI1ODM5YWUwODcyZGViMWY4NDEiLCJpYXQiOiIxNjA3NTgyNDgwLjYwMjYxOCIsIm5iZiI6IjE2MDc1ODI0ODAuNjAyNjIzIiwiZXhwIjoiMTYzOTExODQ4MC41NTMzODQiLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.bezHojErnelHM_bgkTD1uU6_iH3San5VHw1VgNVS6WiFXkvvnNYa5cmGWlI9_DnhWe1NM1_MQNbPyCzs2xfYq1ikLdHnWlnFxkkHMuc5P1X1C44b519rizvsvjoqcQZAmygY_rX_Xt8yKXek5eweoF5xXIsFMX6NDk8H28E0G_4D9WE677pibwBbkcO7ikt3vAcKZGlr5ySuhxbekdvXODS6DrKF1zM9iDi8lCbCziwbzOjA5j2aPrb1Udq9e24nnZzCqGKGPoLSdB1jIFYwJAVsz2pPsCK-MpAbTUe7F2lXv_AGr_JMv4Ec4wGRvsqnKNRjir9c--C1mg2w_oE7417i7yCe5-uvZWBFu0DLRJ0xVg8Ax88-zr-NTIJ9dgP82BNbcc8p3VFO5oaBcHTG2wUTIMTMafACSoPX77LUkhZdKk3wV2MxxX1J0xxAM-YyQPDUdkA4ifaMrBE0nJH4zgKSeeElGKrpu-sYzs6d9VX7WNCeRN-E-PWoem34Fs-UTP0CxcRSZh3patHNxnyuok1GIkygZvyPLmQhpWfzvQMoXpQHwk9_q68Ntvo-xwYadAMPhU9IzM-cQLhZwpkIuss_GWTBjI4dnhd-aJmt6lfFivsaIefZMyH3CQHsGA3Kydzg01Ie2JMlm3AcGm0vK0ilE5yb-ApsnPHakcXgaFk";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://127.0.0.1:8000' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

        /**
     * @Then the response contains :arg1 records
     */
    public function theResponseContainsRecords($arg1)
    {
        $data = json_decode($this->responseBody);
        $count = count($data);

        return $count == $arg1;
    }

    /**
     * @Then the question contains a title of :arg1
     */
    public function theQuestionContainsATitleOf($arg1)
    {
        $data = json_decode($this->responseBody);
        if($data->title == $arg1){

        }else{
            throw new Exception('The title does not match');
        }

    }
}
