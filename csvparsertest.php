<?php
    require_once('Parser.php');

    error_reporting(E_ALL);

    $csvfile = 'input.csv';
    $jsonfile = 'input.json';

    try {
        //parse csv to object
        Parser::parse($csvfile);

        //parse json array to csv
        Parser::parse($jsonfile);

        echo 'done';
        
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>
