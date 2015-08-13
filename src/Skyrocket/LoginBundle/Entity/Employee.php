<?php

namespace Skyrocket\LoginBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Skyrocket\LoginBundle\Repository\EmployeeRepository")
 * @ORM\Table(name="Employee")
 */
class Employee extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $isResigned;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $joiningDate;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $resignedDate;

    /**
     * @ORM\ManyToMany(targetEntity="Skyrocket\LoginBundle\Entity\EmployeeGroup")
     * @ORM\JoinTable(name="employee_user_group",
     *      joinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * Set isResigned
     *
     * @param  integer  $isResigned
     * @return Employee
     */
    public function setIsResigned($isResigned)
    {
        $this->isResigned = $isResigned;

        return $this;
    }

    /**
     * Get isResigned
     *
     * @return integer
     */
    public function getIsResigned()
    {
        return $this->isResigned;
    }

    /**
     * Set joiningDate
     *
     * @param  \DateTime $joiningDate
     * @return Employee
     */
    public function setJoiningDate($joiningDate)
    {
        $this->joiningDate = $joiningDate;

        return $this;
    }

    /**
     * Get joiningDate
     *
     * @return \DateTime
     */
    public function getJoiningDate()
    {
        return $this->joiningDate;
    }

    /**
     * Set resignedDate
     *
     * @param  \DateTime $resignedDate
     * @return Employee
     */
    public function setResignedDate($resignedDate)
    {
        $this->resignedDate = $resignedDate;

        return $this;
    }

    /**
     * Get resignedDate
     *
     * @return \DateTime
     */
    public function getResignedDate()
    {
        return $this->resignedDate;
    }

    /**
     * Add groups
     *
     * @param  \FOS\UserBundle\Model\GroupInterface $groups
     * @return Employee
     */
    public function addGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \FOS\UserBundle\Model\GroupInterface $groups
     */
    public function removeGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
