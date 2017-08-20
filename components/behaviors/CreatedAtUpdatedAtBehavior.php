<?php
namespace app\components\behaviors;

use yii\db\ActiveRecord;

/**
 * Class CreatedAtUpdatedAtBehavior
 * @package common\components\behaviors
 *
 * Behavior for insert/update datetime in format Y-m-d H:i:s for different ActiveRecords
 *
 * @property \yii\db\ActiveRecord $owner
 */
class CreatedAtUpdatedAtBehavior extends \yii\base\Behavior
{
    /**
     * @var string
     */
    public $datetimeFormat = 'Y-m-d H:i:s';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }

    /**
     * @return void
     */
    public function beforeInsert($event)
    {
        $this->owner->created_at = date($this->datetimeFormat);
    }

    /**
     * @return void
     */
    public function beforeUpdate($event)
    {
        $this->owner->updated_at = date($this->datetimeFormat);
    }
}