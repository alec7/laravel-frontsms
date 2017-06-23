# Laravel Front SMS Provider(s)

Easy SMS with [Front](http://fro.no/).

Contains Notification Channel, Service Provider and a Facade.

## Installation

```
$ composer require andersevenrud/laravel-frontsms
```

## Configuration

In `config/app.php`:

```

'providers' => [
    Laravel\FrontSMS\FrontSMSServiceProvider::class,
],

'aliases' => [
  'FrontSMS' => Laravel\FrontSMS\Facades\FrontSMS::class
]
```

Then publish configurations:

```
$ php artisan vendor:publish
```

You now have `config/frontsms.php`.

## Usage

### General

```php
use FrontSMS;

function something() {

  $result = FrontSMS::send(12345678, 'hello world!');

}

```

### Notifications

```php
use NotificationChannels\FrontSMS\FrontSMSChannel;
use NotificationChannels\FrontSMS\FrontSMSMessage;
use Illuminate\Notifications\Notification;

class ExampleNotification extends Notification
{
    public function via($notifiable)
    {
        return [FrontSMSChannel::class];
    }

    public function toFront($notifiable)
    {
        return FrontSMSMessage::create('12345678', 'Hello world!');
    }
}
```

## Changelog

* **0.6.1** - Updated composer.json
* **0.6.0** - Initial release

## License

MIT
