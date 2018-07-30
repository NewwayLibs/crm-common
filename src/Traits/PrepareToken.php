<?php

namespace Newway\CrmCommon\Traits;

trait PrepareToken
{
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function prepareToken($token)
    {
        return [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
}
