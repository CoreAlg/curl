# Curl client for PHP


## Install

Via Composer

``` bash
$ composer require corealg/curl
```

### Quick Start and Examples

```php
# GET Request

use CoreAlg\Curl;

$curl = new Curl();
$response = $curl->get('https://www.lipsum.com/');
var_dump($response)
```

```php
# GET Request With Custom Options

use CoreAlg\Curl;

$curl = new Curl();

$options = [
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer TOKEN"
    ]
    // You can add options as many as you need
];

$response = $curl->get('https://www.lipsum.com/', $options);
var_dump($response)
```

```php
# Get Content Length Via GET Request

use CoreAlg\Curl;

$curl = new Curl();

// Set custom options and send it to getFileSize function as second argument [OPTIONAL]
// $options = [
//     CURLOPT_HTTPHEADER => [
//         "Content-Type: application/json",
//         "Authorization: Bearer TOKEN"
//     ]
//     // You can add options as many as you need
// ];

$response = $curl->getFileSize("https://dummyimage.com/250/ffffff/000000");
var_dump($response)
```

```php
# POST Request

use CoreAlg\Curl;

$curl = new Curl();

$data = [
    // Your data array
];

$options = [];

$response = $curl->post('https://www.lipsum.com/', $data, $options);
var_dump($response)

*NOTE: $data and $options both are optional parameter

*NOTE: $data array will be converted to json before execute request, so you do not need to worry about it just feel free to make and pass your $data array
```

```php
# PATCH Request

use CoreAlg\Curl;

$curl = new Curl();

$data = [
    // Your data array
];

$options = [];

$response = $curl->patch('https://www.lipsum.com/', $data, $options);
var_dump($response)

*NOTE: $data and $options both are optional parameter

*NOTE: $data array will be converted to json before execute request, so you do not need to worry about it just feel free to make and pass your $data array
```