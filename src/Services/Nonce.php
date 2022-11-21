<?php

namespace App\Services;

class Nonce
{
    private $random;

    public function __construct()
    {
        $random = random_bytes(5);
        $this->random = bin2hex($random);
    }

    public function generate()
    {
        return "nonce-" . $this->random;
    }
}
