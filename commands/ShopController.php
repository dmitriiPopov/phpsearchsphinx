<?php
namespace app\commands;

use app\components\import\ImportComponent;
use app\models\Shop;
use app\models\ShopProducts;
use yii\helpers\Console;
use Yii;

/**
 * Class ShopController
 * @package common\commands
 */
class ShopController extends \yii\console\Controller
{
    /**
     * Handle new Shops.
     * Should set this command on CRON
     *
     * @run php yii shop/handle-new
     */
    public function actionHandleNew()
    {
        Console::output('Start');

        //check if new shops exist
        foreach (Shop::find()->andWhere(['status' => Shop::STATUS_NEW])->each() as $shop) {/**@var $shop Shop*/

            Console::output(sprintf('Handle Shop #%d (%s)', $shop->id, $shop->name));

            //if shop has already had products...
            if ($shop->getShopProducts()->exists()) {
                //delete all previous products
                ShopProducts::deleteAll(['shop_id' => $shop->id]);
                Console::output('Old products have been deleted.');
            }

            Console::output('Start import...');
            //import products from Shop's file ...
            $imported = Yii::$app->import->getImporter(ImportComponent::IMPORTER_SHOP_CSV)->import([
                'shop' => $shop,
            ]);

            //if products have been imported...
            if ($imported) {
                Console::output('Products have been imported');
                //set status as indexed
                $shop->setAttribute('status', Shop::STATUS_INDEXED);
                //save Shop
                if ($shop->save()) {
                    Console::output('Shop has been marked as Indexed');
                }
            } else {
                Console::output('Products have NOT been imported');
            }

        }

        Console::output('End');
    }
}
