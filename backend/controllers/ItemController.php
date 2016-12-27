<?php

namespace cms\menu\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use cms\menu\backend\models\ItemForm;
use cms\menu\common\models\Menu;

/**
 * Item manage controller
 */
class ItemController extends Controller
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
	 * Create
	 * @param integer $id 
	 * @return string
	 */
	public function actionCreate($id)
	{
		$parent = Menu::findOne($id);
		if ($parent === null)
			throw new BadRequestHttpException(Yii::t('menu', 'Item not found.'));

		$model = new ItemForm(new Menu);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save($parent)) {
			Yii::$app->session->setFlash('success', Yii::t('menu', 'Changes saved successfully.'));
			return $this->redirect([
				'menu/index',
				'id' => $model->getObject()->id,
			]);
		}

		return $this->render('create', [
			'model' => $model,
			'id' => $id,
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
		if ($object === null || $object->isRoot())
			throw new BadRequestHttpException(Yii::t('menu', 'Item not found.'));

		$model = new ItemForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('menu', 'Changes saved successfully.'));
			return $this->redirect([
				'menu/index',
				'id' => $model->getObject()->id,
			]);
		}

		return $this->render('update', [
			'model' => $model,
			'id' => $object->id,
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
		if ($object === null || $object->isRoot())
			throw new BadRequestHttpException(Yii::t('menu', 'Item not found.'));

		$sibling = $object->prev()->one();
		if ($sibling === null)
			$sibling = $object->next()->one();

		if ($object->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('menu', 'Item deleted successfully.'));

		return $this->redirect(['menu/index', 'id' => $sibling ? $sibling->id : null]);
	}

	/**
	 * Making alias list for specified menu item type
	 * @param integer $type Menu item type
	 * @return string
	 */
	public function actionAlias($type)
	{
		return Json::encode([
			'type' => (integer) $type,
			'items' => Menu::getAliasListByType($type),
		]);
	}

}
