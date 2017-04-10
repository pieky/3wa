<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route(
     *     "/hello/{name}",
     *     name="app.default.hello",
     *     defaults={"name"= "anonyme"},
     *     requirements={"name" = "[a-z]+"}
     * )
     */
    public function helloAction($name){
        return $this->render('default/hello.html.twig', [
            'title' => 'Hi',
            'name' => $name
        ]);
    }

    /**
     * @Route("/", name="app.default.index")
     */
    public function indexAction(Request $request)
    {
        /*Request :
            - POST : $request->request->get(VAR)
            - GET : $request->query->get(VAR)
            - SERVER : $request->server->get(VAR)
            - HEADERS : $request->headers->get(VAR)
            - COOKIES : $request->cookies->get(VAR)
            - SESSION : $request->getSession->get(VAR)
            - FILES : $request->files->get(VAR)
        */
        return $this->render('default/index.html.twig', [
            'title' => 'Hey !',
            'ip' => $request->getClientIp(),
            'host' => $request->headers->get('host'),
            'get' => $request->query->get('variable')
        ]);

        /*return new Response('{"data" : ["value"]}',200, [
            'Content-type' => 'text/json',
            'toto' => 'titi'
        ]);*/
    }
}
