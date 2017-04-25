<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 25/04/17
 * Time: 09:33
 */

namespace AppBundle\Event\Account;


use Symfony\Component\EventDispatcher\Event;


class AccountEvent extends Event {

    /*
     * La classe de l'événement fait l'interface entre le controleur et souscripteur (le controleur envoi les infos au souscripteur)
     * elle doit comporter des informations utiles aux actions à réalisées dans le souscripteur
     */

    private $username;
    private $email;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


}