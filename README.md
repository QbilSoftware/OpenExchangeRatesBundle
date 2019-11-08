# Open exchange rates bundle

Symfony 4+ compatible bundle for fetching foreign exchange rates from openexchangerates.com. Symfony wrapper for `qbil-software/openexchangerates` package

**Installation**
1) Add `qbil-software/openexchangeratesbundle` to `composer.json` file
2) Run `composer update`
3) Register bundle by adding following line to `AppKernel.php`

        new Qbil\OpenExchangeRateBundle\QbilOpenExchangeRatesBundle()
    
4) Add `qbil_open_exchange_rates.app_id: APP_ID` and `qbil_open_exchange_rates.base_currency: BASE_CURRENCY_SYMBOL` to `parameters.yml` 
where APP_ID is you app_id (See https://docs.openexchangerates.org/docs/authentication for more info about app_id) 
and BASE_CURRENCY_SYMBOL is you base currency (See https://docs.openexchangerates.org/docs/set-base-currency for more info about base currency).
Default base currency is USD. If you dont wish to change base currency, set it to `~`
5) You are ready to use the bundle now.

**Usage**

Open exchange rates bundle comes with a service with id `Qbil\OpenExchangeRates\Exchange`, which can be injected into another services via dependency injection provided by symfony.

**Methods**

The service provides five public methods `latest`, `historical`, `between`, `convert` and `currencies`.

All methods except `currencies` (which accepts no argument) can accept an associative array as argument with following keys

1) `symbols` or `currencies` -  Get foreign exchange rates of only give currencies (in comma separated format). 
<br /> <br /> For example <br /> `$this-get('qbil_open_exchange_rates.exchange')->latest(['symbols' => 'USD,EUR,GBP'])` will return an array of latest foreign exchange rates of currencies USD, EUR and GBP
2) `base` - Even if you specified base currency in `parameters.yml` file, you can override it in each request adding `base` key to arguments array
<br /> <br /> For example <br /> `$this-get('qbil_open_exchange_rates.exchange')->latest(['base' => 'GBP'])`  will return an array of latest foreign exchange rates with respect to base currency GBP

**Methods explained** 
1) `latest` - This method fetches latest foreign exchange rates. e.g. `$this-get('qbil_open_exchange_rates.exchange')->latest()` will return an array of latest exchange rates.

2) `historical` - This method fetches foreign exchange rates of a particular date. It has a required argument key `date` (yyyy-mm-dd format), i.e. the date of which foreign exchange rates you want to fetch .e.g. `$this-get('qbil_open_exchange_rates.exchange')->historical(['date' => '2017-10-01'])` will return an array of exchange rates of date 2017-10-01.

3) `between` - This method fetches foreign exchange rates between particular dates specified by arguments key `start` and `end` (both in yyyy-mm-dd format) .e.g. `$this-get('qbil_open_exchange_rates.exchange')->between(['start' => '2017-10-01', 'end' => '2017-11-05'])` will return an array of exchange rates of between 2017-10-01 and 2017-11-05.

4) `convert` - This method is used to convert any money value from one currency to another at the latest foreign exchange rates. It has three required argument keys `amount`, `from` and `to`. `from` and `to` are currency codes (3 letters) and `amount` is the amount you want to convert. e.g. `$this-get('qbil_open_exchange_rates.exchange')->between(['amount' => '15678800', 'from' => 'USD', 'to' => 'EUR'])` will return equivalent amount in EUR (as string)

5) `currencies` - This method returns array of all supported currencies with symbol as their key and currency as their value.

Note: Some of the above methods are only available in enterprise or ultimate edition. Please visit https://openexchangerates.com for more info
