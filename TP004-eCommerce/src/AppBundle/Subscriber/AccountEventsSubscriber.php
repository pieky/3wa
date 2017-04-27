<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 25/04/17
 * Time: 09:39
 */

namespace AppBundle\Subscriber;

use AppBundle\Event\Account\AccountEvent;
use AppBundle\Event\Account\AccountEvents;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AccountEventsSubscriber implements EventSubscriberInterface{

    private $mailer;
    private $contactMail;
    private $translator;
    private $twig;
    private $router;
    private $request;

    public function __construct(\Swift_Mailer $mailer, $contactMail, Translator $translator, \Twig_Environment $twig, Router $router, RequestStack $request){
        $this->mailer = $mailer;
        $this->contactMail = $contactMail;
        $this->translator = $translator;
        $this->twig = $twig;
        $this->router = $router;
        $this->request = $request;
    }

    public static function getSubscribedEvents(){
        return [
            AccountEvents::CREATE => 'create',
            AccountEvents::PASSWORD_CHANGE => 'passwordChange',
        ];
    }

    public function create(AccountEvent $event){
        $message = \Swift_Message::newInstance()
            ->setFrom($this->contactMail)
            //->setTo($user->getEmail())
            ->setContentType('text/html')
            ->setSubject(
                $this->translator->trans('account.register.subject', [], 'mailing')
            )
            ->setBody(
                $this->twig->render('mailing/account.create.html.twig', [
                    'username' => $event->getUsername()
                ])
            )
        ;
        $this->mailer->send($message);
    }

    public function passwordChange(AccountEvent $event){
        $message = \Swift_Message::newInstance()
            ->setFrom($this->contactMail)
            //->setTo($user->getEmail())
            ->setContentType('text/html')
            ->setSubject(
                $this->translator->trans('password.reset.success.subject', [], 'mailing')
            )
            ->setBody(
                $this->twig->render('mailing/password.reset.success.html.twig', [
                    'username' => $event->getUsername(),
                    'locale' => $this->request->getMasterRequest()->getLocale()
                ])
            )
        ;
        $this->mailer->send($message);
    }

}