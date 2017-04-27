<?php

namespace AppBundle\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SubCategorySubscriber implements EventSubscriberInterface {

    private $locales;

    public function __construct($locales){
        $this->locales = $locales;
    }

    public static function getSubscribedEvents() {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSubmit(FormEvent $event){

        $data = $event->getData();
        $form = $event->getForm();
        $entity = $form->getData();

        exit(dump($data, $entity));

        foreach ($this->locales as $key => $value) {
            $entity->translate($value)->setName($data['translations']["name_$value"]);
        }

        $entity->mergeNewTranslations();
    }
}