<?php

return [
    'precios' => [
        1 => 600,       // 1 foto = 6,00 EUR
        2 => 1000,      // 2 fotos = 10,00 EUR
        3 => 1300,      // 3 fotos = 13,00 EUR
        4 => 1600,      // 4 fotos = 16,00 EUR
        5 => 1900,      // 5 fotos = 19,00 EUR
        6 => 2200,      // 6 fotos = 22,00 EUR
        'extra' => 300, // Cada foto adicional +3,00 EUR
    ],

    'descarga' => [
        'expiracion_horas' => 72, // Tiempo de validez del token
        'max_intentos' => 3,      // Maximo intentos de descarga
    ],

    'admin' => [
        'max_file_size' => 10485760, // 10MB en KB
        'allowed_types' => ['jpeg', 'png', 'jpg', 'gif'],
    ],
];
