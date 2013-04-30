<?php
/**
 * @author Jeapie <jeapiecompany@gmail.com>
 * @version 0.1
 * */

require 'PushMessage.php';

PushMessage::init()
    ->setUser('user token goes here')
    ->setToken('app token goes here')
    ->setTitle('Hello')
    ->setMessage('World')
    ->setDevice('you device name')
    ->setPriority(0)
    ->send();

if (!PushMessage::init()->getResult()) {
	print_r(PushMessage::init()->getErrors());
} else {
	echo 'the message was sent';
}


// after the first initialization, you can immediately send messages
PushMessage::init()->send();

if (!PushMessage::init()->getResult()) {
	print_r(PushMessage::init()->getErrors());
} else {
	echo 'the message was sent';
}