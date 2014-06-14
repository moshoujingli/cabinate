<?php

namespace Cabinate\APIBundle\Exceptions;
/*
 * Exception for Resource Not Fount.
 */
class ResourceNotFoundException extends CabinateAPIException
{
    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = 'Resource Not Found', \Exception $previous = null, $code = 0)
    {
        parent::__construct(404, $message, $previous, array(), $code);
    }
}