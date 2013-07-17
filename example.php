<?php
/**
 * @author Jeapie <jeapiecompany@gmail.com>
 * @version 0.3
 * */

require 'PushMessage.php';

PushMessage::init()
    ->setToken('app token goes here')
    ->setTitle('Hello')
    ->setMessage('World')
    ->setPriority(0)
    ->personaSend();

if (!PushMessage::init()->getResult()) {
	print_r(PushMessage::init()->getErrors());
} else {
	echo 'the message was sent';
}


// after the first initialization, you can immediately send messages
PushMessage::init()->personaSend();

//for group of users by email
PushMessage::init()
    ->setEmails(array('login@example.com', 'login2@example.com'))
    ->usersSend();

//can add or remove email
PushMessage::init()
    ->addEmail('login3@example.com')
    ->removeEmail('login2@example.com')
    ->usersSend();

// or send all users message
PushMessage::init()->broadcastSend();

// get result
if (!PushMessage::init()->getResult()) {
    //show errors
	print_r(PushMessage::init()->getErrors());
} else {
	echo 'the message was sent';
}