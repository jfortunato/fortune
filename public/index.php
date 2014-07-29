<?php

require_once __DIR__ . '/../bootstrap/bootstrap.php';

$output = new Fortune\Output\JsonOutput(new Fortune\Output\Header);
echo $output->prepare(array(["name" => "Fido"], ["name" => "Daisy"]));
