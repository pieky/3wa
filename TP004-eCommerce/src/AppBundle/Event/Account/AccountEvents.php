<?php

namespace AppBundle\Event\Account;


class AccountEvents{

    /*
     * contient uniquement une liste de constantes
     *  la valeur de la constante correspond au nom unique de l'événement
     */
    const CREATE = 'app.event.account.create';
    const UPDATE = 'app.event.account.update';
    const DELETE = 'app.event.account.delete';
    const PASSWORD_CHANGE = 'app.event.account.password_change';

}