<?php

return [
    'precios' => [
        1 => 500,      // 1 foto = 5,00 €
        2 => 900,      // 2 fotos = 9,00 € (4,50€ cada una)
        3 => 1200,     // 3 fotos = 12,00 € (4,00€ cada una)
        4 => 1500,     // 4 fotos = 15,00 € (3,75€ cada una)
        5 => 1750,     // 5 fotos = 17,50 € (3,50€ cada una)
        6 => 2000,     // 6 fotos = 20,00 € (3,33€ cada una)
        'extra' => 300, // Cada foto adicional +3,00 €
    ],
    
    'descarga' => [
        'expiracion_horas' => 72, // Tiempo de validez del token
        'max_intentos' => 3,      // Máximo intentos de descarga
    ],
    
    'admin' => [
        'max_file_size' => 10485760, // 10MB en KB
        'allowed_types' => ['jpeg', 'png', 'jpg', 'gif'],
    ]
];