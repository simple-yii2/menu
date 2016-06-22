<?php

use yii\helpers\Html;

$title = $model->item->name;

$this->title = $title . '|' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('mainmenu', 'Main menu'), 'url' => ['index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('_form', [
	'model' => $model,
	'id' => $model->item->id,
]) ?>
