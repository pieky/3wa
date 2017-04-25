<?php

namespace AppBundle\Subscriber;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class AuthenticationSubscriber implements EventSubscriberInterface {

    private $mailer;
    private $contactMail;
    private $translator;
    private $twig;
    private $session;
    private $router;

    public function __construct(\Swift_Mailer $mailer, $contactMail, Translator $translator, \Twig_Environment $twig, Session $session, Router $router){
        $this->mailer = $mailer;
        $this->contactMail = $contactMail;
        $this->translator = $translator;
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }

    public static function getSubscribedEvents(){
        return [
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'authenticationSuccess',
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'authenticationFailure'
        ];
    }

    public function authenticationSuccess(AuthenticationEvent $event){

        /*
         * si l'user is not connect -> getUser renvoie 'anno.' -> annonyme
         * si l'user is not connect -> getUser renvoie une instance d'User
         */

        $user = $event->getAuthenticationToken()->getUser();

        // si l'user est connecté
        if($user instanceof User){
            $this->session->remove('auth_number_failure');
            /*
             * création du message
             *  -> la methode setFrom est obligatoire
             */
            $message = \Swift_Message::newInstance()
                ->setFrom($this->contactMail)
                //->setTo($user->getEmail())
                ->setContentType('text/html')
                ->setSubject(
                    $this->translator->trans('authentification.success.login.subject', [], 'mailing')
                )
                ->setBody(
                    $this->twig->render('mailing/authentication.success.html.twig', [
                        'username' => $user->getUsername()
                    ])
                )
            ;

            //envoi du msg
            $this->mailer->send($message);
        }
    }

    public function authenticationFailure(AuthenticationFailureEvent $event) {
        if($this->session->has('auth_number_failure')){
            $count = $this->session->get('auth_number_failure');
            $count++;
            $this->session->set('auth_number_failure', $count);
        } else {
            $this->session->set('auth_number_failure', 1);
        }

        if($this->session->get('auth_number_failure') > 2) {
            $this->session->getFlashBag()->set('notice', $this->translator->trans('flashMessages.user.connectionFailure', [], 'messages'));
        }
    }
}