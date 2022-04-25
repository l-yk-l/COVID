<?php
namespace app\modules\tag\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use \yii\validators\Validator;
use app\modules\tag\models\Tag;
use app\modules\tag\models\Tagset;
use app\modules\tag\models\TagCategory;
use app\modules\tag\models\TagCategoryAssign;
use app\modules\tag\behaviors\models\TagMap;


class TagBehavior extends Behavior
{
    private $_tagNames;

    private $_tags;

    private $_slugTitleMap;

    private $_slugIdMap;

    private $_catMap;

    private $_tagMap;

    private $_isFilled = false;

    private $_issetOwner = false;

    public function __construct(){
        parent::__construct();
        /*foreach ($this->getSlugTitleMap() as $slug => $cat_title) {
            $this->_tagMap[$slug] = array();
            //$this->_isFilled[$cat] = false;
        }*/
    }

    public function fill(){
        /*print_r($this->owner);
        if(!isset($this->_tagMap) && !$this->owner->isNewRecord){*/

            /*$tags = $this->owner->tags;
            $cat_tags = array();
            foreach ($tags as $key => $tag) {
                $cat_tags['c_'.$tag->category_id][] = $tag->title;
            }
            foreach ($cat_tags as $key => $tags) {
                //$this->_tagMap[$key] = implode(',', $tags);
                $this->_tagMap[$key] = $tags;
            }*/

            $tags = $this->owner->tags;
            $cat_tags = $this->tagCategories;
            foreach ($tags as $key => $tag) {
                if(isset($cat_tags[$tag->category_id])){
                    $this->_tagMap[$cat_tags[$tag->category_id]->slug][] = $tag->title;
                }
            }
            //echo '1';
            //print_r($this->_tagMap);
           /* foreach ($cat_tags as $key => $tags) {
                //$this->_tagMap[$key] = implode(',', $tags);
                $this->_tagMap[$key] = $tags;
            }*/
        //}
        //print_r($this->_tagMap);exit(0);
    }

    public function attach($owner){
        parent::attach($owner);
        foreach ($this->getSlugTitleMap() as $slug => $cat_title) {
            $this->_tagMap[$slug] = array();
            //$this->_isFilled[$cat] = false;
        }
        foreach ($this->getTagCategories() as $key => $cat) {
            $owner->validators[] = Validator::createValidator('safe', $this->owner, $cat->slug);
        }
        //print_r($this->owner->getCats()); exit(0);
    }

    /*public function hasMethod($name){
        if(parent::hasMethod('set'.$name) || parent::hasMethod('get'.$name)){
            return true;
        }
    }*/

    public function __get($key){
        //$_POST['get'][] = $key;
        if($this->canGetProperty($key)){
            /*if($this->owner!==null && !$this->_isFilled){
                $this->fill();
            }*/
            if(isset($this->_tagMap[$key])){
                if(!$this->_isFilled){
                    $this->fill();
                    $this->_isFilled = true;
                }
                return $this->_tagMap[$key];
            }
        }
        return parent::__get($key);
    }

    public function __set($key, $value){
        //$_POST['set'][] = $key;
        //echo 'SEEEET. '.$key;
        if($this->canSetProperty($key)){
            /*if(!isset($this->_tagMap)){
                $this->fill();
            }*/
            if(isset($this->_tagMap[$key])){
                if($value === ''){
                    $value = array();
                }
                $this->_tagMap[$key] = $value;
            }
        }
        else{
            parent::__set($key, $value);
        }
    }

    public function canGetProperty($name, $checkVars = true){
        /*if(isset($this->owner) && $this->owner->canGetProperty($name, $checkVars)){
            return true;
        }*/
        //$_POST['canGet'][] = $name;
        if(isset($this->_tagMap[$name])){
            return true;
        }
        return parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true){
        //$_POST['canSet'][] = $name;
        if(isset($this->_tagMap[$name])){
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    /*public function __get($key){
        //$tagMap_key = 'c_'.$key;
        $tagMap_key = $key;
        if(!isset($this->_tagMap)){
            $this->_tags = $this->owner->getTags()->all();
            if($this->_tags){
                foreach ($this->_tags as $key => $value) {
                    $this->_tagMap['c_'.$value->category_id][] = $value->title;
                }
            }
        }
        if(isset($this->_tagMap[$tagMap_key])){
            return $this->_tagMap[$tagMap_key];
        }
        return parent::__get($key);
    }*/

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /*public function afterSave(){
        $this->_tagMap->afterSave();
    }

    public function beforeDelete(){
        $this->_tagMap->beforeDelete();
    }*/

    /*public function getTag(){
        if(!isset($this->_tagMap)){
            $this->_tagMap = new TagMap();
            $tagsArr = array();
            $this->_tags = $this->owner->getTags()->all();
            if($this->_tags){
                foreach ($this->_tags as $key => $value) {
                    $tagsArr['c_'.$value->category_id][] = $value->title;
                }
                $this->_tagMap::fill($tagsArr, get_class($this->owner), $this->owner->primaryKey);
            }
        }
        return $this->_tagMap;
    }*/
    public function getTagForm($form){
        return Yii::$app->view->render('@app/modules/tag/behaviors/views/TagBehaviorForm', [
            'model' => $this->owner,
            'form' => $form,
        ]);
    }
    //отдаст массив объектов категорий
    public function getTagCategories(){
        if(!isset($this->_catMap)){
            $assignCats = TagCategoryAssign::find()->where(['class_name' => get_class($this->owner)])->all();
            $assignCatsIds = array();
            foreach ($assignCats as $key => $value) {
                $assignCatsIds[] = $value->category_id;
            }
            $tagCat = TagCategory::find()->where(['category_id' => $assignCatsIds])->indexBy('category_id')->all();
            $this->_catMap = $tagCat;
/*            print_r($tagCat);
            exit(0);*/
        }
        return $this->_catMap;
    }
    //отдаст массив метка категории => название категории
    public function getSlugTitleMap(){
        if(!isset($this->_slugTitleMap)){
            $tagCat = $this->getTagCategories();
            $slugMap = array();
            foreach ($tagCat as $key => $value) {
                $slugMap[$value->slug] = $value->title;
            }
            $this->_slugTitleMap = $slugMap;
        }
        return $this->_slugTitleMap;
    }
    //отдаст массив метка категории => название категории
    public function getSlugIdMap(){
        if(!isset($this->_slugIdMap)){
            $tagCat = $this->getTagCategories();
            $slugMap = array();
            foreach ($tagCat as $key => $value) {
                $slugMap[$value->slug] = $value->category_id;
            }
            $this->_slugIdMap = $slugMap;
        }
        return $this->_slugIdMap;
    }

    public function getCatSlug($cat_id){
        $this->tagCategories;
        if(isset($this->_catMap[$cat_id])){
            return $this->_catMap[$cat_id]->slug;
        }
        return null;
    }

    public function getCatTitle($cat_id){
        $this->tagCategories;
        if(isset($this->_catMap[$cat_id])){
            return $this->_catMap[$cat_id]->title;
        }
        return null;
    }

    public function getTagsets(){
        return $this->owner->hasMany(Tagset::className(), ['item_id' => $this->owner->primaryKey()[0]])->where(['class_name' => get_class($this->owner)]);
    }

    public function getTags(){
        return $this->owner->hasMany(Tag::className(), ['tag_id' => 'tag_id'])->via('tagsets');
    }

    public function afterSave(){
        //print_r($_POST);
        //print_r($this->_tagMap); 
        //exit(0);
        if(!$this->owner->isNewRecord) {
            $this->beforeDelete();
        }
        $cnt = 0;
        $tagsets = [];

        foreach ($this->_tagMap as $key => $value) {
            if($value){
                $cnt += count($value);
            }
        }
        //print_r($this->_tagMap);exit(0);
        if($cnt) {
            $modelClass = get_class($this->owner);
            $slugIdMap = $this->getSlugIdMap();
            foreach ($this->_tagMap as $cat_slug => $tags) {
                $_POST['cnt'][] = $tags;
                if(count($tags) > 0){
                    foreach ($tags as $name) {
                        if (!($tag = Tag::findOne(['title' => $name]))) {
                            $tag = new Tag(['title' => $name, 'category_id' => $slugIdMap[$cat_slug]]);
                        }
                        $tag->frequency++;
                        if ($tag->save()) {
                            $updatedTags[] = $tag;
                            $tagsets[] = [$modelClass, $this->owner->primaryKey, $tag->tag_id];
                        }
                    }
                }
            }

            if(count($tagsets)) {
                Yii::$app->db->createCommand()->batchInsert(Tagset::tableName(), ['class_name', 'item_id', 'tag_id'], $tagsets)->execute();
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
        //Tag::deleteAll(['frequency' => 0]);
        Tagset::deleteAll(['class_name' => get_class($this->owner), 'item_id' => $this->owner->primaryKey]);
    }
}

/*$map[
    'slug' => ['c1', 'c2', ],
    'tagNames' => ['t11, t12, t13', 't21, t22, t23'],
    'tags' => [['t11', 't12', 't13'], ['t21', 't22', 't23']],
]*/