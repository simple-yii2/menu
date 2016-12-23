<?php

namespace cms\menu\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use cms\menu\backend\models\MenuForm;
use cms\menu\common\models\Menu;

/**
 * Menu manage controller
 */
class MenuController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'roles' => ['Menu']],
				],
			],
		];
	}

	/**
	 * Tree
	 * @param integer|null $id Initial item id
	 * @return string
	 */
	public function actionIndex($id = null)
	{
		$initial = Menu::findOne($id);

		$dataProvider = new ActiveDataProvider([
			'query' => Menu::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'initial' => $initial,
		]);
	}

	/**
	 * Create
	 * @return string
	 */
	public function actionCreate()
	{
		$model = new MenuForm(new Menu);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('menu', 'Changes saved successfully.'));
			return $this->redirect([
				'index',
			]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Update
	 * @param integer $id
	 * @return string
	 */
	public function actionUpdate($id)
	{
		$object = Menu::findOne($id);
		if ($object === null || !$object->isRoot())
			throw new BadRequestHttpException(Yii::t('menu', 'Menu not found.'));

		$model = new MenuForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('menu', 'Changes saved successfully.'));
			return $this->redirect([
				'index',
			]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Delete
	 * @param integer $id
	 * @return string
	 */
	public function actionDelete($id)
	{
		$object = Menu::findOne($id);
		if ($object === null || !$object->isRoot())
			throw new BadRequestHttpException(Yii::t('menu', 'Menu not found.'));

		if ($object->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('menu', 'Menu deleted successfully.'));

		return $this->redirect(['index']);
	}

	/**
	 * Move
	 * @param integer $id 
	 * @param integer $target 
	 * @param integer $position 
	 * @return void
	 */
	public function actionMove($id, $target, $position)
	{
		$object = Menu::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('menu', 'Item not found.'));
		$oIsRoot = $object->isRoot();

		$t = Menu::findOne($target);
		if ($t === null)
			throw new BadRequestHttpException(Yii::t('menu', 'Item not found.'));
		$tIsRoot = $t->isRoot();

		switch ($position) {
			case 0:
				if (!($oIsRoot || $tIsRoot))
					$object->insertBefore($t);
				break;

			case 1:
				if (!$oIsRoot)
					$object->appendTo($t);
				break;
			
			case 2:
				if (!($oIsRoot || $tIsRoot))
					$object->insertAfter($t);
				break;
		}
	}

}
