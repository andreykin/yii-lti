<?php

/**
 * LTILoginer class.
 * LTILoginer is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LTILoginer extends CFormModel
{
    public $consumer_key;
    public $user_id;
    
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// required fields
			array('consumer_key, user_id', 'required'),
		    // rememberMe needs to be a boolean
		    array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		    array('username', 'safe'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new LTIUserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password',$this->_identity->errorCode);
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new LTIUserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
	
	public function getUsername() {
	    return $this->consumer_key.'_'.$this->user_id;
	}
}
