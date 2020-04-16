<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\Event\Recipient\Recipient;
use Opendevel\Aws\SnsSes\TestCase;
use SmartEmailing\Types\JsonString;

final class ComplaintEventTest extends TestCase
{
    use ObjectMixinTrait;

    public function testEvent(): void
    {
        $json = <<<JSON
{
   "userAgent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
   "complainedRecipients":[
      {
         "emailAddress":"recipient1@example.com"
      }
   ],
   "complaintFeedbackType":"abuse",
   "arrivalDate":"2009-12-03T04:24:21.000-05:00",
   "timestamp":"2012-05-25T14:59:38.623Z",
   "feedbackId":"000001378603177f-18c07c78-fa81-4a58-9dd1-fedc3cb8f49a-000000"
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $event = ComplaintEvent::fromArray($array);
        $this->assertInstanceOf(ComplaintEvent::class, $event);
        $this->assertSame($array, $event->getPayload());

        $this->assertSame($array['userAgent'], $event->getUserAgent());
        $this->assertSame($array['complaintFeedbackType'], $event->getComplaintFeedbackType());
        $this->assertSame($array['arrivalDate'], $event->getArrivalDate());
        $this->assertSame($array['timestamp'], $event->getTimestamp()->format($this->datetimeFormat));
        $this->assertSame($array['feedbackId'], $event->getFeedbackId());
        $this->assertSame(null, $event->getComplaintSubType()); //@todo

        $complainedRecipients = $event->getComplainedRecipients();
        $this->assertCount(1, $complainedRecipients);

        $complainedRecipient0 = $complainedRecipients[0];
        $this->assertInstanceOf(Recipient::class, $complainedRecipient0);
        $this->assertSame($array['complainedRecipients'][0]['emailAddress'], $complainedRecipient0->getEmailAddress());
    }
}
