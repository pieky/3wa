<?php
namespace AppBundle\Service;


class StringUtilsService {

    public function generateToken($length, $cstrong = true){
        return bin2hex(openssl_random_pseudo_bytes($length, $cstrong));
    }

}