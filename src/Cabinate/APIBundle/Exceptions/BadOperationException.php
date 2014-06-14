<?php

namespace Cabinate\APIBundle\Exceptions;
/*
 * Exception for Patch with not supported path and ops.
 */
class BadOperationException extends CabinateAPIException
{
    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = 'Operation or Path Not Support', \Exception $previous = null, $code = 0)
    {
        parent::__construct(422, $message, $previous, array(), $code);
    }
}