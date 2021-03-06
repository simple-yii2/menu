<?php

use yii\helpers\Html;
use yii\web\JsExpression;

use dkhlystov\widgets\NestedTreeGrid;
use cms\menu\common\models\Menu;

$title = Yii::t('menu', 'Menus');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
	<?= Html::a(Yii::t('menu', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
</div>

<?= NestedTreeGrid::widget([
	'dataProvider' => $dataProvider,
	'showRoots' => true,
	'initialNode' => $initial,
	'moveAction' => ['move'],
	'tableOptions' => ['class' => 'table table-condensed'],
	'pluginOptions' => [
		'onMoveOver' => new JsExpression('function (item, helper, target, position) {
			return position != 1 || target.data("type") == 0;
		}'),
	],
	'rowOptions' => function ($model, $key, $index, $grid) {
		$options = ['data-type' => $model->type];

		if (!$model->active)
			Html::addCssClass($options, 'warning');

		return $options;
	},
	'columns' => [
		[
			'attribute' => 'name',
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				$value = Html::encode($model->name);
				if ($model->isRoot()) {
					$value .= ' ' . Html::tag('span', Html::encode($model->alias), ['class' => 'label label-primary']);
				} elseif ($model->type == Menu::LINK) {
					$value .= ' ' . Html::tag('span', Html::encode($model->url), ['class' => 'text-info']);
				} elseif ($model->type != Menu::SECTION) {
					$type = Html::tag('span', Html::encode($model->getTypeName()), ['class' => 'label label-default']);

					if ($model->type == Menu::DIVIDER) {
						$value = $type;
					} else {
						$value .= ' ' . $type;
					}
				}

				return $value;
			}
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'options' => ['style' => 'width: 75px;'],
			'template' => '{update} {delete} {create}',
			'buttons' => [
				'create' => function ($url, $model, $key) {
					$isSection = $model->type == Menu::SECTION;

					if (!($model->isRoot() || $isSection))
						return '';

					$title = Yii::t('menu', 'Create item');

					return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
						'title' => $title,
						'aria-label' => $title,
						'data-pjax' => 0,
					]);
				},
			],
			'urlCreator' => function ($action, $model, $key, $index) {
				if ($action == 'create' || !$model->isRoot())
					$action = 'item/' . $action;

				return [$action, 'id' => $model->id];
			},
		],
	],
]) ?>
