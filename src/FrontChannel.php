<?php
/*!
 * laravel-frontsms
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * Anders Evenrud <andersevenrud@gmail.com>
 */

namespace NotificationChannels\Front;

use GuzzleHttp\Client;

use Illuminate\Notifications\Notification;

use NotificationChannels\Front\FrontException;
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
