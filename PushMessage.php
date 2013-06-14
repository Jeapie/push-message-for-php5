<?php
/**
 * This class for send push message to Jeapie server.
 * For more informations go to http://jeapie.com
 *
 * @category PHP
 * @author Jeapie <jeapiecompany@gmail.com>
 * @link http://jeapie.com/start
 * @example example.php
 * @version 0.1
 * @license BSD License
 *
 * Example how to use
 *
 * $result = PushMessage::init()
 *     ->setUser('userKey')            // require
 *     ->setToken('tokenKey')          // require
 *     ->setTitle('titleOfMessage')    // not require
 *     ->setMessage('bodyOfMessage')   // require
 *     ->setDevice('htcsensation')     // not require
 *     ->setPriority(0)                // not require. can be -1, 0, 1
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
    const API_URL = 'https://api.jeapie.com/v1/send/message.json';

    /**
     * @var array
     * */
    private $_errors = array();

    /**
     * @var bool
     * */
    private $_result = false;

    /**
     * @var object
     */
    private static $_instance;

    private $_user,
        $_token,
        $_title = '',
        $_message,
        $_device = '',
        $_priority = 0;

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
     * Set api user key. User key can take from http://dashboard.jeapie.com
     *
     * @param string $user - user key (must be 32 symbols)
     * @return $this
     */
    public function setUser($user)
    {
        $this->_user = (string)$user;

        return $this;
    }


    /**
     * Get api user key
     *
     * @return string - user key
     */
    public function getUser()
    {
        return $this->_user;
    }


    /**
     * Set api token. Token can take from http://dashboard.jeapie.com/applications
     *
     * @param string $token - token (must be 32 symbols)
     * @return $this
     */
    public function setToken($token)
    {
        $this->_token = (string)$token;

        return $this;
    }


    /**
     * Get api token
     *
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
        $this->_title = (string)$title;

        return $this;
    }


    /**
     * Get title of message
     *
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
        $this->_message = (string)$message;

        return $this;
    }


    /**
     * Get body of message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Set device
     *
     * @param string $device - device name
     * @return $this
     */
    public function setDevice($device)
    {
        $this->_device = (string)$device;

        return $this;
    }


    /**
     * Get device name
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->_device;
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
        $this->_priority = (int)$priority;

        return $this;
    }


    /**
     * Get message's priority
     *
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
        $this->_errors = (array)$errors;

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
        $this->_result = (bool)$result;

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


    private function _checkToken($token = '')
    {
        if (empty($token) || strlen($token) != 32)
            return false;
        else
            return true;
    }


    /**
     * Send message to server and get result of operations.
     *
     * @return bool
     */
    public function send()
    {
        $message = $this->getMessage();

        if (!$this->_checkToken($this->getUser()) ||
            !$this->_checkToken($this->getToken()) ||
            empty($message))
        {
            $this->_setErrors(array('Incorrect user or app tokens, or empty message!'));
            $this->_setResult(false);
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'user'     => $this->getUser(),
            'token'    => $this->getToken(),
            'title'    => $this->getTitle(),
            'message'  => $this->getMessage(),
            'device'   => $this->getDevice(),
            'priority' => $this->getPriority(),
        ));

        if (!$response = curl_exec($ch))
        {
            $result = array(
                'success' => false,
                'errors'  => curl_error($ch),
            );
        }

        curl_close($ch);

        if (empty($result))
        {
            try {
                $result = json_decode($response, true);
            } catch (Exception $e) {
                $result = array(
                    'success' => false,
                    'errors'  => $e->getMessage(),
                );
            }
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
