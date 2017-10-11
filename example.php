<?php
/**
 * File for example code
 */

error_reporting(E_ALL);
function errorHandler($errno, $errstr, $errfile, $errline) {
    throw new Exception("Error[$errno] $errstr at $errfile:$errline");
}
set_error_handler("errorHandler");

require __DIR__ . '/vendor/autoload.php';

/**
 * @param string $username
 * @param string $password
 */
function login($username, $password)
{
    try {
        echo "login('$username', '$password') ... " . PHP_EOL;

        // prepare configuration values
        // TODO: read config file
        $url    = 'ldap://ldap.muratyaman.co.uk:389';
        $baseDn = 'dc=muratyaman,dc=co,dc=uk';
        $baseCn = 'cn=users,cn=accounts';
        $config = new FreeIpaLdap\Config($url, $baseDn, $baseCn);

        // open connection
        $connection = new FreeIpaLdap\Connection($config);

        // try to login
        $user = $connection->login($username, $password);

        echo 'cn: ' . $user->cn . PHP_EOL;
        echo 'dn: ' . $user->dn . PHP_EOL;
        echo 'mail: ' . $user->mail . PHP_EOL;
        echo json_encode($user->toArray()) . PHP_EOL;

    } catch (FreeIpaLdap\ConnectionError $err1) {
        echo 'Connection error: ' . $err1->getMessage() . PHP_EOL;
    } catch (FreeIpaLdap\LoginError $err2) {
        echo 'Login error: ' . $err2->getMessage() . PHP_EOL;
    } catch (FreeIpaLdap\Error $err3) {
        echo 'LDAP error: ' . $err3->getMessage() . PHP_EOL;
    } catch (Exception $err4) {
        echo 'Error: ' . $err4->getMessage() . PHP_EOL;
    }

    echo 'done' . PHP_EOL;
}

/**
 * @param array $argv
 */
function cli($argv)
{
    // get credentials from user
    // TODO: validate inputs
    $username = $argv[1];
    $password = $argv[2];
    login($username, $password);
}

/**
 * @param array $post
 */
function web($post)
{
    // get credentials from user
    // TODO: validate inputs
    $username = $post['username'];
    $password = $post['password'];
    login($username, $password);
}

// command line
if ('cli' === php_sapi_name()) {
    cli($argv);
} else {
    web($_POST);
}
