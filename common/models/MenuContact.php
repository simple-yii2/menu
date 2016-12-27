<?php

namespace cms\menu\common\models;

class MenuContact extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::CONTACT;
	}

	/**
	 * @inheritdoc
	 */
	public function isEnabled()
	{
		return class_exists('cms\contact\backend\Module');
	}

	/**
	 * @inheritdoc
	 */
	public function createUrl()
	{
		return ['/contact/contact/index'];
	}

}
