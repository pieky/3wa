<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 20/04/17
 * Time: 10:36
 */

namespace AppBundle\Listener;


use AppBundle\Entity\UserToken;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserTokenListener {

    private $contactMail;
    private $translator;
    private $doctrine;
    private $mailer;
    private $twig;
    private $router;

    public function __construct($contactMail, Translator $translator, Registry $doctrine, \Swift_Mailer $mailer, \Twig_Environment $twig, Router $router){
        $this->contactMail = $contactMail;
        $this->translator = $translator;
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
    }

    public function prePersist(UserToken $entity, LifecycleEventArgs $args) {

        if($entity instanceof UserToken){
            $user = $this->doctrine->getRepository('AppBundle:User')->findOneBy([ 'email' => $entity->getEmail()]);
            $link = $this->router->generate('app.security.password.reset', [
                'email' => $entity->getEmail(),
                'token' => $entity->getToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = \Swift_Message::newInstance()
                    ->setFrom($this->contactMail)
                    //->setTo($user->getEmail())
                    ->setContentType('text/html')
                    ->setSubject(
                        $this->translator->trans('password.reset.subject', [], 'mailing')
                    )
                    ->setBody(
                        $this->twig->render('mailing/password.reset.html.twig', [
                            'username' => $user->getUsername(),
                            'link' => $link
                        ])
                    )
                ;

                //envoi du msg
                $this->mailer->send($message);

        }
    }

}