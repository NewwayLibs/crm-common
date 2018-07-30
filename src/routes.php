<?php

Route::get('service-info', function () {
    return response()->json(config('service-info'));
});
