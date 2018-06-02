# Open exchange rate bundle

Symony 2.8 + compatible bundle for fetching foreign exchange rates from openexchangerates.com.

**Installation**
1) Add `qbil/open-exchange-rate-bundle` to `composer.json` file
2) Run `composer update`
3) Register bundle by adding following line to `AppKernel.php`

        new Qbil\OpenExchangeRateBundle\QbilOpenExchangeRateBundle()
    
4) Add `qbil_open_exchange_rate.app_id: APP_ID` and `qbil_open_exchange_rate.base_currency: BASE_CURRENCY_SYMBOL` to `parameters.yml` 
where APP_ID is you app_id (See https://docs.openexchangerates.org/docs/authentication for more info about app_id) 
and BASE_CURRENCY_SYMBOL is you base currency (See https://docs.openexchangerates.org/docs/set-base-currency for more info about base currency).
Default base currency is USD. If you dont wish to change base currency, set it to `~`
5) You are ready to use the bundle now.

**Usage**

Open exchange rate bundle comes with a service with id `qbil_open_exchange_rate.exchange`, that means you can access it anywhere where you have access to service container or you can inject it to another services via Dependency Injection provided by symfony.

For example,

In you controller you can get instance of `qbil_open_exchange_rate.exchange` service by `$this-get('qbil_open_exchange_rate.exchange')`

The service provides two public methods `latest` and `historical` to get latest and historical (fx rate of a particular date) foreign exchange rates from openexchangerates.com. More methods will be added soon

Both methods accept an associative array as argument with following keys

1) `symbols` or `currency` -  Get foreign exchange rates of only give currencies (in comma separated format). 
<br /> <br /> For example <br /> `$this-get('qbil_open_exchange_rate.exchange')->latest(['symbols' => 'USD,EUR,GBP'])` will return an array of latest foreign exchange rates of currencies USD, EUR and GBP
2) `base` - Even if you specified base currency in `parameters.yml` file, you can override it in each request adding `base` key to arguments array
<br /> <br /> For example <br /> `$this-get('qbil_open_exchange_rate.exchange')->latest(['base' => 'GBP'])`  will return an array of latest foreign exchange rates with respect to base currency GBP

`historical` method has a required argument key `date` (yyyy-mm-dd format), i.e. the date of which foreign exchange rates you want to fetch .e.g. `$this-get('qbil_open_exchange_rate.exchange')->latest(['date' => '2017-10-01'])` will return an array of exchange rates of date 2017-10-01 .