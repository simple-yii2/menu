<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

use menu\backend\assets\MenuFormAsset;
use menu\common\models\Menu;

MenuFormAsset::register($this);

$typeOptions = [];
if ($model->item !== null && $model->item->children()->count() > 0)
	$typeOptions['disabled'] = true;

$urlOptions = [];
if ($model->type !== Menu::LINK)
	$urlOptions['options'] = ['class' => 'form-group hidden'];

$aliasOptions = ['options' => [
	'data-url' => Url::toRoute('alias'),
	'class' => 'form-group',
]];
if ($model->type === Menu::SECTION || $model->type === Menu::LINK)
	Html::addCssClass($aliasOptions['options'], 'hidden');

?>
<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => ['class' => 'menu-form'],
]); ?>

	<?= $form->field($model, 'active')->checkbox() ?>

	<?= $form->field($model, 'name') ?>

	<?= $form->field($model, 'type')->dropDownList(Menu::getTypeList(), $typeOptions) ?>

	<?= $form->field($model, 'url', $urlOptions) ?>

	<?= $form->field($model, 'alias', $aliasOptions)->dropDownList(Menu::getAliasList($model->type)) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('menu', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('menu', 'Cancel'), ['index', 'id' => $id], ['class' => 'btn btn-link']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
