<?php

namespace Rhymedigital\IsotopeUpsShipping\Entity\UpsApi;

use JsonException;
use RahulGodiyal\PhpUpsApiWrapper\Auth;
use RahulGodiyal\PhpUpsApiWrapper\Entity\ShipQuery;
use RahulGodiyal\PhpUpsApiWrapper\Utils\HttpClient;
use Rhymedigital\IsotopeUpsShipping\Entity\UpsApi\Entity\RateRequest;

class Rating extends Auth
{
    private const VERSION = 'v2403';

    private ShipQuery   $query;
    private RateRequest $rateRequest;
    private object      $apiResponse;

    public function __construct()
    {
        $this->query = new ShipQuery();
    }

    public function setQuery(ShipQuery $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery(): ShipQuery
    {
        return $this->query;
    }

    public function setRateRequest(RateRequest $rateRequest): self
    {
        $this->rateRequest = $rateRequest;

        return $this;
    }

    public function getRateRequest(): RateRequest
    {
        return $this->rateRequest;
    }

    public function getRate(string $client_id, string $client_secret, string $serviceCode = ''): array
    {
        $auth = $this->authenticate($client_id, $client_secret);

        if (($auth['status'] ?? 'fail') === 'fail') {
            return $auth;
        }

        $access_token = $auth['access_token'];

        $queryParams = '';
        if ($this->query->exists()) {
            $queryParams = '?'.http_build_query($this->query->toArray());
        }

        $httpClient = new HttpClient();
        $httpClient->setHeader([
            'Authorization: Bearer '.$access_token,
            'Content-Type: application/json',
        ]);
        $httpClient->setPayload($this->getRequest());
        $httpClient->setUrl($this->_getAPIBaseURL().'/api/rating/'.self::VERSION.($serviceCode ? '/rate' : '/shop').$queryParams);
        $httpClient->setMethod('POST');
        $this->apiResponse = $res = $httpClient->fetch();

        if (!isset($res->RateResponse)) {
            if (isset($res->response)) {
                $error = $res->response->errors[0]->message;
            }
            else {
                $error = 'Rating request failed! Please try again.';
            }

            return ['status' => 'fail', 'msg' => $error];
        }

        if (!isset($res->RateResponse->RatedShipment)) {
            return ['status' => 'fail', 'msg' => 'Invalid Request.'];
        }

        return ['status' => 'success', 'data' => $res];
    }

    /**
     * @param string $mode
     * @return $this
     */
    public function setMode(string $mode): self
    {
        parent::setMode($mode);

        return $this;
    }

    /**
     * @return string
     * @throws \JsonException
     */
    public function getRequest(): string
    {
        return \json_encode(
            [
                'RateRequest' => $this->rateRequest->toArray(),
            ],
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * @throws JsonException
     */
    public function getResponse(): string
    {
        return json_encode($this->apiResponse, JSON_THROW_ON_ERROR);
    }
}
