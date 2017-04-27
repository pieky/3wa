<?php
/**
 * Created by PhpStorm.
 * User: wamobi10
 * Date: 27/04/17
 * Time: 16:30
 */

namespace AppBundle\Service;


class ProductUtilsService{

    public function maxAvailableOrder($stock, $max = 5){
        if($stock < $max) return $stock; else return $max;
    }

}