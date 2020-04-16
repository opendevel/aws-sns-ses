<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\TestCase;
use SmartEmailing\Types\JsonString;

final class RejectEventTest extends TestCase
{
    use ObjectMixinTrait;

    public function testEvent(): void
    {
        $json = <<<JSON
{
    "reason": "Bad content"
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $event = RejectEvent::fromArray($array);
        $this->assertInstanceOf(RejectEvent::class, $event);
        $this->assertSame($array, $event->getPayload());
        $this->assertSame($array['reason'], $event->getReason());
    }
}
