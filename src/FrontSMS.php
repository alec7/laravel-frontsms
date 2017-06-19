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

use NotificationChannels\Front\FrontException;

class FrontSMS
{

    /**
     * Parses a response from SMS Gateway
     *
     * @param String $response
     * @return Array
     */
    static protected function parseResponse($response)
    {
        $list = explode(', ', $response);

        $data = [];
        foreach ( $list as $iter ) {
            list($key, $value) = explode('=', $iter);
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * Escape a phone number
     *
     * @param String $number
     *
     * @return String
     */
    static protected function escapeNumber($number)
    {
        return is_null($number) ? $number : preg_replace('/[^A-Za-z0-9 ]/', '', $number);
    }

    /**
     * Send request
     *
     * @param \NotificationChannels $intance
     * @param Array $params
     *
     * @throws \NotificationChannels\Front\FrontException
     *
     * @return void
     */
    static public function sendRequest($instance, Array $params)
    {
        $params['serviceid'] = config('services.frontsms.serviceid');
        $params['fromid'] = config('services.frontsms.fromid');

        if (  empty($params['serviceid']) ) {
            throw new FrontException('Cannot send without credentials');
        }

        if ( empty($params['txt']) ) {
            throw new FrontException('Cannot send empty message');
        }

        if ( empty($params['phoneno']) ) {
            throw new FrontException('Invalid reciever number');
        }

        $params['phoneno'] = static::escapeNumber($params['phoneno']);
        if ( strlen($params['phoneno']) < 8 || strlen($params['phoneno']) > 10 ) {
            throw new FrontException('Invalid reciever number');
        }

        $endpoint = config('services.frontsms.endpoint', 'https://www.pling.as/psk/push.php');
        $url = $endpoint . '?' . http_build_query($params);

        $response = $instance->getClient()->get($url);
        $body = (string) $response->getBody();
        $data = static::parseResponse($body);

        if ( !isset($data['ErrorCode']) ) {
            throw new FrontException('Invalid response from gateway: ' . $body);
        }

        if ( $data['ErrorCode'] != 0 ) {
            throw new FrontException(sprintf('Gateway error: %s (%s)', $data['ID'], $data['ErrorCode']));
        }
    }

}
