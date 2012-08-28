<?php

/**
 * OAuth 2.0 Endpoint Controller
 * 
 * @author Anton Menkveld <anton@parallelsoftware.com.au>
 *
 */
class DefaultController extends \parallel\yii\Controller
{	
	/**
	 * Authorisation Endpoint
	 */
	public function actionAuth() {
		echo "auth Endpoint";
	}
	
	/**
	 * Token Endpoint
	 */
	public function actionToken() {
		echo "token Endpoint";
	}	
}	