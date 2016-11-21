<?php
namespace MVC\Models;

use \MVC\Singleton;

class GeoCoding
{
    use Singleton;

    const SERVICE_REQUEST_METHOD_GET = 'GET';
    const SERVICE_REQUEST_METHOD_POST = 'POST';
    const SERVICE_REQUEST_METHOD_PUT = 'PUT';
    const SERVICE_REQUEST_METHOD_DELETE = 'DELETE';

    const API_URL = "http://maps.googleapis.com/maps/api/geocode/json";

    private static $ch = null;

    // proxy
    protected $str_proxy = "";

    public function __construct()
    {
        if (is_null(self::$ch)) {
            self::$ch = curl_init();

            // proxy
            self::$proxy = explode("\n", $this->str_proxy);
            if (!empty(self::$proxy)) {
                foreach (self::$proxy as &$item) {
                    $item = trim($item);
                }
            }
        }
    }

    public function __destruct()
    {
        if (!is_null(self::$ch)) {
            curl_close(self::$ch);
            self::$ch = null;
        }
    }

    public static $response;

    /**
     * @param $address
     * @param $zip_code
     * @return array
     * @throws \Exception
     */
    public function getLocation($address, $zip_code = null)
    {
        if ($zip_code) {
            $address .= ' , ' . $zip_code;
        }
        $args = [
            'address' => $address,
        ];
        $response = $this->getJsonRequest(self::API_URL, $args);
        self::$response = json_encode($response);

        if ($response->body->status != 'OK') {
            if ($response->body->status == 'OVER_QUERY_LIMIT') {
                if ($this->nextProxy()) {
                    return $this->getLocation($address, $zip_code); // goto
                }
            }
            throw new \Exception($response->body->status);
        }

        $result = $response->body->results[0];
        $acs = $result->address_components;
        $state = '';
        foreach ($acs as $ac) {
            foreach ($ac->types as $type) {
                if ($type == 'administrative_area_level_1') {
                    $state = $ac->long_name;
                    break 2;
                }
            }
        }

        $country = '';
        foreach ($acs as $ac) {
            foreach ($ac->types as $type) {
                if ($type == 'country') {
                    $country = $ac->short_name;
                }
            }
        }

        if ($country == 'PR') {
            $state = 'Puerto Rico (Unincorporated territory)';
        }

        if (isset($response->body->results[1])) {
            throw new \Exception('Found several addresses');
        }

        return [
            $result->geometry->location->lat,
            $result->geometry->location->lng,
            $response->body->results[0]->formatted_address,
            $state,
            $country,
        ];

    }

    protected function getJsonRequest($uri, array $args = [], array $headers = [])
    {
        $httpQuery = http_build_query($args);
        $response = $this->doRequest(
            $uri,
            self::SERVICE_REQUEST_METHOD_GET,
            $httpQuery,
            $headers
        );
        if (!empty($response->body)) {
            $response->body = json_decode($response->body);
        }

        return $response;
    }

    public static $proxy = [];

    protected function doRequest(
        $url,
        $method = self::SERVICE_REQUEST_METHOD_GET,
        $body = null,
        array $headers = []
    ) {
        if (is_null(self::$ch)) {
            throw new \Exception('CUrl-resource is null');
        }

        if ($method == self::SERVICE_REQUEST_METHOD_GET) {
            $url .= '?' . $body;
        }

        curl_setopt_array(
            self::$ch,
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => $headers,
            ]
        );

        switch ($method) {
            case self::SERVICE_REQUEST_METHOD_POST:
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, self::SERVICE_REQUEST_METHOD_POST);
                curl_setopt(self::$ch, CURLOPT_POST, true);
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $body);
                break;
            case self::SERVICE_REQUEST_METHOD_PUT:
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, self::SERVICE_REQUEST_METHOD_PUT);
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $body);
                break;
            case self::SERVICE_REQUEST_METHOD_DELETE:
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, self::SERVICE_REQUEST_METHOD_DELETE);
                break;
            default: // case self::SERVICE_REQUEST_METHOD_GET:
                curl_setopt(self::$ch, CURLOPT_POST, false);
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, self::SERVICE_REQUEST_METHOD_GET);
        }

        if (isset(self::$proxy[0])) {
            curl_setopt(self::$ch, CURLOPT_PROXY, self::$proxy[0]); // proxy
        }

        $result = curl_exec(self::$ch);
        $httpStatus = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
        $err = curl_error(self::$ch);

        if ($httpStatus != 200) {
            if ($this->nextProxy()) {
                return $this->doRequest($url, $method, $body, $headers); // goto
            }
        }

        if ($err) {
            throw new \Exception($err, $httpStatus);
        }
        if ($httpStatus != 200) {
            throw new \Exception('ERROR_STATUS', $httpStatus);
        }

        return (object)[
            'code' => $httpStatus,
            'body' => $result,
        ];
    }

    protected function nextProxy()
    {
        if (isset(self::$proxy[0])) {
            array_shift(self::$proxy);

            return true;
        }

        return false;
    }

}
