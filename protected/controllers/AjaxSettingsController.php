<?php

class AjaxSettingsController extends CController
{

	public function __construct($id, $module=null) {

		if(Yii::app()->user->isGuest)
		{
			throw new CException(Yii::t('yii','Guests are not allowed to save or retrieve settings.'));
		}

		parent::__construct($id, $module);

	}

	/**
	 * @todo (mburtscher) Add description
	 */
	public function actionSet()
	{
		$name = $_POST['name'];
		$scope = (isset($_POST['scope']) ? $_POST['scope'] : null);
		$object = (isset($_POST['object']) ? $_POST['object'] : null);
		$value = $_POST['value'];
		Yii::app()->user->settings->set($name, $value, $scope, $object);
		Yii::app()->user->settings->saveSettings();
	}

	/**
	 * Add a value to a setting
	 */
	public function actionAdd()
	{

		$name = $_POST['name'];
		$scope = (isset($_POST['scope']) ? $_POST['scope'] : null);
		$object = (isset($_POST['object']) ? $_POST['object'] : null);
		$value = $_POST['value'];

		$oldValue = Yii::app()->user->settings->get($name, $scope, $object);

		if($oldValue && !is_array($oldValue))
		{
			$oldValue = (array)$oldValue;
		}

		$oldValue[] = $value;

		Yii::app()->user->settings->set($name, $oldValue, $scope, $object);
		Yii::app()->user->settings->saveSettings();

	}

}