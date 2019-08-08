<?php
return [
    "defaultConnect" => "defaultMySQL",
    "options" => [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ],
    "connections" => [
        "defaultMySQL" => [
            "dataBase" => "test",
            "userName" => "root"
        ]
    ]
];