<?php


namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiConnector
{
    protected static $id = 0;

    public function __construct(protected ParameterBagInterface $params, private HttpClientInterface $httpClient)
    {
    }

    public function getUserBalance(int $userId)
    {
        $endpoint = $this->params->get('balance_service_endpoint');
        $request = $this->httpClient->request(
            'POST',
            $endpoint,
            [
                'json' => [
                    "jsonrpc" => "2.0",
                    "method" => "balance.userBalance",
                    "params" => [
                        "user_id" => $userId
                    ],
                    "id" => ++self::$id
                ]
            ]
        );
        return json_decode($request->getContent())->result;
    }

    public function getHistory(int $userId, int $limit = 50)
    {
        $endpoint = $this->params->get('balance_service_endpoint');
        $request = $this->httpClient->request(
            'POST',
            $endpoint,
            [
                'json' => [
                    "jsonrpc" => "2.0",
                    "method" => "balance.history",
                    "params" => [
                        "user_id" => $userId,
                        "limit" => $limit
                    ],
                    "id" => ++self::$id
                ]
            ]
        );
        return json_decode($request->getContent())->result;
    }
}
