<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [];

    public function __construct(Application $app, Encrypter $encrypter)
    {
        parent::__construct($app, $encrypter);
        
        if(env('APP_DEBUG',false)) {
            $this->except = [
                route('logout'),
                '/api/*',
                route('mercado_pago_webhook')
            ];
        }else {
            $this->except = [
                route('logout'),
                route('mercado_pago_webhook')
            ];
        }
    }
}
