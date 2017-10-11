<?php
/**
 * File for connection class
 */

namespace FreeIpaLdap;

use Exception;

/**
 * Class Connection
 * @package FreeIpaLdap
 */
class Connection
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var resource
     */
    private $ldapResource;

    /**
     * Connection constructor.
     * @param Config $config
     * @param bool   $autoConnect
     */
    function __construct(Config $config, $autoConnect = true)
    {
        $this->config = $config;
        if ($autoConnect) {
            $this->connect();
        }
    }

    /**
     * @return Config
     */
    function getConfig()
    {
        return $this->config;
    }

    /**
     * @return resource
     * @throws ConnectionError
     * @see http://php.net/manual/en/function.ldap-connect.php
     */
    function connect()
    {
        try {
            $this->ldapResource = ldap_connect($this->config->url);
        } catch (Exception $ex) {
            throw new ConnectionError($ex->getMessage(), $ex->getCode(), $ex);
        }

        return $this->ldapResource;
    }

    /**
     * Get LDAP resource
     * @return resource
     * @throws ConnectionError
     */
    function getLdapResource()
    {
        if ($this->ldapResource) {
            return $this->ldapResource;
        }
        throw new ConnectionError('No connection');
    }

    /**
     * Login and get user details
     * @param string $username
     * @param string $password
     * @return User | null
     * @throws LoginError
     * @see http://php.net/manual/en/function.ldap-bind.php
     */
    function login($username, $password)
    {
        try {
            $user = null;
            $conf = $this->getConfig();
            $res  = $this->getLdapResource();
            $rdn  = sprintf('uid=%s,%s,%s', $username, $conf->baseCn, $conf->baseDn);
            $done = ldap_bind($res, $rdn, $password);

            if ($done) {
                $user = $this->fetchUser($rdn);
            } else {
                $errMsg = ldap_error($res);
                throw new LoginError($errMsg);
            }

        } catch (Exception $ex) {
            throw new LoginError($ex->getMessage(), $ex->getCode(), $ex);
        }

        return $user;
    }

    /**
     * Fetch user details
     * @param string $rdn
     * @return User | null
     * @throws LoginError
     */
    private function fetchUser($rdn)
    {
        $user = null;

        try {
            $filter  = '(objectclass=*)';
            $res     = $this->getLdapResource();
            $result  = ldap_search($res, $rdn, $filter);
            $entries = ldap_get_entries($res, $result);
            //$entries = json_decode(json_encode($entries), $assoc = true);
            if (isset($entries['0'])) {
                $user = new User($entries['0']);
            }
        } catch (Exception $ex) {
            throw new LoginError($ex->getMessage(), $ex->getCode(), $ex);
        }

        return $user;
    }
}