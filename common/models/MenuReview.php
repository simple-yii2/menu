<?php

namespace cms\menu\common\models;

class MenuReview extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::REVIEW;
	}

	/**
	 * @inheritdoc
	 */
	public function isEnabled()
	{
		return class_exists('cms\review\backend\Module');
	}

	/**
	 * @inheritdoc
	 */
	public function createUrl()
	{
		return ['/review/review/index'];
	}

}
