<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

use mainmenu\backend\assets\MainmenuFormAsset;
use mainmenu\common\models\Mainmenu;

MainmenuFormAsset::register($this);

$typeOptions = [];
if ($model->item !== null && $model->item->children()->count() > 0)
	$typeOptions['disabled'] = true;

$urlOptions = [];
if ($model->type !== Mainmenu::LINK)
	$urlOptions['options'] = ['class' => 'form-group hidden'];

$aliasOptions = ['options' => [
	'data-url' => Url::toRoute('alias'),
	'class' => 'form-group',
]];
if ($model->type === Mainmenu::SECTION || $model->type === Mainmenu::LINK)
	Html::addCssClass($aliasOptions['options'], 'hidden');

?>
<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => ['class' => 'mainmenu-form'],
]); ?>

	<?= $form->field($model, 'active')->checkbox() ?>

	<?= $form->field($model, 'name') ?>

	<?= $form->field($model, 'type')->dropDownList(Mainmenu::getTypeList(), $typeOptions) ?>

	<?= $form->field($model, 'url', $urlOptions) ?>

	<?= $form->field($model, 'alias', $aliasOptions)->dropDownList(Mainmenu::getAliasList($model->type)) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('mainmenu', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('mainmenu', 'Cancel'), ['index', 'id' => $id], ['class' => 'btn btn-link']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
