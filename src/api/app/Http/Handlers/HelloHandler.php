<?php
namespace App\Http\Handlers;

class HelloHandler
{
    public function __invoke()
    {
        return "Ok";
    }
}
