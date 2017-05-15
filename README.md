# Front SMS Notifications Channel for Laravel

Easy SMS notifications with [Front](http://fro.no/).

## Installation

```
$ composer require andersevenrud/laravel-frontsms
```

## Configuration

In `config/services.php`:

```php
return [
    'frontsms' => [
        'endpoint' => env('FRONTSMS_ENDPOINT', 'https://www.pling.as/psk/push.php'),
        'serviceid' => env('FRONTSMS_SERVICEID', 1234),
        'fromid' => env('FRONTSMS_FROMID', 'myapplication')
    ]
];
```

Then set up `.env` accordingly.

## Usage

```php
use NotificationChannels\Front\FrontChannel;
use NotificationChannels\Front\FrontMessage;
use Illuminate\Notifications\Notification;

class ExampleNotification extends Notification
{
    public function via($notifiable)
    {
        return [FrontChannel::class];
    }

    public function toFront($notifiable)
    {
        return FrontMessage::create('12345678', 'Hello world!');
    }
}
```
