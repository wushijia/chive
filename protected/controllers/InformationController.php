<?php

class InformationController extends Controller
{

	public function __construct($id, $module = null)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$this->layout = false;
		}

		parent::__construct($id, $module);
	}

	/**
	 * Shows all currently running processes on the MySQL server.
	 */
	public function actionProcesses()
	{
		Yii::app()->getDb()->setActive(true);
		$cmd = Yii::app()->getDb()->createCommand('SHOW PROCESSLIST');
		$processes = $cmd->queryAll();

		$this->render('processes', array(
			'processes' => $processes,
		));
	}

	/**
	 * Kills a process on the server.
	 */
	public function actionKillProcess()
	{
		$ids = json_decode(Yii::app()->getRequest()->getParam('ids'));

		$response = new AjaxResponse();
		$response->reload = true;

		foreach($ids AS $id)
		{
			$sql = 'KILL ' . $id;

			try
			{
				Yii::app()->getDb()->setActive(true);
				$cmd = Yii::app()->getDb()->createCommand($sql);

				$cmd->prepare();
				$cmd->execute();

				$response->addNotification('success', Yii::t('message', 'successKillProcess', array('{id}' => $id)), null, $sql);
			}
			catch(CDbException $ex)
			{
				$ex = new DbException($cmd);
				$response->addNotification('error', Yii::t('message', 'errorKillProcess', array('{id}' => $id)), $ex->getText(), $sql);
			}

		}

		$response->send();
	}

	/**
	 * Shows all installed storage engines.
	 */
	public function actionStorageEngines()
	{
		$cmd = Yii::app()->getDb()->createCommand('SHOW STORAGE ENGINES');
		$engines = $cmd->queryAll();

		$this->render('storageEngines', array(
			'engines' => $engines,
		));
	}

	/**
	 * Shows all installed character sets.
	 */
	public function actionCharacterSets()
	{
		$cmd = Yii::app()->getDb()->createCommand('SHOW CHARACTER SET');
		$charactersets = $cmd->queryAll();

		$charsets = array();
		foreach($charactersets AS $set)
		{
			$charsets[$set['Charset']] = $set;
		}

		// Fetch collations into charsets
		$cmd = Yii::app()->getDb()->createCommand('SHOW COLLATION');
		$collations = $cmd->queryAll();

		foreach($collations AS $collation)
		{
			$charsets[$collation['Charset']]['collations'][] = $collation;
		}

		$this->render('characterSets', array(
			'charsets' => $charsets,
		));
	}

	/**
	 * Shows all server variables.
	 */
	public function actionVariables()
	{
		$cmd = Yii::app()->getDb()->createCommand('SHOW GLOBAL VARIABLES');
		$data = $cmd->queryAll();

		$variables = array();
		foreach($data AS $entry)
		{
			$prefix = substr($entry['Variable_name'], 0, strpos($entry['Variable_name'], '_'));
			$variables[$prefix][$entry['Variable_name']] = $entry['Value'];
		}

		$this->render('variables', array(
			'variables' => $variables,
		));
	}

	/**
	 * Shows current server status.
	 */
	public function actionStatus()
	{
		$cmd = Yii::app()->getDb()->createCommand('SHOW GLOBAL STATUS');
		$data = $cmd->queryAll();

		$status = array();
		foreach($data AS $entry)
		{
			$prefix = substr($entry['Variable_name'], 0, strpos($entry['Variable_name'], '_'));
			$status[$prefix][$entry['Variable_name']] = $entry['Value'];
		}

		$this->render('status', array(
			'status' => $status,
		));
	}

}