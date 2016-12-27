<?php

namespace cms\menu\common\models;

class MenuNews extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::NEWS;
	}

	/**
	 * @inheritdoc
	 */
	public function isEnabled()
	{
		return class_exists('cms\news\backend\Module');
	}

	/**
	 * @inheritdoc
	 */
	public function createUrl()
	{
		return ['/news/news/index'];
	}

}
