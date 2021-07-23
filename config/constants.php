<?php

return [
    'charge' => [
        'created' => 0,
        'paid' => 1,
        'refund' => 2,
    ],
    'booking' => [
        'created' => 0,
        'charged' => 1,
        'confirmed' => 2,
        'cancelled' => 3,
        'failed' => 4
    ],
    'trip' => [
        'created' => 0,
        'booked' => 1,
        'confirmed' => 2,
        'canceled' => 3
    ],
    'transfer' => [
        'incomplete' => 0,
        'complete' => 1,
    ],
    'notification' => [
        'booked' => 0,
        'confirmed' => 1,
        'canceled' => 2,
    ]

];
