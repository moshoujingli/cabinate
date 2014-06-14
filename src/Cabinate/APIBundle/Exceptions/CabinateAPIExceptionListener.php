<?php
namespace Cabinate\APIBundle\Exceptions;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
/**
 * Exception Listener for API Exception.
 */
class CabinateAPIExceptionListener 
{
	public function onKernelException(GetResponseForExceptionEvent $event)
      {
        $exception =  $event->getException();
        if ($exception instanceof CabinateAPIException) {
            //create response, set status code etc.
            $response = $event->getResponse();
            $content = sprintf('{"message":"%s"}',$exception->getMessage());
            if (!isset($response)) {
                $response = new Response($content,$exception->getStatusCode());
            }else{
                $response->setContent($content);
            }
            $event->setResponse($response); //event will stop propagating here. Will not call other listeners.
        }
      }
}