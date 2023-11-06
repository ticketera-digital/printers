<?php

namespace Ticketeradigital\Printers;

class PrintException extends \Exception
{
    public function __construct(string $message)
    {
        // $message = $response->json()['error'];
        parent::__construct($message);
    }

    public function __toString()
    {
        return __CLASS__.": {$this->message}\n";
    }
}
