<?php
/**
 * This class for send push message to Jeapie server.
 * For more informations go to http://jeapie.com
 *
 * @category PHP
 * @author Jeapie <jeapiecompany@gmail.com>
 * @link http://jeapie.com/start
 * @example example.php
 * @version 0.3
 * @license BSD License
 *
 * Example how to use:
 *
 * 1) Set params
 * PushMessage::init()
 *     ->setToken('tokenKey')           // require
 *     ->setTitle('titleOfMessage')     // not require
 *     ->setMessage('bodyOfMessage')    // require
 *     ->setPriority(0)                // not require. can be -1, 0, 1
 *
 * 2) Send
 * PushMessage::init()
 *     ->setDevice('htcsensation')      // not require. Using only for personalSend()
 *     ->personalSend();
 * 
 * PushMessage::init()
 *     ->setEmails(array(               // required. Using only for usersSend()
 *         'login@exmaple.com',
 *         'login@exmaple.com',
 *     ))
 *     ->usersSend();
 *
 * PushMessage::init()
 *     ->broadcastSend();
 *
 * If return false you can get errors:
 * PushMessage::init()->getErrors()
 *
 * Also you can get result as
 * PushMessage::init()->getResult()
 */
class PushMessage
{
    const PERSONAL_SEND_URL = "https://api.jeapie.com/v2/personal/send/message.json";
    const USERS_SEND_URL = "https://api.jeapie.com/v2/users/send/message.json";
    const BROADCAST_SEND_URL = "https://api.jeapie.com/v2/broadcast/send/message.json";

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

    private $_token,
        $_title = '',
        $_message,
        $_device = '',
        $_priority = 0,
        $_emails = array();

    private $_useSslVerification = true;


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
     * add array of emails for send group of users by email
     *
     * @param array $emails
     * @return $this
     */
    public function setEmails($emails)
    {
        $this->_emails = (array) $emails;

        return $this;
    }


    /**
     * get array of emails
     *
     * @return array
     */
    public function getEmails()
    {
        return implode(',', $this->_emails);
    }


    /**
     * add email for send group of users by email
     *
     * @param string $email
     * @return $this
     */
    public function addEmail($email)
    {
        array_push($this->_emails, (string) $email);

        return $this;
    }


    /**
     * remove email from array of emails
     *
     * @param string $email
     * @return $this
     */
    public function removeEmail($email)
    {
        if($key = array_search($email, $this->_emails))
        {
            unset($this->_emails[$key]);
        }

        return $this;
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



    /**
     * Disable ssl verification if have error:
     * "SSL certificate problem: unable to get local issuer certificate"
     */
    public function disableSslVerification()
    {
        $this->_useSslVerification = false;

        return $this;
    }


    /**
     * Enable ssl verification
     */
    public function enableSslVerification()
    {
        $this->_useSslVerification = true;

        return $this;
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
     * @param string $url - api url
     * @param array $params - params for curl
     * @return bool
     */
    private function _send($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (!$this->_useSslVerification)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

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


    /**
     * Check params and send personal message by method _send
     *
     * @see _send
     * @return bool
     */
    public function personalSend()
    {
        $message = $this->getMessage();

        if (!$this->_checkToken($this->getToken()) ||
            empty($message))
        {
            $this->_setErrors(array('Incorrect app tokens, or empty message!'));
            $this->_setResult(false);
            return false;
        }

        return $this->_send(self::PERSONAL_SEND_URL, array(
            'token'    => $this->getToken(),
            'title'    => $this->getTitle(),
            'message'  => $message,
            'device'   => $this->getDevice(),
            'priority' => $this->getPriority(),
        ));
    }


    /**
     * Check params and send message for group of users by method _send
     *
     * @see _send
     * @return bool
     */
    public function usersSend()
    {
        $message = $this->getMessage();
        $emails = $this->getEmails();

        if (!$this->_checkToken($this->getToken()) ||
            empty($emails) ||
            empty($message))
        {
            $this->_setErrors(array('Incorrect app tokens or empty emails, or empty message!'));
            $this->_setResult(false);
            return false;
        }

        return $this->_send(self::USERS_SEND_URL, array(
            'token'    => $this->getToken(),
            'emails'   => $emails,
            'title'    => $this->getTitle(),
            'message'  => $message,
            'device'   => $this->getDevice(),
            'priority' => $this->getPriority(),
        ));
    }


    /**
     * Check params and send message to all users who's subscribed on this Provider by method _send
     *
     * @see _send
     * @return bool
     */
    public function broadcastSend()
    {
        $message = $this->getMessage();

        if (!$this->_checkToken($this->getToken()) ||
            empty($message))
        {
            $this->_setErrors(array('Incorrect app tokens, or empty message!'));
            $this->_setResult(false);
            return false;
        }

        return $this->_send(self::BROADCAST_SEND_URL, array(
            'token'    => $this->getToken(),
            'title'    => $this->getTitle(),
            'message'  => $message,
            'device'   => $this->getDevice(),
            'priority' => $this->getPriority(),
        ));
    }

}
