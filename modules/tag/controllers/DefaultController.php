<?php

namespace app\modules\tag\controllers;

use yii\web\Controller;
use app\modules\tag\models\TagCategory;
use app\modules\tag\models\Tag;
/**
 * Default controller for the `tag` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
	// Пока что кок
    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }

    public function actionTagList($q = null, $tag_id = null, $cat_id) {
	    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    $out = ['results' => ['id' => '', 'text' => '']];
	    if (!is_null($q)) {
	        $query = new \yii\db\Query;
	        $query->select('tag_id as id, title AS text')
	            ->from(Tag::tableName())
	            ->where(['like', 'title', $q])
	            ->andWhere(['category_id' => $cat_id])
	            ->limit(20);
	        $command = $query->createCommand();
	        $data = $command->queryAll();
	        $out['results'] = array_values($data);
	    }
	    elseif ($id > 0) {
	        $out['results'] = ['id' => $id, 'text' => Tag::find($id)->title];
	    }
	    return $out;
	}
}
