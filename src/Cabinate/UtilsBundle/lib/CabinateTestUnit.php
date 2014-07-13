<?php
namespace Cabinate\UtilsBundle\lib;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class CabinateTestUnit extends WebTestCase
{
    protected $em;
    protected $logger;
    protected $configure;
    protected $util;
    public function setup()
    {
        $this->em = $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->logger = $this->getMockBuilder('\Monolog\Logger')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
