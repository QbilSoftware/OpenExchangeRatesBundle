<?php
/**
 * Created by PhpStorm.
 * User: faizan
 * Date: 1/6/18
 * Time: 6:36 PM
 */

namespace Qbil\OpenExchangeRateBundle\Domain;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Exchange
 * @method array latest(array $args = []) Get latest foreign rates from openexhangerates.com
 * @method array historical(array $args = []) Get foreign rates of a particular date from openexhangerates.com
 */
class Exchange
{
    private $appId;

    /** @var Client */
    private $client;

    private $options = [];

    public function __construct($appId, $baseCurrency = null)
    {
        $this->appId = $appId;
        $this->client = new Client([
            'base_uri' => 'https://openexchangerates.org/api',
        ]);

        $this->options = [
            'query' => [
                'app_id' => $this->appId,
            ]
        ];

        if ($baseCurrency) {
            $this->options['query']['base'] = $baseCurrency;
        }
    }

    public function __call($name, $arguments)
    {
        if (!in_array($name, ["latest", "historical"])) {
            throw new \BadMethodCallException("Call to undefined method $name. Valid methods are `latest` and `historical`");
        }

        $path = "/$name";

        if ($arguments[0]['symbols'] || $arguments[0]['currency']) {
            $this->options['query']['symbols'] = $arguments[0]['symbols'] ?: $arguments[0]['currency'];
        }

        if ("historical" === $name) {
            if (!$arguments[0]['date']) {
                throw new \InvalidArgumentException('Required parameter `date` is missing');
            }

            $path .= "/{$arguments[0]['date']}";
        }

        try {
            $response = $this
                ->client
                ->request('GET', "$path.json", $this->options);
        } catch (GuzzleException $e) {
            return json_encode($e->getMessage());
        }

        return json_decode($response->getBody()->getContents(), true)['rates'];
    }
}