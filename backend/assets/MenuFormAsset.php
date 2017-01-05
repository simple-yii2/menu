<?php

namespace cms\menu\backend\assets;

use yii\web\AssetBundle;

class MenuFormAsset extends AssetBundle
{

	public $js = [
		'menu-form.js',
	];
	
	public $depends = [
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/menu-form';
	}

}
