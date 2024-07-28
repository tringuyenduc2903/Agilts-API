<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorEnabledResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function toResponse($request): JsonResponse|Response
    {
        $request->user()->tokens()->whereName('mobile-auth')->delete();

        return $request->wantsJson()
            ? response()->json([
                'token' => $request->user()->createToken('mobile-auth')->plainTextToken,
            ])
            : back()->with('status', Fortify::TWO_FACTOR_AUTHENTICATION_ENABLED);
    }
}
