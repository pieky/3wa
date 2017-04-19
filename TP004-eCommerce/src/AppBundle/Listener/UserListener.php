<?php

namespace AppBundle\Listener;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserListener {

    private $passwordEncoder;
    private $doctrine;

    public function __construct(UserPasswordEncoder $passwordEncoder, Registry $doctrine){
        $this->doctrine = $doctrine;
        $this->passwordEncoder = $passwordEncoder;
    }

    /*
     * le nom des méthodes reprends strictement le nom de l'événement
     * attention : le type des paramètres peut changer !!
     */

    public function postLoad(User $entity, LifecycleEventArgs $args) {
        // update user lastconnexion
        $entity->setLastConnection(new \DateTime());

        // maj bdd
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
    }

    public function prePersist(User $entity, LifecycleEventArgs $args) {

        // encoder password
        $passwordEncrypted = $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
        $entity->setPassword($passwordEncrypted);

        // selection du rôle
        $role = $this->doctrine->getRepository('AppBundle:Role')->findOneBy([
            'name' => 'ROLE_USER'
        ]);

        // assigner le rôle selectionné
        $entity->addRole($role);
        $entity->setLastConnection(new \DateTime());
    }
}