<?php
require __DIR__."/tests/Bootstrap.php";

$app = Bootstrap::getApp();

$app['console']->run();

