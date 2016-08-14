<?php
    require_once('Parser.php');

    error_reporting(E_ALL);

    $csvfile = 'input.csv';
    $jsonfile = 'input.json';

    //parse csv to object
    Parser::parse($csvfile);

    //parse json array to csv
    Parser::parse($jsonfile);
?>
