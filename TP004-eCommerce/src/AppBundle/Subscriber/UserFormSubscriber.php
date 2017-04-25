<?php

namespace AppBundle\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserFormSubscriber implements EventSubscriberInterface {

    private $request;
    private $passwordEncoder;
    public function __construct(RequestStack $request, UserPasswordEncoder $passwordEncoder){
        $this->request = $request;
        $this->passwordEncoder = $passwordEncoder;
    }


    public static function getSubscribedEvents() {
        return [
            FormEvents::POST_SET_DATA => 'postSetData',
            FormEvents::POST_SUBMIT => 'postSubmit',
        ];
    }

    public function postSetData(FormEvent $event){

        //saisie du formulaire
        $data = $event->getData();
        //formulaire
        $form = $event->getForm();
        //entity origin
        $entity = $form->getData();

        $currentRoute = $this->request->getMasterRequest()->get('_route');

        if($entity->getId() && $currentRoute != 'app.profile.update.password') {
            $form->remove('username')
                ->remove('email')
                ->remove('password');
        }

        if($entity->getId() === null) {
            $form->remove('address')
                ->remove('zipcode')
                ->remove('city')
                ->remove('country');
        }

        if($currentRoute === 'app.profile.update.password') {
            $form->remove('address')
                ->remove('zipcode')
                ->remove('city')
                ->remove('country')
                ->remove('username')
                ->remove('email');
        }

    }

    public function postSubmit(FormEvent $event){

        $data = $event->getData();
        $form = $event->getForm();
        $entity = $form->getData();

        $currentRoute = $this->request->getMasterRequest()->get('_route');

        if($entity->getId() && $currentRoute === 'app.profile.update.password') {
            $passwordEncrypted = $this->passwordEncoder->encodePassword($entity,$data->getPassword());
            $entity->setPassword($passwordEncrypted);
        }

    }
}