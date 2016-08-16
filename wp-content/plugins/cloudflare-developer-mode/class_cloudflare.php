<?php
/**
 * CloudFlare API Class
 */
class cloudflare_api
{
    //The URL of the API
    private static $URL = array(
        'USER' => 'https://www.cloudflare.com/api_json.html',
        'HOST' => 'https://api.cloudflare.com/host-gw.html'
    );

    //Service mode values.
    private static $MODE_SERVICE = array('A', 'AAAA', 'CNAME');

    //Prio values.
    private static $PRIO = array('MX', 'SRV');

    //Timeout for the API requests in seconds
    const TIMEOUT = 5;

    //Interval values for Stats
    const INTERVAL_365_DAYS = 10;
    const INTERVAL_30_DAYS = 20;
    const INTERVAL_7_DAYS = 30;
    const INTERVAL_DAY = 40;
    const INTERVAL_24_HOURS = 100;
    const INTERVAL_12_HOURS = 110;
    const INTERVAL_6_HOURS = 120;

    //Stores the api key
    private $token_key;
    private $host_key;

    //Stores the email login
    private $email;

    /**
     * Make a new instance of the API client
     */
    public function __construct()
    {
        $parameters = func_get_args();
        switch (func_num_args()) {
            case 1:
                //a host API
                $this->host_key  = $parameters[0];
                break;
            case 2:
                //a user request
                $this->email     = $parameters[0];
                $this->token_key = $parameters[1];
                break;
        }
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setToken($token_key)
    {
        $this->token_key = $token_key;
    }

    /**
     *  Retrieve Domain Statistics For A Given Time Frame
     */
    public function stats($domain, $interval = 20)
    {
        $data = array(
            'a'        => 'stats',
            'z'        => $domain,
            'interval' => $interval
        );
        return $this->http_post($data);
    }


    /**
     * Toggling Development Mode
     */
    public function devmode($domain, $mode)
    {
        $data = array(
            'a' => 'devmode',
            'z' => $domain,
            'v' => ($mode == true) ? 1 : 0
        );
        return $this->http_post($data);
    }

    /**
     * Clear CloudFlare's Cache
     * This function will purge CloudFlare of any cached files.
     */
    public function fpurge_ts($domain)
    {
        $data = array(
            'a' => 'fpurge_ts',
            'z' => $domain,
            'v' => 1
        );
        return $this->http_post($data);
    }


    public function user_lookup($email, $isID = false)
    {
        $data = array(
            'act' => 'user_lookup'
        );
        if ($isID) {
            $data['unique_id'] = $email;
        } else {
            $data['cloudflare_email'] = $email;
        }
        return $this->http_post($data, 'HOST');
    }

    public function user_auth($email, $password, $id = '')
    {
        $data = array(
            'act'              => 'user_auth',
            'cloudflare_email' => $email,
            'cloudflare_pass'  => $password,
            'unique_id'        => $id
        );
        return $this->http_post($data, 'HOST');
    }





    /**
     * GLOBAL API CALL
     */
    private function http_post($data, $type = 'USER')
    {
        switch ($type) {
            case 'USER':
                $data['u']   = $this->email;
                $data['tkn'] = $this->token_key;
                break;
            case 'HOST':
                $data['host_key'] = $this->host_key;
                break;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_URL, self::$URL[$type]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $http_result = curl_exec($ch);
        $error       = curl_error($ch);
        $http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code != 200) {
            return array(
                'error' => $error
            );
        } else {
            return json_decode($http_result);
        }
    }
}
