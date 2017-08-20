<?php
//common config for web and console application
return [
    'components' => [

        'db' => [
            'class'   => 'yii\db\Connection',
            'charset' => 'utf8',
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

    'params' => array_merge(require_once('params.php'), require_once('local/local.params.php'), [

    ]),
];