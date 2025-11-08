<?php
use craft\helpers\App;

return [
    'id' => App::env('CRAFT_APP_ID') ?: 'CraftCMS',

    'modules' => [
        'custom-module' => [
            'class' => \modules\Module::class,
        ],
    ],
    'bootstrap' => ['custom-module'],
];
