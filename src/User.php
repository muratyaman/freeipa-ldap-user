<?php
/**
 * File for user class
 */

namespace FreeIpaLdap;

/**
 * Class User
 *
 * @property string $uid                    e.g. 'haci'
 * @property string $givenName              e.g. 'Haci'
 * @property string $sn                     e.g. 'Yaman'
 * @property string $displayName            e.g. 'Haci Yaman'
 * @property string $cn                     e.g. 'Haci Murat Yaman'
 * @property string $gecos                  e.g. 'Haci Yaman'
 * @property string $initials               e.g. 'HMY'
 *
 * @property string $title                  e.g. 'Lead Software Engineer'
 * @property string $employeeType           e.g. 'engineer'
 * @property string $preferredLanguage      e.g. 'english'
 * @property string $employeeNumber         e.g. '12345'
 * @property string $ou                     e.g. 'unipart' Organisational Unit
 *
 * @property string $street                 e.g. '123 High Street'
 * @property string $l                      e.g. 'Ilford' town/location
 * @property string $st                     e.g. 'Essex'  state/county
 * @property string $postalcode             e.g. 'IG1'
 *
 * @property string $mail                   e.g. 'haci@muratyaman.co.uk'
 * @property string $krbCanonicalName       e.g. 'haci@muratyaman.co.uk'
 * @property string $krbPrincipalName       e.g. 'haci@MURATYAMAN.CO.UK'
 * @property string $krbPasswordExpiration  e.g. '20180107115310Z'
 * @property string $krbLastPwdChange       e.g. '20171009115310Z'
 *
 * @property string $createTimestamp        e.g. '20171009104819Z'
 * @property string $modifyTimestamp        e.g. '20171011093722Z'
 *
 * @property string $userclass              e.g. 'user'
 * @property array  $objectClass
 * @property array  $memberOf
 *
 * @property string $loginShell             e.g. '/bin/sh'
 * @property string $homeDirectory          e.g. '/home/haci'
 *
 * @property int    $gidNumber              e.g. 1435800002 group ID
 * @property int    $uidNumber              e.g. 1435800001
 *
 * @property string $dn                     e.g. 'uid=haci,cn=users,cn=accounts,dc=muratyaman,dc=co,dc=uk'
 * @property string $ipaUniqueId            e.g. '5ccb2ae2-acdf-11e7-90d3-22d7b6f443e9'
 *
 * @package FreeIpaLdap
 *
 */
class User
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * User constructor.
     * @param array $data
     */
    function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get magic method
     * @param string $name
     * @return string | null
     */
    function __get($name)
    {
        return $this->getProperty($name);
    }

    /**
     * Get property
     * @param $name
     * @return array|bool|float|int|mixed|null|string
     */
    function getProperty($name)
    {
        $result = null;

        $val = null;
        if (isset($this->data[$name])) {
            $val = $this->data[$name];
        } else {
            $name = strtolower($name);
            if (isset($this->data[$name])) {
                $val = $this->data[$name];
            }
        }

        do {
            if (is_scalar($val)) {
                $result = $val; break;
            }

            if (is_array($val)) {
                if (isset($val['count'])) {
                    $c = $val['count'];
                    if (1 == $c && isset($val['0'])) {
                        $result = $val['0']; break;
                    }
                    // many values
                    unset($val['count']);// remove 'count' entry
                    $result = array_values($val); break;
                }
            }

        } while(false);//loop once


        return $result;
    }

    /**
     * @return array
     */
    function toArray()
    {
        $data = [];

        $keys = array_keys($this->data);
        foreach ($keys as $key) {
            if (is_int($key)) {
                continue;// ignore number index
            }
            $val = $this->getProperty($key);// trigger magic method
            $data[$key] = $val;
        }

        return $data;
    }
}