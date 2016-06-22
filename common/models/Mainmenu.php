<?php

namespace mainmenu\common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Main menu active record
 */
class Mainmenu extends ActiveRecord {

	/**
	 * Menu item types
	 */
	const SECTION = 0;
	const LINK = 1;
	const PAGE = 2;

	/**
	 * Making available type list
	 * @return array
	 */
	public static function getTypeList()
	{
		$typeList = [
			self::SECTION => Yii::t('mainmenu', 'Section'),
			self::LINK => Yii::t('mainmenu', 'Link'),
		];

		foreach (Yii::$app->getModules(false) as $module) {
			if (is_string($module)) {
				$className = $module;
			} elseif (is_array($module)) {
				$className = $module['class'];
			} else {
				$className = $module::className();
			}

			if ($className == 'page\backend\Module')
				$typeList[self::PAGE] = Yii::t('mainmenu', 'Page');
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
		if ($type == self::PAGE)
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
	public static function tableName() {
		return 'Mainmenu';
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'name' => Yii::t('mainmenu', 'Name'),
			'active' => Yii::t('mainmenu', 'Active'),
			'type' => Yii::t('mainmenu', 'Type'),
			'url' => Yii::t('mainmenu', 'Url'),
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
		return new MainmenuQuery(get_called_class());
	}

}

/**
 * Main menu active query
 */
class MainmenuQuery extends ActiveQuery
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
