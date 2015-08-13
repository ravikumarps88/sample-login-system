<?php

namespace Skyrocket\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * doctrine entity manager
     * @var Obeject
     */
    public $em;

    /**
     * This function is used to preexecute commonly used services, to reduce the number of requests
     *
     */
    public function preExecute()
    {
        $this->em = $this->getDoctrine()->getManager();
    }

    /**
     * Function to display admin dashboard with listing all employees
     *
     * @return HTML
     * @throws AccessDeniedException
     */
    public function dashboardAction()
    {
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {

            $fromDate = date("Y-m-d H:i:s", strtotime($request->get('fromDate')));
            $toDate = date("Y-m-d H:i:s", strtotime($request->get('toDate')));

            $employees = $this->getDoctrine()->getManager()->getRepository('SkyrocketLoginBundle:Employee')->getFilteredEmployees($resignedFlag = 0, $fromDate, $toDate);
        } else {

            $employees = $this->getDoctrine()->getManager()->getRepository('SkyrocketLoginBundle:Employee')->getAllEmployee();
        }

        return $this->render('SkyrocketLoginBundle:Default:index.html.twig', array('employees' => $employees));
    }

    /**
     * Function to make the employee resigned
     * @param int $employeeId Employee id
     *
     * @return RedirectResponse
     */
    public function makeEmployeeResignedAction($employeeId)
    {
        $em = $this->getDoctrine()->getManager();
        $employees = $em->getRepository('SkyrocketLoginBundle:Employee')->find($employeeId);
        $employees->setIsResigned(1);
        $employees->setResignedDate(new \DateTime("now"));
        $em->persist($employees);
        $em->flush();

        return $this->redirectToRoute('skyrocket_admin_homepage');
    }

    /**
     * Function to list all resigned employees
     *
     * @return RedirectResponse
     */
    public function showResignedAction()
    {
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {

            $fromDate = date("Y-m-d H:i:s", strtotime($request->get('fromDate')));
            $toDate = date("Y-m-d H:i:s", strtotime($request->get('toDate')));

            $employees = $this->getDoctrine()->getManager()->getRepository('SkyrocketLoginBundle:Employee')->getFilteredEmployeesResigned($resignedFlag = 1, $fromDate, $toDate);
        } else {

            $employees = $this->getDoctrine()->getManager()->getRepository('SkyrocketLoginBundle:Employee')->getAllEmployee($resignedFlag = 1);
        }

        return $this->render('SkyrocketLoginBundle:Default:showResigned.html.twig', array('employees' => $employees));
    }

}
