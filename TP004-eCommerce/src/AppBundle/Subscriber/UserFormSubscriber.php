<?php

namespace AppBundle\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserFormSubscriber implements EventSubscriberInterface {


    public static function getSubscribedEvents() {
        return [
            FormEvents::POST_SET_DATA => 'postSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function postSetData(FormEvent $event){

        //saisie du formulaire
        $data = $event->getData();

        //formulaire
        $form = $event->getForm();

        //entity origin
        $entity = $form->getData();

        if($entity->getId()) {
            $form->remove('username')
                ->remove('email')
                ->remove('password');
        }else{
            $form->remove('address')
                ->remove('zipcode')
                ->remove('city')
                ->remove('country');
        }
    }

    public function preSubmit(FormEvent $event){

        $data = $event->getData();
        $form = $event->getForm();
        $entity = $form->getData();

        //exit(dump($data,$form, $entity));
    }
}