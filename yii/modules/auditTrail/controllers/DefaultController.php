<?php

class DefaultController extends PpController
{
	public function actionIndex()
	{
		$this->render('index');
	}
}