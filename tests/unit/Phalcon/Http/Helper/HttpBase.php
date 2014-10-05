<?php
/**
 * HttpBase.php
 * \Phalcon\Text\Helper\HttpBase
 *
 * Base class for Phalcon\Http component
 *
 * PhalconPHP Framework
 *
 * @copyright (c) 2011-2014 Phalcon Team
 * @link      http://www.phalconphp.com
 * @author    Andres Gutierrez <andres@phalconphp.com>
 * @author    Nikolaos Dimopoulos <nikos@phalconphp.com>
 *
 * The contents of this file are subject to the New BSD License that is
 * bundled with this package in the file docs/LICENSE.txt
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@phalconphp.com
 * so that we can send you a copy immediately.
 */

namespace Phalcon\Tests\unit\Phalcon\Http\Helper;

use \Phalcon\DI as PhDI;
use \Phalcon\Http\Request as PhRequest;
use \Phalcon\Http\Response as PhResponse;
use \Phalcon\Mvc\Url as PhUrl;
use \Phalcon\Filter as PhFilter;

class HttpBase extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * Initializes the response object and returns it
     *
     * @return PhResponse
     */
    protected function getResponseObject()
    {
        $di = new PhDI();

        $di->set(
            'url',
            function () {
                $url = new PhUrl();
                $url->setBaseUri('/');

                return $url;
            }
        );

        $response = new PhResponse();
        $response->setDI($di);

        return $response;
    }

    /**
     * Initializes the request object and returns it
     *
     * @return PhRequest
     */
    protected function getRequestObject()
    {
        $di = new PhDI();

        $di->set(
            'filter',
            function () {
                return new PhFilter();
            }
        );

        $request = new PhRequest();
        $request->setDI($di);

        return $request;
    }

    /**
     * Checks the has functions on non defined variables
     *
     * @param string    $function
     */
    protected function hasEmpty($function)
    {
        $request = $this->getRequestObject();
        $actual  = $request->$function('test');

        expect($actual)->false();
    }

    /**
     * Checks the has functions on defined variables
     *
     * @param string    $function
     * @param string    $method
     */
    public function hasNotEmpty($function, $method)
    {
        $request  = $this->getRequestObject();
        $unMethod = "un{$method}";

        $this->$method('test', 1);
        $actual  = $request->$function('test');
        $this->$unMethod('test');

        expect($actual)->true();
    }

    /**
     * Checks the get functions on undefined variables
     *
     * @param string    $function
     */
    public function getEmpty($function)
    {
        $request = $this->getRequestObject();

        $actual  = $request->$function('test');

        expect($actual)->isEmpty();
    }

    /**
     * Checks the get functions on defined variables
     *
     * @param string    $function
     * @param string    $method
     */
    public function getNotEmpty($function, $method)
    {
        $request  = $this->getRequestObject();
        $unMethod = "un{$method}";

        $this->$method('test', 1);
        $expected = 1;
        $actual   = $request->$function('test');
        $this->$unMethod('test');

        expect($actual)->equals($expected);
    }

    /**
     * Checks the get functions for sanitized data
     *
     * @param string    $function
     * @param string    $method
     */
    public function getSanitized($function, $method)
    {
        $request  = $this->getRequestObject();
        $unMethod = "un{$method}";

        $this->$method('test', 'lol<');
        $expected = 'lol';
        $actual   = $request->$function('test', 'string');
        $this->$unMethod('test');

        expect($actual)->equals($expected);
    }

    /**
     * Checks the get functions for sanitized data (array filters)
     *
     * @param string    $function
     * @param array     $filter
     * @param string    $method
     */
    public function getSanitizedArrayFilter($function, $filter, $method)
    {
        $request  = $this->getRequestObject();
        $unMethod = "un{$method}";

        $this->$method('test', 'lol<');
        $expected = 'lol';
        $actual   = $request->$function('test', $filter);
        $this->$unMethod('test');

        expect($actual)->equals($expected);
    }

    /**
     * Sets a server variable ($_SERVER)
     *
     * @param string    $var
     * @param mixed     $value
     */
    protected function setServerVar($var, $value)
    {
        $_SERVER[$var] = $value;
    }

    /**
     * Unsets a server variable ($_SERVER)
     *
     * @param string    $var
     */
    protected function unsetServerVar($var)
    {
        unset($_SERVER[$var]);
    }

    /**
     * Sets a get variable ($_GET)
     *
     * @param string    $var
     * @param mixed     $value
     */
    protected function setGetVar($var, $value)
    {
        $_GET[$var] = $value;
    }

    /**
     * Unsets a get variable ($_GET)
     *
     * @param string    $var
     */
    protected function unsetGetVar($var)
    {
        unset($_GET[$var]);
    }

    /**
     * Sets a post variable ($_POST)
     *
     * @param string    $var
     * @param mixed     $value
     */
    protected function setPostVar($var, $value)
    {
        $_POST[$var] = $value;
    }

    /**
     * Unsets a post variable ($_POST)
     *
     * @param string    $var
     */
    protected function unsetPostVar($var)
    {
        unset($_POST[$var]);
    }

    /**
     * Sets a request variable ($_REQUEST)
     *
     * @param string    $var
     * @param mixed     $value
     */
    protected function setRequestVar($var, $value)
    {
        $_REQUEST[$var] = $value;
    }

    /**
     * Unsets a request variable ($_REQUEST)
     *
     * @param string    $var
     */
    protected function unsetRequestVar($var)
    {
        unset($_REQUEST[$var]);
    }
}
