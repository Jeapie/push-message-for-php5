push-message-for-php5
=====================

PHP5 class for push message to Jeapie

For send message:

    $result = PushMessage::init()  
        ->setUser('userKey')            // require  
        ->setToken('tokenKey')          // require  
        ->setTitle('titleOfMessage')    // not require  
        ->setMessage('bodyOfMessage')   // require  
        ->setPriority(0)                // not require. can be -1, 0, 1  
        ->send();                       // return true or false

Also you can get result as  
`PushMessage::init()->getResult();`

If result return false you can get errors:  
`PushMessage::init()->getErrors();`
