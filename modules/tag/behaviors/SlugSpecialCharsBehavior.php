<?php
namespace app\modules\tag\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use \yii\validators\Validator;
use app\modules\tag\models\Tag;
use app\modules\tag\models\Tagset;
use app\modules\tag\models\TagCategory;
use app\modules\tag\behaviors\models\TagMap;
use yii\behaviors\SluggableBehavior;


class SlugSpecialCharsBehavior extends SluggableBehavior{
    public $ensureUnique = true;
    
    public $delimiter = '_';

    protected function generateSlug($slugParts){
        $slug = parent::generateSlug($slugParts);
        $slug = str_replace('-', $this->delimiter, $slug);
        return $slug;
    }

    protected function generateUniqueSlug($baseSlug, $iteration){
        $slug = parent::generateUniqueSlug($baseSlug, $iteration);
        $slug = str_replace('-', $this->delimiter, $slug);
        return $slug;
    }
}