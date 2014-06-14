<?php
namespace Cabinate\APIBundle\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
/**
 * Exception for API.
 */
class CabinateAPIException extends \Exception implements HttpExceptionInterface
{
	private $statusCode;
    private $headers;

    public function __construct($statusCode, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}