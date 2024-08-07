<?php

namespace App\Exception;


class CustomerNotFoundException extends \RuntimeException
{

    public function __construct(int $id)
    {
        parent::__construct("Customer #" . $id . " was not found");
    }

}