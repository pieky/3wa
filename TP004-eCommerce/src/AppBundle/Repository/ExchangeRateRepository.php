<?php

namespace AppBundle\Repository;

/**
 * ExchangeRateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExchangeRateRepository extends \Doctrine\ORM\EntityRepository
{
    public function updateExchangeRateCommand($currenciesRate){

        $result = null;
        $query = $this->createQueryBuilder('AppBundle:ExchangeRate');
        foreach ($currenciesRate as $code => $rate) {

            $result .= ' - '.$code .' = '.$rate ."\n";

            $query->update('AppBundle:ExchangeRate','er')
                ->set('er.rate', ':rate')
                ->where('er.code = :code')
                ->setParameters([
                    'code' => $code,
                    'rate' => $rate
                ])
                ->getQuery()
                ->execute();

        }

        return $result;
    }
}