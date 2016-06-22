<?php

namespace mainmenu\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;

use mainmenu\backend\models\MainmenuForm;
use mainmenu\common\models\Mainmenu;

/**
 * Main menu manage controller
 */
class MainmenuController extends Controller
{

	/**
	 * Access control
	 * @return array
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'roles' => ['mainmenu']],
				],
			],
		];
	}

	/**
	 * Main menu tree
	 * @param integer|null $id Initial item id
	 * @return void
	 */
	public function actionIndex($id = null)
	{
		$initial = Mainmenu::findOne($id);

		$dataProvider = new ActiveDataProvider([
			'query' => Mainmenu::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'initial' => $initial,
		]);
	}

	/**
	 * Main menu item creating
	 * @param integer|null $id Parent item id
	 * @return void
	 */
	public function actionCreate($id = null)
	{
		$model = new MainmenuForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->create($id)) {
			Yii::$app->session->setFlash('success', Yii::t('mainmenu', 'Changes saved successfully.'));
			return $this->redirect([
				'index',
				'id' => $model->item->id,
			]);
		}

		return $this->render('create', [
			'model' => $model,
			'id' => $id,
		]);
	}

	/**
	 * Main menu item updating
	 * @param integer $id Menu item id
	 * @return void
	 */
	public function actionUpdate($id)
	{
		$item = Mainmenu::findOne($id);
		if ($item === null)
			throw new BadRequestHttpException(Yii::t('mainmenu', 'Menu item not found.'));

		$model = new MainmenuForm(['item' => $item]);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->update()) {
			Yii::$app->session->setFlash('success', Yii::t('mainmenu', 'Changes saved successfully.'));
			return $this->redirect([
				'index',
				'id' => $model->item->id,
			]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Main menu item deleting
	 * @param integer $id Menu item id
	 * @return void
	 */
	public function actionDelete($id)
	{
		$item = Mainmenu::findOne($id);
		if ($item === null)
			throw new BadRequestHttpException(Yii::t('mainmenu', 'Menu item not found.'));

		if ($item->delete())
			Yii::$app->session->setFlash('success', Yii::t('mainmenu', 'Menu item deleted successfully.'));

		return $this->redirect(['index']);
	}

	public function actionMove($id, $target, $position)
	{
		$item = Mainmenu::findOne($id);
		if ($item === null)
			throw new BadRequestHttpException(Yii::t('mainmenu', 'Menu item not found.'));

		$t = Mainmenu::findOne($target);
		if ($t === null)
			throw new BadRequestHttpException(Yii::t('mainmenu', 'Menu item not found.'));

		switch ($position) {
			case 0:
				$item->insertBefore($t);
				break;

			case 1:
				$item->appendTo($t);
				break;
			
			case 2:
				$item->insertAfter($t);
				break;
		}
	}

	/**
	 * Making alias list for specified menu item type
	 * @param integer $type Menu item type
	 * @return void
	 */
	public function actionAlias($type)
	{
		return Json::encode([
			'type' => (integer) $type,
			'items' => Mainmenu::getAliasList($type),
		]);
	}

}
