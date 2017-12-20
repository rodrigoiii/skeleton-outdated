<?php

use Tracy\Debugger;

Debugger::enable(config('app.debug_bar') ? Debugger::DEVELOPMENT : Debugger::PRODUCTION, storage_path("logs"));
Debugger::timer();