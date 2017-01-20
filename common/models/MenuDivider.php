<?php

namespace cms\menu\common\models;

class MenuDivider extends Menu
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::SECTION;
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
	public function isNameNeeded()
	{
		return false;
	}

}
