<?php
/*!
 * laravel-frontsms
 * Anders Evenrud <andersevenrud@gmail.com>
 */

namespace NotificationChannels\Front;

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
     * @throws \NotificationChannels\Front\Exceptions\FrontException
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
        $data = static::parseResponse((string) $response->getBody());

        if ( !isset($data['ErrorCode']) ) {
            throw new FrontException('Invalid response from gateway: ' . $code);
        }

        if ( $data['ErrorCode'] != 0 ) {
            throw new FrontException(sprintf('Gateway error: %s (%s)', $data['ID'], $data['ErrorCode']));
        }
    }

}
