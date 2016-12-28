<?php

namespace cms\menu\common\models;

class MenuFeedback extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::FEEDBACK;
	}

	/**
	 * @inheritdoc
	 */
	public function isEnabled()
	{
		return class_exists('cms\feedback\backend\Module');
	}

	/**
	 * @inheritdoc
	 */
	public function createUrl()
	{
		return ['/feedback/feedback/index'];
	}

}
