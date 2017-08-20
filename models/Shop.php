<?php

namespace app\models;

use app\components\behaviors\CreatedAtUpdatedAtBehavior;
use Yii;

/**
 * This is the model class for table "shop".
 *
 * @property int $id
 * @property string $name
 * @property string $filename_real
 * @property string $filename
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $file_csv_column_separator
 *
 * @property ShopProducts[] $shopProducts
 */
class Shop extends \yii\db\ActiveRecord
{
    //available statuses
    const STATUS_NEW      = 'new';
    const STATUS_INDEXING = 'handling';
    const STATUS_INDEXED  = 'handled';
    const STATUS_DISABLED = 'disabled';
    const STATUS_FAILED   = 'failed';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'string'],
            [['name'], 'string', 'max' => 127],
            [['filename_real', 'filename'], 'string', 'max' => 511],
            ['file_csv_column_separator', 'string', 'max' => 4],
            ['file_csv_column_separator', 'default', 'value' => ';'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Shop'),
            'filename_real' => Yii::t('app', 'File'),
            //'filename' => Yii::t('app', 'Filename'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'file_csv_column_separator' =>  Yii::t('app', 'Csv column separator'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [

            'createdAtUpdatedAtBehavior' => [
                'class' => CreatedAtUpdatedAtBehavior::className(),
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopProducts()
    {
        return $this->hasMany(ShopProducts::className(), ['shop_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getStatusesLabels()
    {
        return [
            self::STATUS_NEW      => Yii::t('app', 'New'),
            self::STATUS_INDEXING => Yii::t('app', 'Indexing'),
            self::STATUS_INDEXED  => Yii::t('app', 'Indexed'),
            self::STATUS_DISABLED => Yii::t('app', 'Disabled'),
            self::STATUS_FAILED   => Yii::t('app', 'Failed'),
        ];
    }

    /**
     * @return null
     */
    public function getStatusLabel()
    {
        $statuses = self::getStatusesLabels();

        return isset($statuses[$this->status]) ? $statuses[$this->status] : null;
    }

    /**
     * @return string
     */
    public static function getAbsoluteFilePath()
    {
        return Yii::$app->params['shopFilesAbsolutePath'];
    }

    /**
     * @return string
     */
    public function getAbsoluteFile()
    {
        return $this->filename ? self::getAbsoluteFilePath() . DIRECTORY_SEPARATOR . $this->filename : null;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->filename ? Yii::$app->params['shopFilesPath'] . '/' . $this->filename : null;
    }

    /**
     * Delete csv file from server
     * @return void
     */
    public function deleteFile()
    {
        if ($this->getAbsoluteFile()) {
            //delete csv file from server
            @unlink($this->getAbsoluteFile());
        }
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            //delete csv file with source data
            $this->deleteFile();

            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     * @return void
     */
    public function afterDelete()
    {
        parent::afterDelete();

        //delete all related products
        ShopProducts::deleteAll(['shop_id' => $this->id]);
    }
}
