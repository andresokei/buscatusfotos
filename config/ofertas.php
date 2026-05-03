<?php

return [
    'precios' => [
        // TEMPORAL: precios reducidos para prueba real de pago.
        1 => 50,       // 1 foto = 0,50 EUR
        2 => 50,       // 2 fotos = 0,50 EUR
        3 => 50,       // 3 fotos = 0,50 EUR
        4 => 50,       // 4 fotos = 0,50 EUR
        5 => 50,       // 5 fotos = 0,50 EUR
        6 => 50,       // 6 fotos = 0,50 EUR
        'extra' => 50, // Cada foto adicional +0,50 EUR
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
