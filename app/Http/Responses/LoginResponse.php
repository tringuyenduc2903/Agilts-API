<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return Response
     */
    public function toResponse($request): Response
    {
        $request->user()->tokens()->whereName('mobile-auth')->delete();

        return $request->wantsJson()
            ? response()->json([
                'two_factor' => false,
                'token' => $request->user()->createToken('mobile-auth')->plainTextToken,
            ])
            : redirect()->intended(Fortify::redirects('login'));
    }
}
