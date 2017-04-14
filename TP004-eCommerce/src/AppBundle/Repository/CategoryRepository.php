<?php

namespace AppBundle\Repository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository{

    public function getCategoriesByLocale($locale){

        $results = $this
            ->createQueryBuilder('category')
            ->join('category.translations','translations')

            ->where('translations.locale = :locale')
            ->andWhere('category.isActive = 1')
            ->setParameters([
                'locale' => $locale
            ])

            ->getQuery()
            ->getResult()
        ;

        return $results;
    }

    public function getOneCategoryByLocale($locale, $slug){

        $results = $this
            ->createQueryBuilder('category')
            ->join('category.translations','translations')

            ->where('translations.locale = :locale')
            ->andWhere('translations.slug = :slug')
            ->setParameters([
                'locale' => $locale,
                'slug' => $slug
            ])

            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $results;
    }
}
