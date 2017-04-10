<?php

namespace AppBundle\Repository;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends \Doctrine\ORM\EntityRepository{

    /*
     * Les methodes doivent retournées les résultats de requetes !!
     *      Avant-dernière méthode : getQuery()
     *
     *      Derniére méthode : mode de récup des datas
     *          - getResult()               : le plus classique, un tableau d'instance
     *          - getArrayResult()          : tableau de tableau
     *          - getOneOrNulResult()       : une instance ou valeur null
     *          - getScalarResult()         : tableau d'une valeur scalaire (valeur simple)
     *          - getSingleScalarResult()   : une valeur scalaire
     *          - getSingleResult()         : valeur d'une colonne
     *
     *      select :
     *          - utilisation des alias
     *          - séléction de colonnes > créer des tableaux
     *
     *      where :
     *          - la première condition doit utiliser where()
     *          - les conditions suivantes doivent utiliser andWhere()/orWhere()
     *          - utiliser des paramètres de requetes
     *              - donner une valeur aux paramètres avec setParameters()
     *
     *      join :
     *          - premier param : propriété relationnelle
     *          - deuxième param : alias de l'entité jointe
     *          - pas de ON
     *
     *      orderby :
     *          - premier param : propriété pour le tri
     *          - deuxième param (optionnel): ordre de tri (ASC by default, ou DESC)
     *
     *      limit :
     *          - setMaxResult() :
     *
     *      offset :
     *          - setFirstResult() :
     *
     *      alias : AS ou space
     *
     *      having (condition sur group_by) : utilise where
     *
     */

    public function getResult(){

 /*       $results = $this
            ->createQueryBuilder('contact')
            ->select('contact.firstName, contact.lastName, contact.email')
            ->where('contact.firstName LIKE :name')
            ->andWhere('contact.email LIKE :domain')
            ->setParameters([
                'name' => '%au%',
                'domain' => '%.com'
            ])
            ->getQuery()
            ->getResult()
        ;
*/

/*        $results = $this
            ->createQueryBuilder('contact')
            ->select('contact.id, contact.firstName, contact.lastName, contact.email, hobbies.name')

            ->join('contact.subject','subject')
            ->join('contact.hobbies', 'hobbies')

            ->where('subject.content LIKE :subject')
            ->andWhere('hobbies.name = :hobby')

            ->setParameters([
                'subject' => '%justice%',
                'hobby' => 'Escalade'
            ])

            ->orderBy('contact.firstName', 'DESC')

            ->setMaxResults(5)

            ->getQuery()
            ->getResult()
        ;
*/

/*        $results = $this
            ->createQueryBuilder('contact')

            ->select('COUNT(contact.id) AS count_contact, hobby.name')
            ->join('contact.hobbies','hobby')

            ->groupBy('hobby.id')
            ->getQuery()
            ->getResult()
        ;
*/

        $results = $this
            ->createQueryBuilder('contact')
            ->select('contact.firstName, contact.lastName, GROUP_CONCAT(hobby.name)')
            ->join('contact.hobbies','hobby')
            ->groupBy('contact.firstName')
            ->getQuery()
            ->getResult()
        ;

        return $results;
    }
}
