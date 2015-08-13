<?php

namespace Skyrocket\LoginBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Service to manage employee registration process
 *
 * @author Ravikumar P S <ravikumar.s@tpintel.com>
 */
class SkyrocketRegistraionListener
{

    /**
     * Container object
     * @var Object
     */
    protected $container;

    /**
     * Service Constructor
     * @param ContainerInterface $container For getting the container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Function used to support registration process
     * @param Request $request Request object
     *
     * @return RedirectResponse
     */
    public function userRegistration(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->setIsResigned(0); // Setting admin flag to 0
        $user->setJoiningDate(new \DateTime("now"));
        $em = $this->container->get('doctrine')->getManager();
        $group = $em->getRepository('SkyrocketLoginBundle:EmployeeGroup')->getEmployeeGroupId();
        $group = $em->getRepository('SkyrocketLoginBundle:EmployeeGroup')->find($group);
        $user->addGroup($group);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $event = new FormEvent($form, $request);
            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {

                $url = $this->container->get('router')->generate('skyrocket_admin_homepage');
                $response = new RedirectResponse($url);
            }

            return $response;
        }

        return $this->container->get('templating')->renderResponse('SkyrocketLoginBundle:Registration:register.html.twig', array(
                    'form' => $form->createView(),
        ));
    }
}
