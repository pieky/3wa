<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 26/04/17
 * Time: 10:17
 */

namespace AppBundle\Subscriber;


use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventsSubscriber implements EventSubscriberInterface{

    private $maintenance;
    private $twig;
    private $translator;

    public function __construct($maintenance,\Twig_Environment $twig, Translator $translator){
        $this->maintenance = $maintenance;
        $this->twig = $twig;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents() {
        return [
            KernelEvents::REQUEST => 'requestKernel',
            KernelEvents::RESPONSE => 'responseKernel',
        ];
    }

    /*
     * ATTENTION : le type de paramètre change selon le type d'event
     *  ici pour un event REQUEST -> param = GetResponseEvent
     *  voir doc pour tt les types de params !
     */
    public function requestKernel(GetResponseEvent $event){

        $response = $event->getResponse();
        $request = $event->getRequest();

        //create response HTTP
        if($this->maintenance) {
            $view  = $this->twig->render('maintenance/maintenance.html.twig');
            $newResponse = new Response($view, 503);
            return $event->setResponse($newResponse);
        }
        $session = $request->getSession();
        if(!$session->has('cart')){
            $session->set('cart',null);
        }
    }

    public function responseKernel(FilterResponseEvent $event){

        $response = $event->getResponse();
        $content = $response->getContent();
        $request = $event->getRequest();

        $newContent = preg_replace(
            '/<div class="container main-container">/',
            '<div class="container main-container">
                <div class="alert alert-warning">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 
                    '.$this->translator->trans('operation.beta').'
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                </div>',
            $content
        );

        $newResponse = new Response($newContent, $response->getStatusCode(), [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block'
        ]);
        return $event->setResponse($newResponse);
    }

}