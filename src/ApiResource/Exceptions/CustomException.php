<?php

declare(strict_types=1);

namespace App\ApiResource\Exceptions;

use Doctrine\DBAL\Exception;

class CustomException extends Exception
{
    private $data;

    public function setData($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }
}