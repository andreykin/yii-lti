<?php

/**
 * LTIUserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class LTIUserIdentity extends CUserIdentity
{
    private $_id;
 
    public function authenticate()
    {
        $username= $this->username;
        $user=User::model()->find('username=? AND auth_service_name="lti"',array($username));
        if($user===null) {
            // create new User
            $user = new User;
            $user->username = $this->username;
            $user->lti_user_id = $this->username;
            $user->auth_service_name = 'lti';
            if (!$user->save(false)) {
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            }
            $this->_id=$user->id;
            $this->username=$user->username;
            $this->errorCode=self::ERROR_NONE;
        }
        else
        {
            $this->_id=$user->id;
            $this->username=$user->username;
            $this->errorCode=self::ERROR_NONE;
        }
        return $this->errorCode==self::ERROR_NONE;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}