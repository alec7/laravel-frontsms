<?php
/*!
 * laravel-frontsms
 * Anders Evenrud <andersevenrud@gmail.com>
 */

namespace NotificationChannels\Front;

use GuzzleHttp\Client;

use Illuminate\Notifications\Notification;

use NotificationChannels\Front\Exceptions\FrontException;
use NotificationChannels\Front\FrontSMS;

class FrontChannel
{
    protected $client;

    /**
     * @param GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Sends SMS via the Notifyable Laravel interfaces
     *
     * @param Object $notifiable
     * @param Illuminate\Notifications\Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $params = $notification->toFront($notifiable)->toArray();

        FrontSMS::sendRequest($this, [
            'txt' => $params['txt'],
            'phoneno' => $params['phoneno']
        ]);
    }

    /**
     * Sends a raw message without Notifyable
     *
     * @param String $to
     * @param String $message
     */
    static public function sendRaw($to, $message)
    {
        $instance = new static(new Client());

        FrontSMS::sendRequest($instance, [
            'phoneno' => $to,
            'txt' => $message
        ]);
    }

    /**
     * @return GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
