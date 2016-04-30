<?php

/**
 * @license         Berkeley Software Distribution License (BSD-License 2) http://www.opensource.org/licenses/bsd-license.php
 * @author          Roemer Bakker
 * @copyright       Complexity Software
 */

namespace SpryngApiHttpPhp;

use SpryngApiHttpPhp\Spryng_Api_CompatibilityChecker;
use SpryngApiHttpPhp\Resources\Spryng_Api_Resources_Sms;
use SpryngApiHttpPhp\Exception\Spryng_Api_Exception_AuthenticationException;
use SpryngApiHttpPhp\Exception\Spryng_Api_Exception_InvalidRequestException;

/**
 * Acts as driver for the library
 *
 * Class Spryng_Api_Client
 * @package SpryngApiHttpPhp
 */
class Spryng_Api_Client
{
    /**
     * @var string Version of this client
     */
    const CLIENT_VERSION = "0.1";

    /**
     * Api endpoint for all requests
     *
     * @var string
     */
    const API_ENDPOINT = "http://spryng.nl";

    /**
     * @var string
     */
    protected $apiEndpoint = self::API_ENDPOINT;

    /**
     * Public instance of the Sms resource class
     *
     * @var Spryng_Api_Resources_Sms;
     */
    public $sms;

    /**
     * @var string Username for the service
     */
    protected $username;

    /**
     * @var string Password for the service
     */
    protected $password;

    /**
     * @var string Originator name
     */
    protected $sender;

    /**
     * Spryng_Api_Client constructor.
     * @param $username
     * @param $password
     * @param $sender
     */
    public function __construct ( $username, $password, $sender )
    {
        $this->getCompatibilityChecker()->checkCompatibility();

        $this->setCredentials($username, $password, $sender);

        $this->sms = new Spryng_Api_Resources_Sms($this);
    }

    /**
     * Checks format of username and password.
     *
     * @param $username
     * @param $password
     * @param $sender
     * @throws Spryng_Api_Exception_AuthenticationException
     * @throws Spryng_Api_Exception_InvalidRequestException
     */
    public function setCredentials( $username, $password, $sender )
    {
        if (strlen($username) < 2 || strlen($username) > 32)
        {
            throw new Spryng_Api_Exception_AuthenticationException(
                "Username must be between 2 and 32 characters.",
                201
            );
        }
        if (strlen($password) < 6 || strlen($password) > 32)
        {
            throw new Spryng_Api_Exception_AuthenticationException(
                "Password must be between 6 and 32 characters.",
                202
            );
        }

        if ( intval($sender) > 0 && strlen($sender) > 14 )
        {
            throw new Spryng_Api_Exception_InvalidRequestException(
                "Numeric senders can not be longer than 14 characters long.",
                306
            );
        }
        else if ( intval($sender) === 0 && strlen($sender) > 11 )
        {
            throw new Spryng_Api_Exception_InvalidRequestException(
                "Alphanumeric senders can not be longer than 11 characters long.",
                305
            );
        }

        $this->setUsername($username);
        $this->setPassword($password);
        $this->setSender($sender);
    }

    /**
     * Returns instance of the compatibility checker class
     *
     * @return Spryng_Api_CompatibilityChecker
     */
    protected function getCompatibilityChecker ()
    {
        static $checker = NULL;
        if (!$checker)
        {
            $checker = new Spryng_Api_CompatibilityChecker();
        }
        return $checker;
    }

    /**
     * Returns the API endpoint outside of static context.
     *
     * @return string
     */
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }

    /**
     * Returns the current user's username
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the username
     *
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Returns the current user's password
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password
     *
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the current user's originator address.
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Sets the sender
     *
     * @param string $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }
}