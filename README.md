push-message-for-php5
=====================

PHP5 class for push message to Jeapie

For send message:

    $result = PushMessage::init()  
        ->setUser('userKey')            // required  
        ->setToken('tokenKey')          // required  
        ->setTitle('titleOfMessage')    // optional  
        ->setMessage('bodyOfMessage')   // required  
        ->setPriority(0)                // optional. can be -1, 0, 1  
        ->send();                       // return true or false

Also you can get result as  
`PushMessage::init()->getResult();`

If result return false you can get errors:  
`PushMessage::init()->getErrors();`
