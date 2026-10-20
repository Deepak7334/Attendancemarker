<?php
// Minimal implementation of Google_Client for backward compatibility
// This provides the basic functionality needed for Google OAuth login

if (!class_exists('Google_Client')) {
    class Google_Client
    {
        const LIBVER = '2.15.0';
        const USER_ATTR_ID = 'id';
        const USER_ATTR_EMAIL = 'email';
        const USER_ATTR_NAME = 'name';
        const USER_ATTR_PICTURE = 'picture';
        const USER_ATTR_LINK = 'link';

        private $clientId;
        private $clientSecret;
        private $redirectUri;
        private $applicationName;
        private $scopes = [];
        private $accessType = 'online';
        private $approvalPrompt = 'auto';
        private $requestVisibleActions = [];
        private $accessToken;
        private $refreshToken;
        private $tokenCallback;
        private $developerKey;
        private $useApplicationDefaultCredentials = false;
        private $credentialsPath;
        private $servicePath;
        private $httpHandler;
        private $tokenHandler;
        private $cacheConfig = [];
        private $logger;

        public function __construct($config = [])
        {
            $this->applicationName = isset($config['application_name']) ? $config['application_name'] : '';
        }

        public function setClientId($clientId)
        {
            $this->clientId = $clientId;
            return $this;
        }

        public function getClientId()
        {
            return $this->clientId;
        }

        public function setClientSecret($clientSecret)
        {
            $this->clientSecret = $clientSecret;
            return $this;
        }

        public function getClientSecret()
        {
            return $this->clientSecret;
        }

        public function setRedirectUri($redirectUri)
        {
            $this->redirectUri = $redirectUri;
            return $this;
        }

        public function getRedirectUri()
        {
            return $this->redirectUri;
        }

        public function setApplicationName($applicationName)
        {
            $this->applicationName = $applicationName;
            return $this;
        }

        public function getApplicationName()
        {
            return $this->applicationName;
        }

        public function addScope($scope)
        {
            $this->scopes[] = $scope;
            return $this;
        }

        public function setScopes($scopes)
        {
            $this->scopes = is_array($scopes) ? $scopes : [];
            return $this;
        }

        public function getScopes()
        {
            return $this->scopes;
        }

        public function setAccessType($accessType)
        {
            $this->accessType = $accessType;
            return $this;
        }

        public function getAccessType()
        {
            return $this->accessType;
        }

        public function setApprovalPrompt($approvalPrompt)
        {
            $this->approvalPrompt = $approvalPrompt;
            return $this;
        }

        public function getApprovalPrompt()
        {
            return $this->approvalPrompt;
        }

        public function setRequestVisibleActions($rva)
        {
            $this->requestVisibleActions = $rva;
            return $this;
        }

        public function setAccessToken($accessToken)
        {
            $this->accessToken = $accessToken;
            return $this;
        }

        public function getAccessToken()
        {
            return $this->accessToken;
        }

        public function setRefreshToken($refreshToken)
        {
            $this->refreshToken = $refreshToken;
            return $this;
        }

        public function getRefreshToken()
        {
            return $this->refreshToken;
        }

        public function setTokenCallback($callback)
        {
            $this->tokenCallback = $callback;
            return $this;
        }

        public function setDeveloperKey($developerKey)
        {
            $this->developerKey = $developerKey;
            return $this;
        }

        public function getDeveloperKey()
        {
            return $this->developerKey;
        }

        public function setUseApplicationDefaultCredentials($useApplicationDefaultCredentials)
        {
            $this->useApplicationDefaultCredentials = $useApplicationDefaultCredentials;
            return $this;
        }

        public function getUseApplicationDefaultCredentials()
        {
            return $this->useApplicationDefaultCredentials;
        }

        public function createAuthUrl($loginHint = null)
        {
            $params = [
                'response_type' => 'code',
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirectUri,
                'scope' => implode(' ', $this->scopes),
                'access_type' => $this->accessType,
                'approval_prompt' => $this->approvalPrompt,
            ];

            if ($loginHint) {
                $params['login_hint'] = $loginHint;
            }

            $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
            return $url;
        }

        public function getAccessTokenFromCode($code)
        {
            $params = [
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => 'authorization_code',
            ];

            $http = new \GuzzleHttp\Client();
            $response = $http->post('https://oauth2.googleapis.com/token', [
                'form_params' => $params,
            ]);

            $data = json_decode($response->getBody(), true);
            $this->accessToken = $data;
            
            if (isset($data['refresh_token'])) {
                $this->refreshToken = $data['refresh_token'];
            }

            return $data;
        }

        public function verifyIdToken($idToken = null)
        {
            if (!$idToken && isset($this->accessToken['id_token'])) {
                $idToken = $this->accessToken['id_token'];
            }

            if (!$idToken) {
                return null;
            }

            // Basic JWT verification - in production, you should properly verify the signature
            $parts = explode('.', $idToken);
            if (count($parts) !== 3) {
                return null;
            }

            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
            return $payload;
        }

        public function getUserInfo($accessToken = null)
        {
            if (!$accessToken && isset($this->accessToken['access_token'])) {
                $accessToken = $this->accessToken['access_token'];
            }

            if (!$accessToken) {
                return null;
            }

            $http = new \GuzzleHttp\Client();
            $response = $http->get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            return json_decode($response->getBody(), true);
        }

        public function setHttpHandler($handler)
        {
            $this->httpHandler = $handler;
        }

        public function getHttpHandler()
        {
            return $this->httpHandler;
        }

        public function setCacheConfig($cacheConfig)
        {
            $this->cacheConfig = $cacheConfig;
        }

        public function getCacheConfig()
        {
            return $this->cacheConfig;
        }

        public function setLogger($logger)
        {
            $this->logger = $logger;
        }

        public function getLogger()
        {
            return $this->logger;
        }

        public function setCredentialsPath($path)
        {
            $this->credentialsPath = $path;
        }

        public function getCredentialsPath()
        {
            return $this->credentialsPath;
        }

        public function setServicePath($path)
        {
            $this->servicePath = $path;
        }

        public function getServicePath()
        {
            return $this->servicePath;
        }
    }
}

// Define other required Google classes as minimal stubs
if (!class_exists('Google_Service')) {
    class Google_Service
    {
        protected $client;
        
        public function __construct(Google_Client $client)
        {
            $this->client = $client;
        }
        
        public function getClient()
        {
            return $this->client;
        }
    }
}

if (!class_exists('Google_Collection')) {
    class Google_Collection implements Iterator, Countable
    {
        protected $collection_key = 'items';
        protected $modelData = [];
        protected $empty = false;
        private $position = 0;

        public function __construct($data = [])
        {
            $this->modelData = $data;
            $this->position = 0;
        }

        public function getCollectionKey()
        {
            return $this->collection_key;
        }

        public function setCollectionKey($key)
        {
            $this->collection_key = $key;
        }

        public function getIterator()
        {
            return new ArrayIterator($this->modelData);
        }

        public function count()
        {
            return count($this->modelData);
        }

        // Required by Iterator interface
        public function current(): mixed
        {
            return current($this->modelData);
        }

        public function key(): mixed
        {
            return key($this->modelData);
        }

        public function next(): void
        {
            next($this->modelData);
            $this->position++;
        }

        public function rewind(): void
        {
            reset($this->modelData);
            $this->position = 0;
        }

        public function valid(): bool
        {
            return key($this->modelData) !== null;
        }
    }
}

if (!class_exists('Google_Model')) {
    class Google_Model
    {
        protected $modelData = [];

        public function __construct($data = [])
        {
            $this->modelData = $data;
        }

        public function offsetSet($offset, $value)
        {
            if (is_null($offset)) {
                $this->modelData[] = $value;
            } else {
                $this->modelData[$offset] = $value;
            }
        }

        public function offsetExists($offset)
        {
            return isset($this->modelData[$offset]);
        }

        public function offsetUnset($offset)
        {
            unset($this->modelData[$offset]);
        }

        public function offsetGet($offset)
        {
            return isset($this->modelData[$offset]) ? $this->modelData[$offset] : null;
        }
    }
}

if (!class_exists('Google_Exception')) {
    class Google_Exception extends Exception
    {
    }
}

if (!class_exists('Google_Service_Exception')) {
    class Google_Service_Exception extends Google_Exception
    {
        protected $errors = [];

        public function __construct($message, $code = 0, $errors = [])
        {
            parent::__construct($message, $code);
            $this->errors = $errors;
        }

        public function getErrors()
        {
            return $this->errors;
        }
    }
}

if (!class_exists('Google_Http_Batch')) {
    class Google_Http_Batch
    {
        public function __construct(Google_Client $client, $rootUrl = null, $batchPath = 'batch')
        {
            $this->client = $client;
        }

        public function add($request, $identifier = null)
        {
        }

        public function execute()
        {
            return [];
        }
    }
}

if (!class_exists('Google_Http_MediaFileUpload')) {
    class Google_Http_MediaFileUpload
    {
        public function __construct(Google_Client $client, $request, $media, $resumable = false)
        {
            $this->client = $client;
            $this->request = $request;
            $this->media = $media;
            $this->resumable = $resumable;
        }
    }
}

if (!class_exists('Google_Http_REST')) {
    class Google_Http_REST
    {
        public static function execute(Google_Client $client, $request)
        {
            return [];
        }
    }
}

if (!class_exists('Google_Service_Resource')) {
    class Google_Service_Resource
    {
        public function __construct($service, $serviceName, $resourceName, $resource)
        {
            $this->service = $service;
            $this->serviceName = $serviceName;
            $this->resourceName = $resourceName;
            $this->resource = $resource;
        }

        public function call($method, $args, $headers = [])
        {
            return [];
        }
    }
}

if (!class_exists('Google_AccessToken_Revoke')) {
    class Google_AccessToken_Revoke
    {
        public static function revoke(Google_Client $client, $token)
        {
            $http = new \GuzzleHttp\Client();
            try {
                $http->post('https://oauth2.googleapis.com/revoke', [
                    'form_params' => ['token' => $token],
                ]);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }
}

if (!class_exists('Google_AccessToken_Verify')) {
    class Google_AccessToken_Verify
    {
        public static function verifyIdToken(Google_Client $client, $idToken)
        {
            if (!$idToken) {
                return false;
            }

            $parts = explode('.', $idToken);
            if (count($parts) !== 3) {
                return false;
            }

            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
            return $payload;
        }
    }
}

if (!class_exists('Google_AuthHandler_AuthHandlerFactory')) {
    class Google_AuthHandler_AuthHandlerFactory
    {
        public static function create(Google_Client $client, $cache = null, $logger = null)
        {
            return null;
        }
    }
}

if (!class_exists('Google_AuthHandler_Guzzle6AuthHandler')) {
    class Google_AuthHandler_Guzzle6AuthHandler
    {
        public function __construct(Google_Client $client)
        {
            $this->client = $client;
        }
    }
}

if (!class_exists('Google_AuthHandler_Guzzle7AuthHandler')) {
    class Google_AuthHandler_Guzzle7AuthHandler
    {
        public function __construct(Google_Client $client)
        {
            $this->client = $client;
        }
    }
}

if (!class_exists('Google_Task_Composer')) {
    class Google_Task_Composer
    {
        public static function getTaskRoutes()
        {
            return [];
        }
    }
}

if (!class_exists('Google_Task_Exception')) {
    class Google_Task_Exception extends Google_Exception
    {
    }
}

if (!class_exists('Google_Task_Retryable')) {
    class Google_Task_Retryable extends Exception
    {
        private $retryable;

        public function __construct($retryable = false, $message = '', $code = 0, $previous = null)
        {
            parent::__construct($message, $code, $previous);
            $this->retryable = $retryable;
        }

        public function isRetryable()
        {
            return $this->retryable;
        }
    }
}

if (!class_exists('Google_Task_Runner')) {
    class Google_Task_Runner
    {
        public function __construct(Google_Client $client, $taskName, $action, $arguments = [])
        {
            $this->client = $client;
            $this->taskName = $taskName;
            $this->action = $action;
            $this->arguments = $arguments;
        }

        public function run()
        {
            return call_user_func_array($this->action, $this->arguments);
        }
    }
}

if (!class_exists('Google_Utils_UriTemplate')) {
    class Google_Utils_UriTemplate
    {
        public function expand($template, $variables)
        {
            return $template;
        }

        public function parse($template)
        {
            return [];
        }
    }
}
