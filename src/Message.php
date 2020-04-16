<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes;

use Opendevel\Aws\SnsSes\Event\BounceEvent;
use Opendevel\Aws\SnsSes\Event\ClickEvent;
use Opendevel\Aws\SnsSes\Event\ComplaintEvent;
use Opendevel\Aws\SnsSes\Event\DeliveryEvent;
use Opendevel\Aws\SnsSes\Event\FailureEvent;
use Opendevel\Aws\SnsSes\Event\IEvent;
use Opendevel\Aws\SnsSes\Event\OpenEvent;
use Opendevel\Aws\SnsSes\Event\RejectEvent;
use Opendevel\Aws\SnsSes\Event\SendEvent;
use SmartEmailing\Types\Arrays;
use SmartEmailing\Types\PrimitiveTypes;

final class Message
{
    /**
     * Type of event
     *
     * @var string
     */
    private $eventType;

    /**
     * Mail object containing information about the email that produced the event
     *
     * @var Mail
     */
    private $mail;

    /**
     * Event object depending on the type of event
     *
     * @var IEvent|null
     */
    private $event;

    /**
     * @param string $eventType
     * @param Mail $mail
     * @param IEvent $event
     */
    private function __construct(string $eventType, Mail $mail, ?IEvent $event)
    {
        $this->eventType = $eventType;
        $this->mail = $mail;
        $this->event = $event;
    }

    /**
     * @param mixed[] $data
     * @return Message
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $eventType = PrimitiveTypes::extractString($data, 'eventType');
        $mail = Mail::fromArray(Arrays::extractArray($data, 'mail'));

        switch ($eventType) {
            case 'Delivery':
                $event = DeliveryEvent::fromArray(Arrays::extractArray($data, 'delivery'));
                break;
            case 'Send':
                $event = SendEvent::fromArray(Arrays::extractArray($data, 'send'));
                break;
            case 'Reject':
                $event = RejectEvent::fromArray(Arrays::extractArray($data, 'reject'));
                break;
            case 'Open':
                $event = OpenEvent::fromArray(Arrays::extractArray($data, 'open'));
                break;
            case 'Click':
                $event = ClickEvent::fromArray(Arrays::extractArray($data, 'click'));
                break;
            case 'Bounce':
                $event = BounceEvent::fromArray(Arrays::extractArray($data, 'bounce'));
                break;
            case 'Complaint':
                $event = ComplaintEvent::fromArray(Arrays::extractArray($data, 'complaint'));
                break;
            case 'Rendering Failure':
                $event = FailureEvent::fromArray(Arrays::extractArray($data, 'failure'));
                break;
            default:
                $event = null;
                break;
        }

        return new self(
            $eventType,
            $mail,
            $event
        );
    }

    /**
     * @return string
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * @return Mail
     */
    public function getMail(): Mail
    {
        return $this->mail;
    }

    /**
     * @return IEvent
     */
    public function getEvent(): IEvent
    {
        return $this->event;
    }
}
