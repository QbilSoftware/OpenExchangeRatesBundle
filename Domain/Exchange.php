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
 */
class Exchange
{
    /**
     * @var
     */
    private $appId;

    /** @var Client */
    private $client;

    /**
     * @var array
     */
    private $options = [];

    /**
     * Exchange constructor.
     * @param $appId
     * @param null $baseCurrency
     */
    public function __construct($appId, $baseCurrency = null)
    {
        $this->options = [];
        $this->appId = $appId;
        $this->client = new Client([
            'base_uri' => 'https://openexchangerates.org/api',
        ]);

        $this->options['app_id'] = $this->appId;

        if ($baseCurrency) {
            $this->options['base'] = $baseCurrency;
        }
    }

    /**
     * @param array $args
     * @return array
     * @throws GuzzleException
     */
    public function latest(array $args = [])
    {
        return $this->fetch('/latest.json', ['query' => array_merge($args, $this->options)]);
    }


    /**
     * @param array $args
     * @return array
     * @throws GuzzleException
     */
    public function historical(array $args)
    {
        if (!$args['date']) {
            throw new \InvalidArgumentException('Required parameter `date` is missing');
        }

        return $this->fetch("/historical/{$args['date']}.json", ['query' => array_merge($args, $this->options)]);
    }

    /**
     * @param array $args
     * @return array
     * @throws GuzzleException
     */
    public function between(array $args)
    {
        if (!$args['start']) {
            throw new \InvalidArgumentException('Required parameter `start` is missing');
        }

        if (!$args['end']) {
            throw new \InvalidArgumentException('Required parameter `end` is missing');
        }

        return $this->fetch("/time-series.json", ['query' => array_merge($args, $this->options)]);
    }

    /**
     * @param array $args
     * @return string
     * @throws GuzzleException
     */
    public function convert(array $args)
    {
        if (!$args['amount']) {
            throw new \InvalidArgumentException('Required parameter `amount` is missing');
        }

        if (!$args['from']) {
            throw new \InvalidArgumentException('Required parameter `from` is missing');
        }

        if (!$args['to']) {
            throw new \InvalidArgumentException('Required parameter `to` is missing');
        }

        return $this->fetch("/historical/{$args['amount']}/{$args['from']}/{$args['to']}.json", ['query' => array_merge($args, $this->options)], 'response');
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function currencies()
    {
        return json_decode(
            $this
                ->client
                ->request('GET', '/currencies.json')
                ->getBody()
                ->getContents(),
            true
        );
    }

    /**
     * @param $route
     * @param array $options
     * @param $responseKey
     * @return array|string
     * @throws GuzzleException
     */
    private function fetch($route, array $options, $responseKey = 'rates')
    {
        $response = json_decode(
            $this
                ->client
                ->request('GET', $route, $options)
                ->getBody()
                ->getContents(),
            true
        );

        return $response[$responseKey];
    }
}