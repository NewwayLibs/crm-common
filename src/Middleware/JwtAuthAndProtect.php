<?php

namespace Newway\CrmCommon\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class JwtAuthAndProtect extends BaseMiddleware
{
    protected $service;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @internal param null|string $guard
     */
    public function handle($request, Closure $next)
    {

        $this->service = config('service-info.name');


        $this->checkForToken($request);

        try {

            if (! $token = $this->auth->parseToken()->check(true)) {
                throw new UnauthorizedHttpException('jwt-auth', 'Provided token is invalid');
            }

            $user = $token->get('user');
            $permissions = $this->getPermissions($token->get('permissions'));

            if (! $permissions) {
                throw new AccessDeniedHttpException('You do not have enough permissions');
            }

            $request->attributes->add(['jwt-user' => $user, 'jwt-permissions' => array_dot($permissions) ]);

        } catch (JWTException $e) {
            throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }

        return $next($request);


    }

    private function preparePermissions($permissions){

        $result = [];

        foreach ($permissions as $key => $value) {
            array_set($result, $value, 1);
        }

        return $result;

    }

    private function getPermissions($permissions){

        $permissions = $this->preparePermissions($permissions);

        return array_get($permissions, $this->service);
    }
}
