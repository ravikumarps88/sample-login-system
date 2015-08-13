<?php

namespace Skyrocket\LoginBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Employee repository class is used to write queries containing employee entity
 */
class EmployeeRepository extends EntityRepository
{
    /**
     * Function to get all employee details to display in the admin dashboard
     * @param Int $resignedFlag Resigned status
     *
     * @return Array
     */
    public function getAllEmployee($resignedFlag = 0)
    {
        $role='ROLE_EMPLOYEE';
        $qb = $this->createQueryBuilder('e')
            ->select('e.username', 'e.email', 'e.isResigned', 'e.id')
            ->leftJoin('e.groups', 'g')
            ->andWhere('e.isResigned =:resignedFlag')
            ->andWhere('g.roles LIKE :roles')
            ->setParameter('resignedFlag', $resignedFlag)
            ->setParameter('roles', '%"'.$role.'"%');

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * Function used to get filtered resigned employees
     *
     * @param Int    $resignedFlag Resigned status
     * @param String $fromDate     From date
     * @param String $toDate       To date
     *
     * @return Array
     */
    public function getFilteredEmployeesResigned($resignedFlag, $fromDate, $toDate)
    {
        $role='ROLE_EMPLOYEE';
        $em = $this->getEntityManager();
        $query = $em->createQuery('
              SELECT e.username,e.email,e.isResigned,e.id FROM SkyrocketLoginBundle:Employee e
              LEFT JOIN e.groups g
              WHERE e.joiningDate >=:fromDate AND e.resignedDate <=:toDate AND e.isResigned =:resignedFlag AND g.roles LIKE :roles
            ')
                ->setParameters(array('fromDate' => $fromDate, 'toDate' => $toDate, 'resignedFlag' => $resignedFlag, 'roles' =>  '%"'.$role.'"%'));
        $result = $query->getResult();

        return $result;
    }

    /**
     * Function used to get filtered employees
     *
     * @param Int    $resignedFlag Resigned status
     * @param String $fromDate     From date
     * @param String $toDate       To date
     *
     * @return Array
     */
    public function getFilteredEmployees($resignedFlag, $fromDate, $toDate)
    {
        $role='ROLE_EMPLOYEE';
        $em = $this->getEntityManager();
        $query = $em->createQuery('
              SELECT e.username,e.email,e.isResigned,e.id FROM SkyrocketLoginBundle:Employee e
              LEFT JOIN e.groups g
              WHERE e.joiningDate >=:fromDate AND e.joiningDate <=:toDate AND e.isResigned =:resignedFlag AND g.roles LIKE :roles
            ')
                ->setParameters(array('fromDate' => $fromDate, 'toDate' => $toDate, 'resignedFlag' => $resignedFlag, 'roles' =>  '%"'.$role.'"%'));
        $result = $query->getResult();

        return $result;
    }

}
