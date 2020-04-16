<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\Event\Recipient\Recipient;
use Opendevel\Aws\SnsSes\TestCase;
use SmartEmailing\Types\JsonString;

final class ClickEventTest extends TestCase
{
    use ObjectMixinTrait;

    public function testEvent(): void
    {
        $json = <<<JSON
{
    "ipAddress": "192.0.2.1",
    "link": "http://docs.aws.amazon.com/ses/latest/DeveloperGuide/send-email-smtp.html",
    "linkTags": {
        "samplekey0": [
            "samplevalue0"
        ],
        "samplekey1": [
            "samplevalue1"
        ]
    },
    "timestamp": "2017-08-09T23:51:25.570Z",
    "userAgent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)"
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $event = ClickEvent::fromArray($array);
        $this->assertInstanceOf(ClickEvent::class, $event);
        $this->assertSame($array, $event->getPayload());

        $this->assertSame($array['ipAddress'], $event->getIpAddress());
        $this->assertSame($array['linkTags'], $event->getLinkTags());
        $this->assertSame($array['timestamp'], $event->getTimestamp()->format($this->datetimeFormat));
        $this->assertSame($array['userAgent'], $event->getUserAgent());
    }
}
