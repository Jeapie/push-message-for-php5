push-message-for-php5
=====================

PHP5 class for push message to This is [an example](http://example.com/ "Title") inline link. Jeapie

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

If you are not familiar with Jeapie - visit http://jeapie.com
