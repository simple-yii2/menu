<?php

namespace cms\menu\common\models;

class MenuSection extends Menu
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

}
