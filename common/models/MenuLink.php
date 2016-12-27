<?php

namespace cms\menu\common\models;

class MenuLink extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::LINK;
	}

	/**
	 * @inheritdoc
	 */
	public function isEnabled()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function isUrlNeeded()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function createUrl()
	{
		return $this->url;
	}

}
