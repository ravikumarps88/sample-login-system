<?php

namespace Skyrocket\LoginBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EmployeeGroup repository class is used to write queries containing employee_group entity
 */
class EmployeeGroupRepository extends EntityRepository {

    /**
     * Function to get group id using specific role
     *
     * @return Integer
     */
    public function getEmployeeGroupId() {

        $role='ROLE_EMPLOYEE';
        $qb = $this->createQueryBuilder('g')
            ->select('g.id')
            ->where('g.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        $result = $qb->getQuery()->getResult();

        return $result[0]['id'];
    }
}
