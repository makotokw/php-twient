<?php

namespace Twient\Auth\OAuth;

use Twient\Exception;

class Request
{
    const OAUTH_VERSION = '1.0';

    /**
     * @var array|null
     */
    private $parameters;

    /**
     * @var string
     */
    private $httpMethod;

    /**
     * @var string
     */
    private $httpUrl;

    // for debug purposes
    /**
     * @var string
     */
    public $baseString;

    public function __construct($httpMethod, $httpUrl, $parameters = null)
    {
        @$parameters or $parameters = array();
        $this->parameters = $parameters;
        $this->httpMethod = $httpMethod;
        $this->httpUrl = $httpUrl;
    }


    /**
     * attempt to build up a request from what was passed to the server
     */
    public static function fromRequest($httpMethod = null, $httpUrl = null, $parameters = null)
    {
        $scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
            ? 'http'
            : 'https';
        @$httpUrl or $httpUrl = $scheme .
            '://' . $_SERVER['HTTP_HOST'] .
            ':' .
            $_SERVER['SERVER_PORT'] .
            $_SERVER['REQUEST_URI'];
        @$httpMethod or $httpMethod = $_SERVER['REQUEST_METHOD'];

        // We weren't handed any parameters, so let's find the ones relevant to
        // this request.
        // If you run XML-RPC or similar you should use this to provide your own
        // parsed parameter-list
        if (!$parameters) {
            // Find request headers
            $requestHeaders = Util::getHeaders();

            // Parse the query-string to find GET parameters
            $parameters = Util::parseParameters($_SERVER['QUERY_STRING']);

            // It's a POST request of the proper content-type, so parse POST
            // parameters and add those overriding any duplicates from GET
            if ($httpMethod == "POST"
                && @strstr(
                    $requestHeaders["Content-Type"],
                    "application/x-www-form-urlencoded"
                )
            ) {
                $post_data = Util::parseParameters(
                    file_get_contents('php://input')
                );
                $parameters = array_merge($parameters, $post_data);
            }

            // We have a Authorization-header with OAuth data. Parse the header
            // and add those overriding any duplicates from GET or POST
            if (@substr($requestHeaders['Authorization'], 0, 6) == "OAuth ") {
                $headerParameters = Util::splitHeader(
                    $requestHeaders['Authorization']
                );
                $parameters = array_merge($parameters, $headerParameters);
            }

        }

        return new Request($httpMethod, $httpUrl, $parameters);
    }

    /**
     * pretty much a helper function to set up the request
     */
    public static function fromConsumerAndToken($consumer, $token, $httpMethod, $httpUrl, $parameters = null)
    {
        @$parameters or $parameters = array();
        $defaults = array(
            "oauth_version" => self::OAUTH_VERSION,
            "oauth_nonce" => self::generateNonce(),
            "oauth_timestamp" => self::generateTimestamp(),
            "oauth_consumer_key" => $consumer->key
        );
        if ($token) {
            $defaults['oauth_token'] = $token->key;
        }

        $parameters = array_merge($defaults, $parameters);

        return new Request($httpMethod, $httpUrl, $parameters);
    }

    public function setParameter($name, $value, $allowDuplicates = true)
    {
        if ($allowDuplicates && isset($this->parameters[$name])) {
            // We have already added parameter(s) with this name, so add to the list
            if (is_scalar($this->parameters[$name])) {
                // This is the first duplicate, so transform scalar (string)
                // into an array so we can add the duplicates
                $this->parameters[$name] = array($this->parameters[$name]);
            }
            $this->parameters[$name][] = $value;
        } else {
            $this->parameters[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParameter($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $name
     */
    public function unsetParameter($name)
    {
        unset($this->parameters[$name]);
    }

    /**
     * The request parameters, sorted and concatenated into a normalized string.
     * @return string
     */
    public function getSignableParameters()
    {
        // Grab all parameters
        $params = $this->parameters;

        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        if (isset($params['oauth_signature'])) {
            unset($params['oauth_signature']);
        }

        return Util::buildHttpQuery($params);
    }

    /**
     * Returns the base string of this request
     *
     * The base string defined as the method, the url
     * and the parameters (normalized), each urlencoded
     * and the concated with &.
     */
    public function getSignatureBaseString()
    {
        $parts = array(
            $this->getNormalizedHttpMethod(),
            $this->getNormalizedHttpUrl(),
            $this->getSignableParameters()
        );

        $parts = Util::urlencodeRfc3986($parts);

        return implode('&', $parts);
    }

    /**
     * just uppercases the http method
     * @return string
     */
    public function getNormalizedHttpMethod()
    {
        return strtoupper($this->httpMethod);
    }

    /**
     * parses the url and rebuilds it to be
     * scheme://host/path
     * @return string
     */
    public function getNormalizedHttpUrl()
    {
        $parts = parse_url($this->httpUrl);

        $port = @$parts['port'];
        $scheme = $parts['scheme'];
        $host = $parts['host'];
        $path = @$parts['path'];

        $port or $port = ($scheme == 'https') ? '443' : '80';

        if (($scheme == 'https' && $port != '443')
            || ($scheme == 'http' && $port != '80')
        ) {
            $host = "$host:$port";
        }
        return "$scheme://$host$path";
    }

    /**
     * builds a url usable for a GET request
     * @return string
     */
    public function toUrl()
    {
        $post_data = $this->toPostData();
        $out = $this->getNormalizedHttpUrl();
        if ($post_data) {
            $out .= '?' . $post_data;
        }
        return $out;
    }

    /**
     * builds the data one would send in a POST request
     * @return string
     */
    public function toPostData()
    {
        return Util::buildHttpQuery($this->parameters);
    }

    /**
     * builds the Authorization: header
     * @return string
     * @throws \Twient\Exception
     */
    public function toHeader()
    {
        $out = 'Authorization: OAuth realm=""';
        foreach ($this->parameters as $k => $v) {
            if (substr($k, 0, 5) != "oauth") {
                continue;
            }
            if (is_array($v)) {
                throw new Exception('Arrays not supported in headers');
            }
            $out .= ',' .
                Util::urlencodeRfc3986($k) .
                '="' .
                Util::urlencodeRfc3986($v) .
                '"';
        }
        return $out;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toUrl();
    }

    /**
     * @param SignatureMethod $signatureMethod
     * @param $consumer
     * @param $token
     */
    public function signRequest($signatureMethod, $consumer, $token)
    {
        $this->setParameter(
            "oauth_signature_method",
            $signatureMethod->getName(),
            false
        );
        $signature = $this->buildSignature($signatureMethod, $consumer, $token);
        $this->setParameter("oauth_signature", $signature, false);
    }

    /**
     * @param SignatureMethod $signatureMethod
     * @param $consumer
     * @param $token
     * @return mixed
     */
    public function buildSignature($signatureMethod, $consumer, $token)
    {
        $signature = $signatureMethod->buildSignature($this, $consumer, $token);
        return $signature;
    }

    /**
     * @return int
     */
    private static function generateTimestamp()
    {
        return time();
    }

    /**
     * util function: current nonce
     * @return string
     */
    private static function generateNonce()
    {
        $mt = microtime();
        $rand = mt_rand();
        return md5($mt . $rand); // md5s look nicer than numbers
    }
}
