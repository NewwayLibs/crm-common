<?php

namespace Newway\CrmCommon\Traits;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

trait ProtectActions
{
    protected $permissions;

    public function protect($action)
    {

        if (!$this->permissions) {
            $this->permissions = [];
        }

        $actions = $this->_prepareActionKeys($action);

        $result = false;

        foreach ($actions as $key) {
            if (array_key_exists($key, $this->permissions)) {
                $result = true;
            }
        }

        if (!$result) {
            throw new AccessDeniedHttpException('You do not have enough permissions');
        }

        return true;
    }

    private function _prepareActionKeys(string $action): array {

        $array = explode('.', $action);

        $result = ['*'];

        $last = false;

        foreach ($array as $val) {

            $last = $last ? $last.'.'.$val : $val;

            $result[] = $last;
            $result[] = $last.'.*';

        }

        return $result;
    }


}
