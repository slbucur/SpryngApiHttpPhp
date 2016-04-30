<?php

/**
 * @license         Berkeley Software Distribution License (BSD-License 2) http://www.opensource.org/licenses/bsd-license.php
 * @author          Roemer Bakker
 * @copyright       Complexity Software
 */

namespace SpryngApiHttpPhp\Resources;

use SpryngApiHttpPhp\Utilities\Spryng_Api_Utilities_Validator;
use SpryngApiHttpPhp\Utilities\Spryng_Api_Utilities_RequestHandler;
use SpryngApiHttpPhp\Exception\Spryng_Api_Exception_InvalidRequestException;

/**
 * Drives SMS functions
 *
 * Class Spryng_Api_Resources_Sms
 * @package SpryngApiHttpPhp\Resources
 */
class Spryng_Api_Resources_Sms extends Spryng_Api_Resources_Base
{

    /**
     * @var string URI for the balance Api
     */
    const BALANCE_URI = "/check.php";

    /**
     * @var string URI for the send Api
     */
    const SMS_URI = "/send.php";

    /**
     * Defaults for optional parameters in send method
     *
     * @var array
     */
    public $defaultSendOptions = array(
        'route'     => 'ECONOMY',
        'allowlong' => false
    );

    /**
     * Used to send text messages
     *
     * @param $recipient string
     * @param $body string
     * @param $options array
     * @return mixed string
     * @throws Spryng_Api_Exception_InvalidRequestException
     */
    public function send($recipient, $body, $options)
    {
        // Make sure allowlong and route are set for validation.

        if (! isset($options['allowlong']))
        {
            $options['allowlong'] = $this->defaultSendOptions['allowlong'];
        }

        if (! isset( $options['route']) )
        {
            $options['route'] = $this->defaultSendOptions['route'];
        }

        if (Spryng_Api_Utilities_Validator::validateSendRequest($recipient, $body, $options))
        {
            // Prepare the request
            $requestHandler = new Spryng_Api_Utilities_RequestHandler();
            $requestHandler->setHttpMethod("GET");
            $requestHandler->setBaseUrl($this->api->getApiEndpoint());
            $requestHandler->setQueryString(static::SMS_URI);
            $requestHandler->addGetParameter('send', 'OPERATION', false);
            $requestHandler->addGetParameter($this->api->getUsername(), 'USERNAME', false);
            $requestHandler->addGetParameter($this->api->getPassword(), 'PASSWORD', false);
            $requestHandler->addGetParameter($recipient, 'DESTINATION', true);
            $requestHandler->addGetParameter($this->api->getSender(), 'SENDER', true);
            $requestHandler->addGetParameter($options['allowlong'], 'ALLOWLONG', false);
            $requestHandler->addGetParameter($body, 'BODY', true);
            $requestHandler->addGetParameter($options['route'], 'ROUTE', true);

            // Add optional reference
            if ( isset($options['reference']) )
            {
                $requestHandler->addGetParameter($options['reference'], 'REFERENCE', true);
            }


            $requestHandler->doRequest();

            return $requestHandler->getResponse();
        }
        else {
            throw new Spryng_Api_Exception_InvalidRequestException(
                "Request is invalid for unknown reason.",
                0
            );
        }
    }

    /**
     * Returns the remaining credit balance
     *
     * @return int
     */
    public function checkBalance ()
    {
        $requestHandler = new Spryng_Api_Utilities_RequestHandler();

        $requestHandler->setHttpMethod("GET");
        $requestHandler->setBaseUrl($this->api->getApiEndpoint());
        $requestHandler->setQueryString(static::BALANCE_URI);
        $requestHandler->setGetParameters([
            'username' => $this->api->getUsername(),
            'password' => $this->api->getPassword()
        ], true);
        $requestHandler->doRequest();

        return (int) $requestHandler->getResponse();
    }
}