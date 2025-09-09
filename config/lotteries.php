<?php

return [

    /*
    |-----------------------------------------------------------------------
    | Loterías y tipos disponibles
    |-----------------------------------------------------------------------
    | Agrega aquí todas las loterías y sus “juegos/tipos”.
    | La clave es un slug estable; "label" es lo que verán en el panel.
    */
    'providers' => [
        'loteria_tachira' => [
            'label' => 'Lotería del Táchira',
            'types' => [
                'triple_a' => 'Triple A',
                'triple_b' => 'Triple B',
                'triple_z' => 'Triple Z',
            ],
        ],
        'loteria_nacional' => [
            'label' => 'Lotería Nacional',
            'types' => [
                'triple' => 'Triple',
            ],
        ],
        // Agrega más aquí…
        // 'loteria_zulia' => [
        //     'label' => 'Lotería del Zulia',
        //     'types' => [
        //         'triple_a' => 'Triple A',
        //     ],
        // ],
    ],

];
