<?php

namespace menu\common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Main menu active record
 */
class Menu extends ActiveRecord {

	/**
	 * Menu item types
	 */
	const TYPE_SECTION = 0;
	const TYPE_LINK = 1;
	const TYPE_PAGE = 2;

	/**
	 * Making available type list
	 * @return array
	 */
	public static function getTypeList()
	{
		$typeList = [
			self::TYPE_SECTION => Yii::t('menu', 'Section'),
			self::TYPE_LINK => Yii::t('menu', 'Link'),
		];

		foreach (Yii::$app->controller->module->module->getModules(false) as $module) {
			if (is_string($module)) {
				$className = $module;
			} elseif (is_array($module)) {
				$className = $module['class'];
			} else {
				$className = $module::className();
			}

			if ($className == 'page\backend\Module')
				$typeList[self::TYPE_PAGE] = Yii::t('menu', 'Page');
		}

		return $typeList;
	}

	/**
	 * Make alias list for specifid type
	 * @param integer $type Menu item type
	 * @return array
	 */
	public static function getAliasList($type)
	{
		if ($type == self::TYPE_PAGE)
			return self::getPageAliasList();

		return [];
	}

	/**
	 * Make pages alias list
	 * @return array
	 */
	protected static function getPageAliasList()
	{
		$items = [];

		foreach (\page\common\models\Page::find()->select(['alias', 'title'])->asArray()->all() as $row) {
			$items[$row['alias']] = $row['title'];
		}

		return $items;
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Menu';
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('menu', 'Name'),
			'active' => Yii::t('menu', 'Active'),
			'type' => Yii::t('menu', 'Type'),
			'url' => Yii::t('menu', 'Url'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'tree' => [
				'class' => NestedSetsBehavior::className(),
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function find()
	{
		return new MenuQuery(get_called_class());
	}

}

/**
 * Main menu active query
 */
class MenuQuery extends ActiveQuery
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			NestedSetsQueryBehavior::className(),
		];
	}

}
