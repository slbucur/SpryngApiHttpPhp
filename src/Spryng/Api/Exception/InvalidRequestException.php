<?php

/**
 * Warns for invalid requests
 *
 * @license         Berkeley Software Distribution License (BSD-License 2) http://www.opensource.org/licenses/bsd-license.php
 * @author          Roemer Bakker
 * @copyright       Complexity Software
 *
 */
class Spryng_Api_Exception_InvalidRequest extends Spryng_Api_Exception
{
    const INVALID_CLIENT                        = 301;
    const BODY_TOO_LONG_WITHOUT_ALLOWLONG       = 302;
    const BODY_TOO_LONG                         = 303;
    const INVALID_DESTINATION                   = 304;
}