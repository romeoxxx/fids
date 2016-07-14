<?php

namespace pimax\Messages;


/**
 * Class Message
 *
 * @package pimax\Messages
 */
class Message
{
    /**
     * @var integer|null
     */
    protected $recipient = null;

    /**
     * @var string
     */
    protected $text = null;

    /**
     * Message constructor.
     *
     * @param $recipient
     * @param $text
     */
    public function __construct($recipient, $text)
    {
        $this->recipient = $recipient;
        $this->text = $text;

    }

    /**
     * Get message data
     *
     * @return array
     */
    public function getData()
    {
        return [
            'recipient' =>  [
                'id' => $this->recipient
            ],
            'message' => [
                'text' => $this->text
            ]
        ];
    }
    public function getTo()
    {
        return $this->recipient;
    }
    public function getMsg()
    {
        return $this->text;
    }
}