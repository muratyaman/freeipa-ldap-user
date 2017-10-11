<?php
/**
 * File for config class
 */

namespace FreeIpaLdap;

/**
 * Class Config
 * @package FreeIpaLdap
 */
class Config
{
    /**
     * URL for LDAP server
     *
     * @example 'ldap://ldap.muratyaman.co.uk'
     * @example 'ldap://ldap.muratyaman.co.uk:389'
     * @example 'ldaps://ldap.muratyaman.co.uk'
     * @example 'ldaps://ldap.muratyaman.co.uk:636'
     * @var string
     */
    public $url;

    /**
     * Base DN
     * @example 'dc=muratyaman,dc=co,dc=uk'
     * @var string
     */
    public $baseDn;

    /**
     * @example 'cn=users,cn=accounts'
     * @var string
     */
    public $baseCn;

    /**
     * Config constructor.
     * @param string $url
     * @param string $baseDn
     * @param string $baseCn
     */
    function __construct($url, $baseDn, $baseCn)
    {
        $this->url    = $url;
        $this->baseDn = $baseDn;
        $this->baseCn = $baseCn;
    }
}