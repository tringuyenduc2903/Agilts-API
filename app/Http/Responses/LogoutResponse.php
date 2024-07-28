<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function toResponse($request): JsonResponse|Response
    {
        $request->user()->currentAccessToken()->delete();

        return $request->wantsJson()
            ? response('', 204)
            : redirect(Fortify::redirects('logout', '/'));
    }
}
