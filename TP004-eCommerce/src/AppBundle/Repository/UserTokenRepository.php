<?php

namespace AppBundle\Repository;

/**
 * UserTokenRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserTokenRepository extends \Doctrine\ORM\EntityRepository {

    public function userTokenFlushCommand() {

        $result = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->delete('AppBundle:UserToken', 'userToken')
            ->where('userToken.expirationDate < :date')
            ->setParameter('date', new \DateTime('-1 days'))
            ->getQuery()
            ->execute();

        return $result;
    }
}
