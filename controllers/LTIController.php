<?php

class LTIController extends CExtController
{
    /**
     * Entry point for LTI Application
     */
    public function actionConnect()
    {
        $tool = Yii::app()->lti->tool;
        
        $tool->setParameterConstraint('oauth_consumer_key', TRUE, 50);
        $tool->setParameterConstraint('resource_link_id', TRUE, 50, array('basic-lti-launch-request'));
        $tool->setParameterConstraint('user_id', TRUE, 50, array('basic-lti-launch-request'));
        $tool->setParameterConstraint('roles', TRUE, NULL, array('basic-lti-launch-request'));
        $tool->controller = $this;
        
        $tool->handle_request();
    }

	/**
	 * Demo action for create new customer
	 */
	public function actionCreateConsumer()
	{
	    // Initialise tool consumer record if it does not already exist
	    Yii::app()->lti->createConsumer('moodlekey');
	}
}