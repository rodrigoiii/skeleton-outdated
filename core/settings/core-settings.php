<?php

# container
include core_path("settings/container.php");

# controller registered
include core_path("settings/registered-controllers.php");

# middleware registered
include core_path("settings/registered-global-middlewares.php");

# eloquent settings
include core_path("settings/eloquent.php");

# tracy debug bar
include core_path("settings/tracy.php");