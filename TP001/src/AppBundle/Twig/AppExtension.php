<?php

namespace AppBundle\Twig;


class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface {

    /*
     * Ajout de fonctions custom
     * Doit retourner un tableau d'instance de Twig_SimpleFunction
     * 1er paramètre = nom de la fonction utilisée dans twig
     * 2eme paramètre =
     *      - callable -> array (de 2 paramètres) :
     *              1er = objet contenant la fonction à appeller
     *              2eme = nom de la fonction PHP à appeller
     *
     */
    public function getFunctions()
    {
        return [
            /*new \Twig_SimpleFunction('my_function', [$this, 'myFunction'])*/
            new \Twig_SimpleFunction('my_function', [$this, 'myFunction']),
            new \Twig_SimpleFunction('highlight', [$this, 'highlight'])
        ];
    }

    public function myFunction() {
        return 'This is my function';
    }

    public function highlight($word, $sentence) {
        $result = preg_replace("/$word/","<mark>$word</mark>",$sentence);
        return $result;
    }

    /*
     * Filter
     * Same principe que pour les fonctions, mais avec l'instance de Tiwg_SimpleFilter
     */
    public function getFilters(){
        return [
            new \Twig_SimpleFilter('password_hash', [$this, 'passwordHash'])
        ];
    }

    public function passwordHash($value){
        $result = password_hash($value, PASSWORD_BCRYPT, ['count' => 12]);
        return $result;
    }

    /*
     * Pour les globales, encore plus simple.
     * simple retour d'un array key => value
     */
    public function getGlobals()
    {
        return [
            'code_ga' => 'XXX-XX-121'
        ];
    }
}