<?php
/**
 * This class for send push message to Jeapie server.
 * For more informations go to http://jeapie.com
 *
 * @category PHP
 * @author Jeapie
 * @email jeapiecompany@gmail.com
 * @link https://github.com/Jeapie/push-message-for-php5.1
 *
 * Example how to use
 *
 * $result = PushMessage::init()
 *     ->setUser('userKey')            // require
 * 	   ->setToken('tokenKey')          // require
 *     ->setTitle('titleOfMessage')    // not require
 *     ->setMessage('bodyOfMessage')   // require
 * 	   ->setPriority(0)                // not require. can be -1, 0, 1
 *     ->send();                       // return true or false
 *
 * If return false you can get errors:
 * PushMessage::init()->getErrors()
 *
 * Also you can get result as
 * PushMessage::init()->getResult()
 */

class PushMessage
{
    private $_errors, $_result = array();

    /**
     * @var _instance
     */
    private static $_instance;

    private $_user,
        $_token,
        $_title,
        $_message,
        $_priority;

    private function __clone() {}

    private function __wakeup() {}

    private function __construct() {}


    /**
     * It's singleton
     *
     * @return object PushMessage
     */
    public static function init()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new PushMessage();
        }

        return self::$_instance;
    }


    /**
     * User key can take from http://dashboard.jeapie.com
     *
     * @param string $user - user key (must be 32 symbols)
     * @return $this
     */
    public function setUser($user)
    {
        $this->_user = $user;

        return $this;
    }


    /**
     * @return string - user key
     */
    public function getUser()
    {
        return $this->_user;
    }


    /**
     * Token can take from http://dashboard.jeapie.com/applications
     *
     * @param string $token - token (must be 32 symbols)
     * @return $this
     */
    public function setToken($token)
    {
        $this->_token = $token;

        return $this;
    }


    /**
     * @return string - token
     */
    public function getToken()
    {
        return $this->_token;
    }


    /**
     * Set title of message
     *
     * @param string $title - title of message (max 255 symbols)
     * @return $this
     */
    public function setTitle($title = '')
    {
        $this->_title = $title;

        return $this;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }


    /**
     * Set body of message
     *
     * @param string $message - body of message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->_message = $message;

        return $this;
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }


    /**
     * Set message's priority. Can be -1, 0, 1
     * More information on http://jeapie.com
     *
     * @param int $priority
     * @return $this
     */
    public function setPriority($priority = 0)
    {
        $this->_priority = $priority;

        return $this;
    }


    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->_priority;
    }


    /**
     * @param array $errors
     * @return $this
     */
    private function _setErrors($errors)
    {
    	$this->_errors = $errors;

    	return $this;
    }


    /**
     * Get array of errors if result = false
     *
     * @return array _errors
     */
    public function getErrors()
    {
        return $this->_errors;
    }


    /**
     * @param bool $result
     * @return $this
     */
    private function _setResult($result)
    {
    	$this->_result = $result;

    	return $this;
    }


    /**
     * Get result
     *
     * @return bool
     * */
    public function getResult()
    {
    	return $this->_result;
    }


    /**
     * Send message to server and get result of operations.
     *
     * @return bool
     */
    public function send()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.jeapie.com/v1/send/message.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'user' => $this->_user,
            'token' => $this->_token,
            'title' => $this->_title,
            'message' => $this->_message,
            'priority' => $this->_priority,
        ));
		$response = curl_exec($ch);
		curl_close($ch);

        try {
        	$result = json_decode($response, true);
        } catch (Exception $e) {
        	$result = array(
        		'success' => false,
        		'errors'  => 'Sorry, some errors. Please contact http://jeapie.com',
        	);
        }

        if (isset($result['success']) && $result['success'] == true) {
        	$this->_setResult(true);
        	$this->_setErrors(array());
        } else {
        	$this->_setResult(false);
            $this->_setErrors( isset($result['errors']) ? $result['errors'] : 'Sorry, some errors. Please contact http://jeapie.com' );
        }

        return $this->getResult();
    }

}