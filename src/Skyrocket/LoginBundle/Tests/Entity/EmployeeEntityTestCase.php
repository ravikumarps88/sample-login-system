<?php

namespace Skyrocket\LoginBundle\Tests\Entity;

use Skyrocket\LoginBundle\Entity\Employee;

/**
 * Class EmployeeEntityTestCase
 *
 * @package Skyrocket\LoginBundle\Tests\Entity
 */
class EmployeeEntityTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Skyrocket\LoginBundle\Entity\Employee
     */
    protected $employee;

    /**
     * Initial set up for the test
     */
    public function setUp()
    {
        $this->employee = new Employee();
    }

    /**
     * Test the setIsAdmin/ getIsAdmin
     *
     * @covers \Skyrocket\LoginBundle\Entity\Employee::setIsAdmin
     * @covers \Skyrocket\LoginBundle\Entity\Employee::getIsAdmin
     */
    public function testIsAdmin()
    {
        $this->employee->setIsAdmin(1);
        $this->assertEquals(1, $this->employee->getIsAdmin());
    }

    /**
     * Test the setIsResigned/getIsResigned
     *
     * @covers \Skyrocket\LoginBundle\Entity\Employee::setIsResigned
     * @covers \Skyrocket\LoginBundle\Entity\Employee::getIsResigned
     */
    public function testIsResigned()
    {
        $this->employee->setIsResigned(1);
        $this->assertEquals(1, $this->employee->getIsResigned());
    }

    /**
     * Test the setJoiningDate/getJoiningDate
     *
     * @covers \Skyrocket\LoginBundle\Entity\Employee::setJoiningDate
     * @covers \Skyrocket\LoginBundle\Entity\Employee::getJoiningDate
     */
    public function testJoiningDate()
    {
        $this->employee->setJoiningDate('2015-08-04 00:00:00');
        $this->assertEquals('2015-08-04 00:00:00', $this->employee->getJoiningDate());
    }

    /**
     * Test the setResignedDate/getResignedDate
     *
     * @covers \Skyrocket\LoginBundle\Entity\Employee::setResignedDate
     * @covers \Skyrocket\LoginBundle\Entity\Employee::getResignedDate
     */
    public function testResignedDate()
    {
        $this->employee->setResignedDate('2015-08-04 00:00:00');
        $this->assertEquals('2015-08-04 00:00:00', $this->employee->getResignedDate());
    }

}
