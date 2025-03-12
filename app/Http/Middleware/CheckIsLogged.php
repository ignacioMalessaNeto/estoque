<?php

namespace App\Http\Middleware;

use App\Http\Controllers\api\TokensController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsLogged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $decoded = TokensController::verifyTokenIsValid($request);

        if ($decoded->status() === 401) {
            return $decoded; // Interrompe a requisição e retorna a resposta de erro
        }

        return $next($request);
    }
}
