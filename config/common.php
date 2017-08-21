<?php
//common config for web and console application
return [
    'components' => [

        'db' => [
            'class'   => 'yii\db\Connection',
            'charset' => 'utf8',
            'on afterOpen' => function($event) {
                // $event->sender refers to the DB connection
                $event->sender->createCommand("SET global net_buffer_length = 1000000")->execute();
                $event->sender->createCommand("SET global max_allowed_packet = 100000000")->execute();
            }
        ],

        'search' => [
            'class' => 'app\components\search\SearchComponent',
            'finders' => [
                //key
                'products' => [
                    //finder conf
                    'class' => 'app\components\search\finders\SphinxFinder',
                ],
            ],
        ],

        'import' => [
            'class' => 'app\components\import\ImportComponent',
            'importers' => [
                'shopCsv' => [
                    'class' => 'app\components\import\importers\ShopCsvImporter',
                ],
            ]
        ],
    ],

    'params' => \yii\helpers\ArrayHelper::merge(require_once('params.php'), require_once('local/local.params.php'), [

    ]),
];