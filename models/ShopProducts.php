<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_products".
 *
 * @property int $id
 * @property int $shop_id
 * @property string $name
 * @property string $url
 * @property string $categoryId
 * @property string $price
 * @property string $picture
 * @property string $description
 *
 * @property Shop $shop
 */
class ShopProducts extends \yii\db\ActiveRecord
{
    const STATUS_NEW      = 'new';
    const STATUS_INDEXED  = 'handled';
    const STATUS_DISABLED = 'disabled';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id'], 'required'],
            [['shop_id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['url', 'picture'], 'string', 'max' => 511],
            [['categoryId', 'price'], 'string', 'max' => 127],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shop::className(), 'targetAttribute' => ['shop_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shop_id' => Yii::t('app', 'Shop ID'),
            'name' => Yii::t('app', 'Name'),
            'url' => Yii::t('app', 'Url'),
            'categoryId' => Yii::t('app', 'Category ID'),
            'price' => Yii::t('app', 'Price'),
            'picture' => Yii::t('app', 'Picture'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
}
