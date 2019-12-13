<?php

namespace CoreAlg;

class Curl
{
    public function __construct()
    {
        # code...
    }

    /**
     * This function will post request via curl
     * @param string $url The url where you want to post your data
     * @param array $data The data you want to post
     * @param array $customOptions If you need custom header/option for this request you can add it as array
     * @return array $response
     */
    public function post(string $url, array $data = [], array $customOptions = [])
    {
        $defaultOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_TIMEOUT => 0,
        ];

        if (count($data) > 0) {
            $defaultOptions[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        $response = $this->request($defaultOptions, $customOptions);

        return $response;
    }

    /**
     * This function will patch request via curl
     * @param string $url The url where you want to patch
     * @param array $data The data you want to patch
     * @param array $customOptions If you need custom header/option for this request you can add it as array
     * @return array $response
     */
    public function patch(string $url, array $data = [], array $customOptions = [])
    {
        $defaultOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30
        ];

        if (count($data) > 0) {
            $defaultOptions[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        $response = $this->request($defaultOptions, $customOptions);

        return $response;
    }

    /**
     * This function will process request via curl
     * @param string $url The url from where you want to get your require data
     * @param array $customOptions If you need custom header/option for this request you can add it as array
     * @return array $response
     */
    public function get(string $url, array $customOptions = [])
    {
        $defaultOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];

        $response = $this->request($defaultOptions, $customOptions);

        return $response;
    }

    /**
     * This function will get content length
     * @param string $url The url from where you want to get your require data
     * @param array $customOptions If you need custom header/option for this request you can add it as array
     * @return int $response
     */
    public function getFileSize(string $url, array $customOptions = [])
    {
        $defaultOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => request()->Header('User-Agent')
        ];

        $response = $this->request($defaultOptions, $customOptions);

        $content_length = 0;

        $rawResponse = $response["rawResponse"] ?? null;

        if ($response["status"] === "success" && is_null($rawResponse) === false) {

            // $statusCode = $response["code"];

            // if (preg_match("/^HTTP\/1\.[01] (\d\d\d)/", $rawResponse, $matches)) {
            //     $status = (int) $matches[1];
            // }

            if (preg_match("/Content-Length: (\d+)/", $rawResponse, $matches)) {
                $content_length = (int) $matches[1];
            }

            // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            // if ($statusCode == 200 || ($statusCode > 300 && $statusCode <= 308)) {
            //     $result = $content_length;
            // }
        }

        return $content_length;
    }

    /**
     * This function will execure curl and prepare response
     * @param array $options Pass curl option
     * @param array $customOptions Custom option (nullable)
     * @return array $output
     */
    private function request(array $options, array $customOptions = []): array
    {
        $output = [];

        if (count($customOptions) > 0) {
            foreach ($customOptions as $key => $value) {
                $options[$key] = $value;
            }
        }

        // init curl
        $curl = curl_init();

        // set curl options
        curl_setopt_array($curl, $options);

        // execute curl
        $result = curl_exec($curl);

        // get http code
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($http_code === 0) {
            $http_code = 400;
        }

        // check for error
        if (curl_errno($curl) > 0) {
            // found curl error, prepare errour output
            $output = [
                "status" => "error",
                "code" => $http_code,
                "message" => curl_error($curl),
                "rawResponse" => $result,
                "data" => []
            ];
        } else {

            // no error found, let's process response
            $response = (is_null($result) === false) ? json_decode($result, true) : [];

            $status = $http_code === 200 ? "success" : "error";

            $output = [
                "status" => $status,
                "code" => $http_code,
                "message" => "",
                "rawResponse" => $result,
                "data" => $response
            ];
        }

        // close curl
        curl_close($curl);

        return $output;
    }
}