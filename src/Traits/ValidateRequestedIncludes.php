<?php

namespace Newway\CrmCommon\Traits;

trait ValidateRequestedIncludes
{
    function _validateRequestedIncludes() {
        $this->middleware(
                function ($request, $next) {

                    $availableIncludes = $this->transformer->getAvailableIncludes();
                    $requestedIncludes = $this->fractal->getRequestedIncludes();

                    foreach ($requestedIncludes as $row) {
                        if (!in_array($row, $availableIncludes)) {
                            return $this->errorWrongArgs($row . ' is not in the available includes');
                        }
                    }

                    return $next($request);
                }
        );
    }


}
