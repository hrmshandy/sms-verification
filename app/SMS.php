<?php

namespace App;

use Twilio\Rest\Client;

class SMS
{
    protected $sid;
    protected $token;
    protected $from;
    protected $client;
    protected $user;
    protected $content;

    public function __construct($user) {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->from = config('services.twilio.number');
        $this->client = new Client($this->sid, $this->token);

        $this->user = $user;
    }

    public function content($text) {
        $this->content = $text;

        return $this;
    }

    public function send() {
        $message = null;
        try {
            // Use the client to do fun stuff like send text messages!
            $message = $this->client->messages->create(
            // the number you'd like to send the message to
                $this->user->phone(),
                array(
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+33756799124',
                    // the body of the text message you'd like to send
                    'body' => $this->content,
                    'messagingServiceSid' => 'MGdddde82387fb4d9a9690f0e0ba75b879'
                )
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $message;
    }
}
