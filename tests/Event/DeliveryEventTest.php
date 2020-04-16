<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\Event\Recipient\Recipient;
use Opendevel\Aws\SnsSes\TestCase;
use SmartEmailing\Types\JsonString;

final class DeliveryEventTest extends TestCase
{
    use ObjectMixinTrait;

    public function testEvent(): void
    {
        $json = <<<JSON
{
    "timestamp": "2014-05-28T22:41:01.184Z",
    "processingTimeMillis": 546,
    "recipients": [
        "success@simulator.amazonses.com"
    ],
    "smtpResponse": "250 ok:  Message 64111812 accepted",
    "reportingMTA": "a8-70.smtp-out.amazonses.com",
    "remoteMtaIp": "127.0.2.0"
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $event = DeliveryEvent::fromArray($array);
        $this->assertInstanceOf(DeliveryEvent::class, $event);
        $this->assertSame($array, $event->getPayload());

        $this->assertSame($array['timestamp'], $event->getTimestamp()->format($this->datetimeFormat));
        $this->assertSame((int)$array['processingTimeMillis'], $event->getProcessingTimeMillis());
        $this->assertSame($array['smtpResponse'], $event->getSmtpResponse());
        $this->assertSame($array['reportingMTA'], $event->getReportingMTA());
        $this->assertSame($array['remoteMtaIp'], $event->getRemoteMtaIp());

        $recipients = $event->getRecipients();
        $this->assertCount(1, $recipients);

        $recipient = $recipients[0];
        $this->assertInstanceOf(Recipient::class, $recipient);
        $this->assertSame('success@simulator.amazonses.com', $recipient->getEmailAddress());
    }
}
