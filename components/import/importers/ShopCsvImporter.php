<?php
namespace app\components\import\importers;

use app\models\ShopProducts;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\models\Shop;
use Yii;

/**
 * Class ShopImporter
 * @package app\components\import\importers
 */
class ShopCsvImporter extends Importer
{
    //matched columns in csv
    const ROW_NAME        = 0;
    const ROW_URL         = 1;
    const ROW_CATEGORY_ID = 2;
    const ROW_PRICE       = 3;
    const ROW_PICTURE     = 4;
    const ROW_DESCRIPTION = 5;

    /**
     * @param array $params
     * @return mixed
     * @throws Exception
     * @return boolean
     */
    public function import($params = [])
    {
        /**@var $shop */
        $shop            = ArrayHelper::getValue($params, 'shop');
        $hasHeader       = ArrayHelper::getValue($params, 'hasHeader', true);
        $batchInsertSize = ArrayHelper::getValue($params, 'batchInsertSize', 10000);

        //validate input Shop model
        if ( ! $shop instanceof Shop) {
            throw new Exception('You have to set `shop` in input params!');
        }

        //open shop file
        $file = fopen($shop->getAbsoluteFile(), 'r');

        //pop header row from file
        if ($hasHeader) {
            $headerRow = fgetcsv($file, null, $shop->file_csv_column_separator);
        }

        //shop product columns
        $batchInsertColumns = ['shop_id', 'name', 'url', 'categoryId', 'price', 'picture', 'description'];

        //list for products batch insert data
        $batchInsertRows = [];

        //get rows from file
        while ($row = fgetcsv($file, null, $shop->file_csv_column_separator)) {
            //add matched row data to batch insert data list
            $batchInsertRows[] = [
                $shop->id,
                ArrayHelper::getValue($row, self::ROW_NAME),
                ArrayHelper::getValue($row, self::ROW_URL),
                ArrayHelper::getValue($row, self::ROW_CATEGORY_ID),
                ArrayHelper::getValue($row, self::ROW_PRICE),
                ArrayHelper::getValue($row, self::ROW_PICTURE),
                ArrayHelper::getValue($row, self::ROW_DESCRIPTION),
            ];

            //if array batch insert data is filled then to save it...
            if (count($batchInsertRows) >= $batchInsertSize) {
                //batch insert
                $this->batchInsert($batchInsertColumns, $batchInsertRows);
                //clear stack for next portion of batch insert data
                $batchInsertRows = [];
            }
        }

        //save out-of-loop data
        if (!empty($batchInsertRows)) {
            //batch insert
            $this->batchInsert($batchInsertColumns, $batchInsertRows);
        }

        return true;
    }

    /**
     * @param array $columns
     * @param array $$rows
     * @return int
     * @throws \yii\db\Exception
     */
    protected function batchInsert($columns, $rows)
    {
        //batch insert
        return Yii::$app->db->createCommand()->batchInsert(ShopProducts::tableName(), $columns, $rows)->execute();
    }
}