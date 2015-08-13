<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skyrocket\LoginBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

/**
 * Controller managing the registration
 *
 * @author Ravikumar P S <ravikumar.s@tpintel.com>
 */
class RegistrationController extends BaseController
{
    /**
     *
     * @param Request $request Registration request values
     *
     * @return RedirectResponse
     * @throws AccessDeniedException
     */
    public function registerAction(Request $request)
    {
        // Calling registraion service
        $skyrocketRegistrationService = $this->get('fos_skyrocket_registration');
        $returnObject = $skyrocketRegistrationService->userRegistration($request);

        return $returnObject;
    }

    /**
     * Tell the user to check his email provider
     *
     * @return HTML
     * @throws NotFoundHttpException
     */
    public function checkEmailAction()
    {
        $email = $this->get('session')->get('fos_user_send_confirmation_email/email');
        $this->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {

            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('SkyrocketLoginBundle:Registration:checkEmail.html.twig', array(
                    'user' => $user,
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     * @param Request $request Request data
     * @param String  $token   User token
     *
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {

            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {

            $url = $this->generateUrl('skyrocket_admin_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed
     *
     * @return HTML
     * @throws AccessDeniedException
     */
    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {

            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('SkyrocketLoginBundle:Registration:confirmed.html.twig', array(
                    'user' => $user,
        ));
    }
}
