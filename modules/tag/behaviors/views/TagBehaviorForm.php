<?php
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
foreach ($model->tagCategories as $cat_id => $cat) {
/*			echo $cat_id;
	echo $model->getCatSlug($cat_id);exit(0);*/
	//print_r($cat->slug);
	echo $form->field($model, $cat->slug)->widget(Select2::classname(), [
	    'options' => ['placeholder' => 'Тэги категории '.$model->getCatTitle($cat_id), 'multiple' => true],
	    'pluginOptions' => [
	        'tags' => true,
	        'multiple' => true,
	        'tokenSeparators' => [','],
	        'maximumInputLength' => 20,
	        'language' => [
	            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
	        ],
	        'ajax' => [
	            'url' => Url::to(['/tag/default/tag-list', 'cat_id' => $cat_id]),
	            'dataType' => 'json',
	            'data' => new JsExpression('function(params) { return {q:params.term}; }')
	        ],
	        //'allowClear' => true,
			'minimumInputLength' => 1,
			'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
	        'templateResult' => new JsExpression('function(tags) { tags.id=tags.text; return tags.text; }'),
	        'templateSelection' => new JsExpression('function (tags) {return tags.text; }'),
	        'createTag' => new JsExpression('function (params) {term = params.term; return {id:term, text:term}; }'),
	        //'insertTag' => new JsExpression('function (data, tag) { console.log(tag);tag.id = tag.text; data.push(tag); }'),
	    ],
	])->label($model->getCatTitle($cat_id));
}