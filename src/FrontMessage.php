<?php
/*!
 * laravel-frontsms
 * Anders Evenrud <andersevenrud@gmail.com>
 */

namespace NotificationChannels\Front;

class FrontMessage
{
    protected $to;
    protected $message;

    /**
     * @param String $to
     * @param String $message
     */
    static public function create($to, $message)
    {
        return new static($to, $message);
    }

    /**
     * @param String $to
     * @param String $message
     */
    public function __construct($to, $message)
    {
        $this->to = $to;
        $this->message = $message;
    }

    /**
     * @return Array
     */
    public function toArray()
    {
        return [
            'phoneno' => $this->to,
            'txt' => $this->message
        ];
    }
}
