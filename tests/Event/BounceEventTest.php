<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\Event\Recipient\BouncedRecipient;
use Opendevel\Aws\SnsSes\TestCase;
use SmartEmailing\Types\JsonString;

final class BounceEventTest extends TestCase
{
    use ObjectMixinTrait;

    public function testEvent(): void
    {
        $json = <<<JSON
{
    "bounceType": "Permanent",
    "bounceSubType": "General",
    "bouncedRecipients": [
        {
            "status": "5.0.0",
            "action": "failed",
            "diagnosticCode": "smtp; 550 user unknown",
            "emailAddress": "recipient1@example.com"
        },
        {
            "status": "4.0.0",
            "action": "delayed",
            "emailAddress": "recipient2@example.com"
        }
    ],
    "reportingMTA": "example.com",
    "timestamp": "2012-05-25T14:59:38.605Z",
    "feedbackId": "000001378603176d-5a4b5ad9-6f30-4198-a8c3-b1eb0c270a1d-000000",
    "remoteMtaIp": "127.0.2.0"
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $event = BounceEvent::fromArray($array);
        $this->assertInstanceOf(BounceEvent::class, $event);
        $this->assertSame($array, $event->getPayload());

        $this->assertSame($array['bounceType'], $event->getBounceType());
        $this->assertSame($array['bounceSubType'], $event->getBounceSubType());
        $this->assertSame($array['reportingMTA'], $event->getReportingMTA());
        $this->assertSame($array['timestamp'], $event->getTimestamp()->format($this->datetimeFormat));
        $this->assertSame($array['feedbackId'], $event->getFeedbackId());
        $this->assertSame($array['remoteMtaIp'], $event->getRemoteMtaIp());

        $bouncedRecipients = $event->getBouncedRecipients();
        $this->assertCount(2, $bouncedRecipients);

        $bouncedRecipient0 = $bouncedRecipients[0];
        $this->assertInstanceOf(BouncedRecipient::class, $bouncedRecipient0);
        $this->assertSame($array['bouncedRecipients'][0]['status'], $bouncedRecipient0->getStatus());
        $this->assertSame($array['bouncedRecipients'][0]['action'], $bouncedRecipient0->getAction());
        $this->assertSame($array['bouncedRecipients'][0]['diagnosticCode'], $bouncedRecipient0->getDiagnosticCode());
        $this->assertSame($array['bouncedRecipients'][0]['emailAddress'], $bouncedRecipient0->getEmailAddress());

        $bouncedRecipient1 = $bouncedRecipients[1];
        $this->assertInstanceOf(BouncedRecipient::class, $bouncedRecipient1);
        $this->assertSame($array['bouncedRecipients'][1]['status'], $bouncedRecipient1->getStatus());
        $this->assertSame($array['bouncedRecipients'][1]['action'], $bouncedRecipient1->getAction());
        $this->assertSame(null, $bouncedRecipient1->getDiagnosticCode());
        $this->assertSame($array['bouncedRecipients'][1]['emailAddress'], $bouncedRecipient1->getEmailAddress());
    }
}
