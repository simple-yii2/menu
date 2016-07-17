<?php

use yii\helpers\Html;

$title = Yii::t('menu', 'Create menu item');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('menu', 'Main menu'), 'url' => ['index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('_form', [
	'model' => $model,
	'id' => $id,
]) ?>
