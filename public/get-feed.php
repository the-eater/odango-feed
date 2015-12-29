<?php

require __DIR__ . '/../vendor/autoload.php';

Odango\OdangoFeed\Registry::init('mysql:dbname=odango', 'root', '');

$feed = Odango\OdangoFeed\Feed::getById($argv[1]);

var_dump($feed->collectFeed());
