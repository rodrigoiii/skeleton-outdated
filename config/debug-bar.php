<?php

return [
    'enabled' => filter_var(app_env('DEBUG_BAR_ON', false), FILTER_VALIDATE_BOOLEAN)
];
