<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\TestCase;
use SmartEmailing\Types\JsonString;

final class OpenEventTest extends TestCase
{
    use ObjectMixinTrait;

    public function testEvent(): void
    {
        $json = <<<JSON
{
    "ipAddress": "192.0.2.1",
    "timestamp": "2017-08-09T22:00:19.652Z",
    "userAgent": "Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_3 like Mac OS X)"
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $event = OpenEvent::fromArray($array);
        $this->assertInstanceOf(OpenEvent::class, $event);
        $this->assertSame($array, $event->getPayload());

        $this->assertSame($array, $event->getPayload());
        $this->assertSame($array['ipAddress'], $event->getIpAddress());
        $this->assertSame($array['timestamp'], $event->getTimestamp()->format($this->datetimeFormat));
        $this->assertSame($array['userAgent'], $event->getUserAgent());
    }
}
