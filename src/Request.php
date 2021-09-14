<?php

/**
 * @package php-http-request
 * @link https://github.com/bayfrontmedia/php-http-request
 * @author John Robinson <john@bayfrontmedia.com>
 * @copyright 2020 Bayfront Media
 */

namespace Bayfront\HttpRequest;

use Bayfront\ArrayHelpers\Arr;
use Bayfront\Validator\Validate;

class Request
{

    /*
     * ############################################################
     * Request methods
     * ############################################################
     */

    // Valid request methods

    public const METHOD_CONNECT = 'CONNECT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_GET = 'GET';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_TRACE = 'TRACE';

    /**
     * Returns valid request method with a fallback to GET.
     *
     * @param string $method
     *
     * @return string
     */

    public static function validateMethod(string $method): string
    {

        $method = strtoupper($method);

        switch ($method) {

            case self::METHOD_CONNECT:
            case self::METHOD_DELETE:
            case self::METHOD_GET:
            case self::METHOD_HEAD:
            case self::METHOD_OPTIONS:
            case self::METHOD_PATCH:
            case self::METHOD_POST:
            case self::METHOD_PUT:
            case self::METHOD_TRACE:

                return $method;

            default:

                return self::METHOD_GET; // Fallback

        }

    }

    /**
     * Returns current request method.
     *
     * @return string
     */

    public static function getMethod(): string
    {
        return self::validateMethod(self::getServer('REQUEST_METHOD'));
    }

    /**
     * Is current request method CONNECT.
     *
     * @return bool
     */

    public static function isConnect(): bool
    {
        return self::getMethod() == self::METHOD_CONNECT;
    }

    /**
     * Is current request method DELETE.
     *
     * @return bool
     */

    public static function isDelete(): bool
    {
        return self::getMethod() == self::METHOD_DELETE;
    }

    /**
     * Is current request method GET.
     *
     * @return bool
     */

    public static function isGet(): bool
    {
        return self::getMethod() == self::METHOD_GET;
    }

    /**
     * Is current request method HEAD.
     *
     * @return bool
     */

    public static function isHead(): bool
    {
        return self::getMethod() == self::METHOD_HEAD;
    }

    /**
     * Is current request method OPTIONS.
     *
     * @return bool
     */

    public static function isOptions(): bool
    {
        return self::getMethod() == self::METHOD_OPTIONS;
    }

    /**
     * Is current request method PATCH.
     *
     * @return bool
     */

    public static function isPatch(): bool
    {
        return self::getMethod() == self::METHOD_PATCH;
    }

    /**
     * Is current request method POST.
     *
     * @return bool
     */

    public static function isPost(): bool
    {
        return self::getMethod() == self::METHOD_POST;
    }

    /**
     * Is current request method PUT.
     *
     * @return bool
     */

    public static function isPut(): bool
    {
        return self::getMethod() == self::METHOD_PUT;
    }

    /**
     * Is current request method TRACE.
     *
     * @return bool
     */

    public static function isTrace(): bool
    {
        return self::getMethod() == self::METHOD_TRACE;
    }

    /*
     * ############################################################
     * Data types
     * ############################################################
     */

    /**
     * Returns value of request type array key in dot notation or entire array, with optional default value.
     *
     * @param string $type (COOKIE, FILE, GET, POST, SERVER, HEADER)
     * @param string|null $key
     * @param null $default
     *
     * @return mixed
     */

    private static function _getType(string $type, string $key = NULL, $default = NULL)
    {

        // -------------------- Get correct type --------------------

        $type = strtoupper($type);

        $array = NULL;

        switch ($type) {

            case 'COOKIE':

                if (isset($_COOKIE)) {

                    $array = $_COOKIE;

                }

                break;

            case 'FILE':

                if (isset($_FILES)) {

                    $array = $_FILES;

                }

                break;

            case 'GET':

                if (isset($_GET)) {

                    $array = $_GET;

                }

                break;

            case 'POST':

                if (isset($_POST)) {

                    $array = $_POST;

                }

                break;

            case 'SERVER':

                if (isset($_SERVER)) {

                    $array = $_SERVER;

                }

                break;

            case 'HEADER':

                if (function_exists('getallheaders')) {

                    $array = getallheaders();

                }

                break;

        }

        // -------------------- Return the value --------------------

        if (NULL === $array) { // Type does not exist

            return NULL;

        }

        if (NULL === $key) { // Return the entire array

            return $array;

        }

        return Arr::get($array, $key, $default); // Return a specific key

    }

    /**
     * Returns value of single $_FILES array key in dot notation or entire array, with optional default value.
     *
     * @param string|null $key
     * @param mixed $default (Default value to return if array key is not found)
     *
     * @return mixed
     */

    public static function getFile(string $key = NULL, $default = NULL)
    {
        return self::_getType('FILE', $key, $default);
    }

    /**
     * Checks if $_FILES array key exists in dot notation.
     *
     * @param string $key
     *
     * @return bool
     */

    public static function hasFile(string $key): bool
    {
        return (bool)self::getFile($key);
    }

    /**
     * Returns value of single $_GET array key in dot notation or entire array, with optional default value.
     *
     * @param string|null $key
     * @param mixed $default (Default value to return if array key is not found)
     *
     * @return mixed
     */

    public static function getQuery(string $key = NULL, $default = NULL)
    {
        return self::_getType('GET', $key, $default);
    }

    /**
     * Checks if $_GET array key exists in dot notation.
     *
     * @param string $key
     *
     * @return bool
     */

    public static function hasQuery(string $key): bool
    {
        return (bool)self::getQuery($key);
    }

    /**
     * Returns value of single $_POST array key in dot notation or entire array, with optional default value.
     *
     * @param string|null $key
     * @param mixed $default (Default value to return if array key is not found)
     *
     * @return mixed
     */

    public static function getPost(string $key = NULL, $default = NULL)
    {
        return self::_getType('POST', $key, $default);
    }

    /**
     * Checks if $_POST array key exists in dot notation.
     *
     * @param string $key
     *
     * @return bool
     */

    public static function hasPost(string $key): bool
    {
        return (bool)self::getPost($key);
    }

    /**
     * Returns value of single $_SERVER array key in dot notation or entire array, with optional default value.
     *
     * @param string|null $key
     * @param mixed $default (Default value to return if array key is not found)
     *
     * @return mixed
     */

    public static function getServer(string $key = NULL, $default = NULL)
    {
        return self::_getType('SERVER', $key, $default);
    }

    /**
     * Checks if $_SERVER array key exists in dot notation.
     *
     * @param string $key
     *
     * @return bool
     */

    public static function hasServer(string $key): bool
    {
        return (bool)self::getServer($key);
    }

    /**
     * Returns value of single $_COOKIE array key in dot notation or entire array, with optional default value.
     *
     * @param string|null $key
     * @param mixed $default (Default value to return if array key is not found)
     *
     * @return mixed
     */

    public static function getCookie(string $key = NULL, $default = NULL)
    {
        return self::_getType('COOKIE', $key, $default);
    }

    /**
     * Checks if $_COOKIE array key exists in dot notation.
     *
     * @param string $key
     *
     * @return bool
     */

    public static function hasCookie(string $key): bool
    {
        return (bool)self::getCookie($key);
    }

    /**
     * Returns value of single header array key in dot notation or entire array, with optional default value.
     *
     * @param string|null $key
     * @param mixed $default (Default value to return if array key is not found)
     *
     * @return mixed
     */

    public static function getHeader(string $key = NULL, $default = NULL)
    {
        return self::_getType('HEADER', $key, $default);
    }

    /**
     * Checks if header array key exists in dot notation.
     *
     * @param string $key
     *
     * @return bool
     */

    public static function hasHeader(string $key): bool
    {
        return (bool)self::getHeader($key);
    }

    /**
     * Returns content body of a request.
     *
     * @return string
     */

    public static function getBody(): string
    {
        return file_get_contents('php://input');
    }

    /**
     * Checks if content body of a request exists.
     *
     * @return bool
     */

    public static function hasBody(): bool
    {
        return (bool)self::getBody();
    }

    /*
     * ############################################################
     * Specific values
     * ############################################################
     */

    /**
     * Returns client's user agent.
     *
     * @return string|null
     */

    public static function getUserAgent(): ?string
    {
        return self::getServer('HTTP_USER_AGENT');
    }

    /**
     * Returns client's referring URL.
     *
     * @return mixed (string|null)
     */

    public static function getReferer()
    {
        return self::getServer('HTTP_REFERER');
    }

    /**
     * Returns the most probable IP of client with optional default value.
     *
     * @param string $default (Default IP address to return if none detected)
     *
     * @return string
     */

    public static function getIp(string $default = ''): string
    {

        $ip_keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        $server = self::getServer();

        foreach ($ip_keys as $key) {

            if (isset($server[$key])) {

                foreach (explode(',', $server[$key]) as $ip) {

                    $ip = trim($ip);

                    if (Validate::ip($ip)) {

                        if ($ip == '::1') { // Localhost will return ::1

                            return 'localhost';

                        }

                        return $ip;

                    }

                }

            }

        }

        return $default;

    }

    /**
     * Is the request originating from the command line.
     *
     * @return bool
     */

    public static function isCli(): bool
    {

        if (php_sapi_name() === 'cli') {
            return true;
        }

        if (empty(self::getServer('REMOTE_ADDR', [])) && !self::hasServer('HTTP_USER_AGENT')) {
            return true;
        }

        return false;

    }

    /**
     * Is the Content-Type header for this request JSON.
     *
     * @return bool
     */

    public static function isJson(): bool
    {
        return strpos(strtolower(self::getHeader('Content-Type', '')), 'json') !== false;
    }

    /**
     * Does the Accept header for this request expect JSON.
     *
     * @return bool
     */

    public static function wantsJson(): bool
    {
        return strpos(strtolower(self::getHeader('Accept', '')), 'json') !== false;
    }

    /**
     * Is connection HTTPS.
     *
     * @return bool
     */

    public static function isHttps(): bool
    {

        if (self::getServer('HTTPS') == 'on'
            || self::getServer('SERVER_PORT') == 443
            || self::getServer('HTTP_X_FORWARDED_PROTO') == 'https') {
            return true;
        }

        return false;
    }

    /**
     * Returns array containing details of the client's request, or string of a specific part of the request.
     *
     * @param string $part (Which part of the request to return. Leaving this blank will return the entire array)
     *
     * Valid $part values include:
     *      method
     *      protocol
     *      host
     *      path
     *      query
     *      query_string
     *      url
     *      full_url
     *
     * @return mixed (array|string)
     */

    public static function getRequest(string $part = '')
    {

        $return = [];

        // Method

        $return['method'] = self::getMethod();

        // Protocol

        if (self::isHttps()) {

            $return['protocol'] = 'https://';

        } else {

            $return['protocol'] = 'http://';

        }

        // Host

        $return['host'] = self::getServer('HTTP_HOST');

        // Path

        $path = explode('?', self::getServer('REQUEST_URI'), 2);

        $return['path'] = $path[0];

        // Query

        $query = self::getQuery();

        $return['query_string'] = '';

        if (!empty($query)) {

            $return['query'] = $query;

            $return['query_string'] = http_build_query($query);

        } else {

            $return['query'] = [];

        }

        // URL

        $return['url'] = $return['protocol'] . $return['host'] . $return['path'];

        $return['full_url'] = $return['url'];

        if ($return['query_string'] != '') {

            $return['full_url'] = $return['url'] . '?' . $return['query_string'];

        }

        return Arr::get($return, $part, $return); // Return desired key, defaults to entire array

    }

    /**
     * Returns current URL.
     *
     * @param bool $include_query (Include the query string, if existing)
     *
     * @return string
     */

    public static function getUrl(bool $include_query = false): string
    {

        if (false === $include_query) {

            return self::getRequest('url');

        }

        return self::getRequest('full_url');

    }

}