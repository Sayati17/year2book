<?php
    $config=[
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'year2book',
    ];
    $conn = new mysqli(
        $config["server"],
        $config["username"],
        $config["password"],
        $config["database"]
    );
?>