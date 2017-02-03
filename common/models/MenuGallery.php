<?php

namespace cms\menu\common\models;

use cms\gallery\common\models\Gallery;

class MenuGallery extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::GALLERY;
	}

	/**
	 * @inheritdoc
	 */
	public function isEnabled()
	{
		return class_exists('cms\gallery\backend\Module');
	}

	/**
	 * @inheritdoc
	 */
	public function isAliasNeeded()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function getAliasList()
	{
		$items = [];
		$query = Gallery::find()
			->select(['alias', 'title'])
			->where(['type' => Gallery::TYPE_COLLECTION]);

		foreach ($query->asArray()->all() as $row) {
			$items[$row['alias']] = $row['title'];
		}

		return $items;
	}

	/**
	 * @inheritdoc
	 */
	public function createUrl()
	{
		return ['/gallery/gallery/index', 'alias' => $this->alias];
	}

}
