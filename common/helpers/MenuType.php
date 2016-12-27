<?php

namespace cms\menu\common\helpers;

use Yii;

class MenuType
{

	const TYPE_SECTION = 0;
	const TYPE_LINK = 1;
	const TYPE_PAGE = 2;
	const TYPE_GALLERY = 3;
	const TYPE_CONTACTS = 4;
	const TYPE_NEWS = 5;
	const TYPE_REVIEW = 6;

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

			if ($className == 'cms\page\backend\Module')
				$typeList[self::TYPE_PAGE] = Yii::t('menu', 'Page');

			if ($className == 'cms\gallery\backend\Module')
				$typeList[self::TYPE_GALLERY] = Yii::t('menu', 'Gallery');

			if ($className == 'cms\contact\backend\Module')
				$typeList[self::TYPE_CONTACTS] = Yii::t('menu', 'Contacts');

			if ($className == 'cms\news\backend\Module')
				$typeList[self::TYPE_NEWS] = Yii::t('menu', 'News');

			if ($className == 'cms\review\backend\Module')
				$typeList[self::TYPE_REVIEW] = Yii::t('menu', 'Reviews');
		}

		return $typeList;
	}

	public static function getTypesWithUrl()
	{
		return [
			static::TYPE_LINK,
		];
	}

	public static function getTypesWithAlias()
	{
		return [
			static::TYPE_PAGE,
			static::TYPE_GALLERY,
		];
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

		if ($type == self::TYPE_GALLERY)
			return self::getGalleryAliasList();

		return [];
	}

	/**
	 * Make pages alias list
	 * @return array
	 */
	protected static function getPageAliasList()
	{
		$items = [];

		foreach (\cms\page\common\models\Page::find()->select(['alias', 'title'])->asArray()->all() as $row) {
			$items[$row['alias']] = $row['title'];
		}

		return $items;
	}

	/**
	 * Make gallery alias list
	 * @return array
	 */
	protected static function getGalleryAliasList()
	{
		$items = [];

		foreach (\cms\gallery\common\models\Gallery::find()->select(['alias', 'title'])->asArray()->all() as $row) {
			$items[$row['alias']] = $row['title'];
		}

		return $items;
	}

}
