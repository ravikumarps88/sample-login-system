<?php

namespace Skyrocket\LoginBundle\Tests\Controller;

use Skyrocket\LoginBundle\Entity\Employee;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @var \Skyrocket\LoginBundle\Controller\DefaultController
     */

    protected $mockController;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $mockRequest;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $mockEntityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $mockRepository;

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $mockRegistry;

    /**
     * Initial set up for the unit test
     */
    public function setUp()
    {
        // Mock controller
        $mockController = $this->getMock(
                '\Skyrocket\LoginBundle\Controller\DefaultController', array('getRequest', 'getDoctrine', 'render', 'redirectToRoute'), array(), '', false
        );

        /**
         * Mock the Registry
         */
        $mockRegistry = $this->getMock(
                'Doctrine\Bundle\DoctrineBundle\Registry', array('getManager'), array(), '', false
        );

        // Mock request
        $mockRequest = $this->getMock(
                'Symfony\Component\HttpFoundation\Request', array('getMethod', 'get'), array(), '', false
        );

        // Mock entity manager
        $mockEntityManager = $this->getMock(
                '\Doctrine\ORM\EntityManager', array('getRepository', 'persist', 'flush'), array(), '', false
        );

        // Mock repository
        $mockRepository = $this->getMock(
                '\Doctrine\ORM\EntityRepository', array('getFilteredEmployees', 'getAllEmployee', 'find', 'getFilteredEmployeesResigned'), array(), '', false
        );

        $this->mockController = $mockController;
        $this->mockRegistry = $mockRegistry;
        $this->mockRequest = $mockRequest;
        $this->mockEntityManager = $mockEntityManager;
        $this->mockRepository = $mockRepository;
        $this->employee = new Employee();
    }

    /**
     * Test case to cover the dashboard listing functionality
     *
     * @covers Skyrocket\LoginBundle\Controller\DefaultController::dashboardAction
     */
    public function testDashboardListingAction()
    {
        $dummyResultArray = $this->getDummyArray();

        $this->mockController->expects($this->once())
                ->method('getRequest')
                ->will($this->returnValue($this->mockRequest));

        $this->mockController->expects($this->once())
                ->method('getDoctrine')
                ->will($this->returnValue($this->mockRegistry));

        $this->mockRegistry->expects($this->once())
                ->method('getManager')
                ->will($this->returnValue($this->mockEntityManager));

        $this->mockEntityManager->expects($this->once())
                ->method('getRepository')
                ->with($this->equalTo('SkyrocketLoginBundle:Employee'))
                ->will($this->returnValue($this->mockRepository));

        $this->mockRepository->expects($this->once())
                ->method('getAllEmployee')
                ->will($this->returnValue($dummyResultArray));

        $this->mockController->dashboardAction();
    }

    /**
     * Test case to cover the dashboard listing functionality with filter value as post
     *
     * @covers Skyrocket\LoginBundle\Controller\DefaultController::dashboardAction
     */
    public function testDashboardListingWithFilterAction()
    {
        $dummyResultArray = $this->getDummyFilterArray();

        $this->mockController->expects($this->once())
                ->method('getRequest')
                ->will($this->returnValue($this->mockRequest));

        $this->mockRequest->expects($this->any())
                ->method('getMethod')
                ->will($this->returnValue('POST'));

        $this->mockRequest->expects($this->at(1))
                ->method('get')
                ->with($this->equalTo('fromDate'))
                ->will($this->returnValue('08/04/2015'));

        $this->mockRequest->expects($this->at(2))
                ->method('get')
                ->with($this->equalTo('toDate'))
                ->will($this->returnValue('08/06/2015'));

        $this->mockController->expects($this->once())
                ->method('getDoctrine')
                ->will($this->returnValue($this->mockRegistry));

        $this->mockRegistry->expects($this->once())
                ->method('getManager')
                ->will($this->returnValue($this->mockEntityManager));

        $this->mockEntityManager->expects($this->once())
                ->method('getRepository')
                ->with($this->equalTo('SkyrocketLoginBundle:Employee'))
                ->will($this->returnValue($this->mockRepository));

        $this->mockRepository->expects($this->once())
                ->method('getFilteredEmployees')
                ->with($this->equalTo(0), $this->equalTo('2015-08-04 00:00:00'), $this->equalTo('2015-08-06 00:00:00'))
                ->will($this->returnValue($dummyResultArray));

        $this->mockController->dashboardAction();
    }

    /*
     * Test case to cover makeEmployeeResignedAction to make an employee resigned
     * 
     *  @covers Skyrocket\LoginBundle\Controller\DefaultController::makeEmployeeResignedAction
     */
    public function testMakeEmployeeResignedAction()
    {
        $dummyResultArray = $this->getDummyFilterArray();

        $this->mockController->expects($this->once())
                ->method('getDoctrine')
                ->will($this->returnValue($this->mockRegistry));

        $this->mockRegistry->expects($this->once())
                ->method('getManager')
                ->will($this->returnValue($this->mockEntityManager));

        $this->mockEntityManager->expects($this->once())
                ->method('getRepository')
                ->with($this->equalTo('SkyrocketLoginBundle:Employee'))
                ->will($this->returnValue($this->mockRepository));

        $this->mockRepository->expects($this->once())
                ->method('find')
                ->with($this->equalTo(7))
                ->will($this->returnValue($this->employee));

        $this->employee->setIsResigned(1);
        $this->employee->setResignedDate('2015-08-04 00:00:00');

        $this->mockEntityManager->expects($this->once())
                ->method('persist')
                ->will($this->returnValue(null));

        $this->mockEntityManager->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(null));

        $this->mockController->expects($this->any())
                ->method('redirectToRoute')
                ->with(
                        $this->equalTo('skyrocket_admin_homepage')
        );

        $this->mockController->makeEmployeeResignedAction(7);
    }

    /**
     * Test case to cover the showResignedAction
     *
     * @covers Skyrocket\LoginBundle\Controller\DefaultController::showResignedAction
     */
    public function testShowResignedAction()
    {
        $dummyResultArray = $this->getResignedDummyArray();

        $this->mockController->expects($this->once())
                ->method('getRequest')
                ->will($this->returnValue($this->mockRequest));

        $this->mockController->expects($this->once())
                ->method('getDoctrine')
                ->will($this->returnValue($this->mockRegistry));

        $this->mockRegistry->expects($this->once())
                ->method('getManager')
                ->will($this->returnValue($this->mockEntityManager));

        $this->mockEntityManager->expects($this->once())
                ->method('getRepository')
                ->with($this->equalTo('SkyrocketLoginBundle:Employee'))
                ->will($this->returnValue($this->mockRepository));

        $this->mockRepository->expects($this->once())
                ->method('getAllEmployee')
                ->with($this->equalTo(1))
                ->will($this->returnValue($dummyResultArray));

        $this->mockController->showResignedAction();
    }

    /**
     * Test case to cover the showResignedAction with filter value as post
     *
     * @covers Skyrocket\LoginBundle\Controller\DefaultController::showResignedAction
     */
    public function testShowResignedWithFilterAction()
    {
        $dummyResultArray = $this->getResignedDummyFilterArray();

        $this->mockController->expects($this->once())
                ->method('getRequest')
                ->will($this->returnValue($this->mockRequest));

        $this->mockRequest->expects($this->any())
                ->method('getMethod')
                ->will($this->returnValue('POST'));

        $this->mockRequest->expects($this->at(1))
                ->method('get')
                ->with($this->equalTo('fromDate'))
                ->will($this->returnValue('08/04/2015'));

        $this->mockRequest->expects($this->at(2))
                ->method('get')
                ->with($this->equalTo('toDate'))
                ->will($this->returnValue('08/06/2015'));

        $this->mockController->expects($this->once())
                ->method('getDoctrine')
                ->will($this->returnValue($this->mockRegistry));

        $this->mockRegistry->expects($this->once())
                ->method('getManager')
                ->will($this->returnValue($this->mockEntityManager));

        $this->mockEntityManager->expects($this->once())
                ->method('getRepository')
                ->with($this->equalTo('SkyrocketLoginBundle:Employee'))
                ->will($this->returnValue($this->mockRepository));

        $this->mockRepository->expects($this->once())
                ->method('getFilteredEmployeesResigned')
                ->with($this->equalTo(1), $this->equalTo('2015-08-04 00:00:00'), $this->equalTo('2015-08-06 00:00:00'))
                ->will($this->returnValue($dummyResultArray));

        $this->mockController->showResignedAction();
    }

    public function getDummyArray()
    {
        $dummyArray = array('0' => array('username' => 'test', 'email' => 'test@123.com', 'isResigned' => '0', 'id' => '2'));

        return $dummyArray;
    }

    public function getResignedDummyArray()
    {
        $dummyArray = array('0' => array('username' => 'test', 'email' => 'test@123.com', 'isResigned' => '1', 'id' => '2'));

        return $dummyArray;
    }

    public function getResignedDummyFilterArray()
    {
        $dummyArray = array('0' => array('username' => 'wwwwwddd', 'email' => 'asdasd@ddd.hhh', 'isResigned' => '0', 'id' => '7'));

        return $dummyArray;
    }

    public function getDummyFilterArray()
    {
        $dummyArray = array('0' => array('username' => 'wwwwwddd', 'email' => 'asdasd@ddd.hhh', 'isResigned' => '0', 'id' => '7'));

        return $dummyArray;
    }

}
