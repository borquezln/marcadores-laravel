<?php

return [
    'default_center' => [
        'latitude' => (float) env('MAP_DEFAULT_LATITUDE', -32.8896767),
        'longitude' => (float) env('MAP_DEFAULT_LONGITUDE', -68.8448381),
        'zoom' => (int) env('MAP_DEFAULT_ZOOM', 13),
    ],
];
