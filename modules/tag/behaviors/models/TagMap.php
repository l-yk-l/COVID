<?php
namespace app\modules\tag\behaviors\models;

use Yii;
use yii\base\Model;
use app\modules\tag\models\Tag;
use app\modules\tag\models\Tagset;

class TagMap extends Model
{
    private $_tags;
    private $_class;
    private $_item_id;
    private $_tagMap;
    
    /*public function __construct($class, $item_id){
        parent::__construct();
        $this->_class = $class;
        $this->_item_id = $_item_id;
    }*/

    public function __get($key){
        //$tagMap_key = 'c_'.$key;
        $tagMap_key = $key;
        
        if(isset($this->_tagMap[$tagMap_key])){
            return implode(',', $this->_tagMap[$tagMap_key]);
        }
        //return parent::__get($key);
    }

    public function fill($tagsArray, $class, $item_id=0){
        $this->_tags = $tagsArray;
    }

    protected function setClass($class){
        $this->_class = $class;
    }

    public function getTags(){
        return $this->_tags;
    }

    public function getTagNames()
    {
        return implode(', ', $this->_tags);
    }

    public function setTagNames($values)
    {
        $this->_tags = $this->filterTagValues($values);
    }

    public function afterSave()
    {
        if(!$this->owner->isNewRecord) {
            $this->beforeDelete();
        }

        if(count($this->_tags)) {
            $tagset = [];
            $modelClass = $this->class;

            foreach ($this->_tagMap as $tagCat => $tagArr) {
                foreach ($tagArr as $key => $tag) {
                    if (!($tag = Tag::findOne(['name' => $name]))) {
                        $tag = new Tag(['name' => $name]);
                    }
                    $tag->frequency++;
                    if ($tag->save()) {
                        $updatedTags[] = $tag;
                        $tagAssigns[] = [$modelClass, $this->item_id, $tag->tag_id];
                    }
                }
            }

            /*foreach ($this->_tags as $name) {
                if (!($tag = Tag::findOne(['name' => $name]))) {
                    $tag = new Tag(['name' => $name]);
                }
                $tag->frequency++;
                if ($tag->save()) {
                    $updatedTags[] = $tag;
                    $tagAssigns[] = [$modelClass, $this->owner->primaryKey, $tag->tag_id];
                }
            }*/

            if(count($tagAssigns)) {
                Yii::$app->db->createCommand()->batchInsert(Tagset::tableName(), ['class', 'item_id', 'tag_id'], $tagAssigns)->execute();
                $this->owner->populateRelation('tags', $updatedTags);
            }
        }
    }

    public function beforeDelete()
    {
        $pks = [];

        foreach($this->owner->tags as $tag){
            $pks[] = $tag->primaryKey;
        }

        if (count($pks)) {
            Tag::updateAllCounters(['frequency' => -1], ['in', 'tag_id', $pks]);
        }
        Tag::deleteAll(['frequency' => 0]);
        TagAssign::deleteAll(['class' => get_class($this->owner), 'item_id' => $this->owner->primaryKey]);
    }

    /**
     * Filters tags.
     * @param string|string[] $values
     * @return string[]
     */
    public function filterTagValues($values)
    {
        return array_unique(preg_split(
            '/\s*,\s*/u',
            preg_replace('/\s+/u', ' ', is_array($values) ? implode(',', $values) : $values),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));
    }
}