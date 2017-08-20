<?php

namespace app\models\forms;

use app\models\ShopProducts;
use Yii;
use yii\base\Model;
use app\models\Shop;
use yii\helpers\HtmlPurifier;
use app\components\UploadedFile;

/**
 * LoginForm is the model behind the login form.
 *
 * @property Shop|null $model This property is read-only.
 *
 */
class ShopForm extends Model
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @var string
     */
    public $name;

    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @var string
     */
    public $status;

    /**
     * @var
     */
    public $file_csv_column_separator = ';';

    /**
     * @var Shop
     */
    private $model;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['name', 'status'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['file'], 'required', 'on' => [self::SCENARIO_CREATE]],

            ['file_csv_column_separator', 'default', 'value' => ';', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            //exclude xss atack
            [['name', 'status', 'file_csv_column_separator'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv', 'checkExtensionByMimeType' => false, 'on' => [self::SCENARIO_CREATE]],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'csv', 'checkExtensionByMimeType' => false, 'on' => [self::SCENARIO_UPDATE]],
        ];
    }

    public function save($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        if ($this->model) {
            //prepare old absolute file path
            $oldModel = clone $this->model;
            //set common from attributes to model
            $this->model->setAttributes($this->getAttributes());
            //if file has been uploaded...
            $filename = null;
            if ($this->file) {
                //set file data
                $this->model->filename_real = HtmlPurifier::process($this->file->name);
                //create name for new file
                $filename = sha1(uniqid() . time()) . '.' . $this->file->extension;
                $this->model->filename = $filename;
                //mark model as new
                $this->model->status = Shop::STATUS_NEW;
            }
            //save model
            if ($this->model->save($runValidation)) {
                //if file has been uploaded...
                if ($this->file) {
                    //remove old file
                    if (in_array($this->scenario, [self::SCENARIO_UPDATE])) {
                        //delete old file via old model
                        $oldModel->deleteFile();
                    }
                    //save new file on server
                    $this->file->saveAs(Shop::getAbsoluteFilePath() . '/' . $filename);
                    //delete all ol products (from previous file)
                    ShopProducts::deleteAll(['shop_id' => $this->model->id]);
                }
                return true;
            } else {
                $this->addErrors($this->model->getErrors());
            }

        }

        return false;
    }


    /**
     * @return Shop|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Shop $model
     * @param boolean $setAttributes
     * @return $this
     */
    public function setModel(Shop $model, $setAttributes = false)
    {
        $this->model = $model;
        if ($setAttributes) {
            $this->setAttributes($model->attributes);
        }

        return $this;
    }

    /**
     * Get list of available statuses for form
     * @return array
     */
    public function getStatusList()
    {
        $statuses = Shop::getStatusesLabels();

        //if products has NOT already imported for shop...
        if ($this->model && !$this->model->getShopProducts()->exists()) {
            //remove status for shop with products
            unset($statuses[Shop::STATUS_INDEXED]);
        } else {
            //remove status for shop with products
            unset($statuses[Shop::STATUS_NEW]);
        }

        return $statuses;
    }
}
