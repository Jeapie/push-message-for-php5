push-message-for-php5
=====================

PHP5 class for push message to [Jeapie](http://jeapie.com/ "Jeapie")

For send message:

    1) Set params
    PushMessage::init()
        ->setToken('tokenKey')           // required
        ->setTitle('titleOfMessage')     // optional
        ->setMessage('bodyOfMessage')    // required
        ->setPriority(0)                // optional. can be -1, 0, 1

    2) Send
    PushMessage::init()
        ->setDevice('htcsensation')      // not require. Using only for personalSend()
        ->personalSend();

    PushMessage::init()
        ->setEmails(array(               // required. Using only for usersSend()
            'login@exmaple.com',
            'login@exmaple.com',
        ))
        ->usersSend();

    PushMessage::init()
        ->broadcastSend();                     // return true or false

Also you can get result as
`PushMessage::init()->getResult();`

If result return false you can get errors:
`PushMessage::init()->getErrors();`

If you have error **"SSL certificate problem: unable to get local issuer certificate"** on your local server
please use the method disableSslVerification().

If you are not familiar with Jeapie - visit http://jeapie.com


## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
