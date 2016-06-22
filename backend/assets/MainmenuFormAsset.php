<?php

namespace mainmenu\backend\assets;

use yii\web\AssetBundle;

class MainmenuFormAsset extends AssetBundle {

	public $js = [
		'mainmenu-form.js',
	];
	
	public $depends = [
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = dirname(__FILE__) . '/mainmenu-form';
	}

}
