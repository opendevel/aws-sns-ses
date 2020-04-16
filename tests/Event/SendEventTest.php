<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\TestCase;
use SmartEmailing\Types\JsonString;

final class SendEventTest extends TestCase
{
    use ObjectMixinTrait;

    public function testEvent(): void
    {
        $json = <<<JSON
{}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $event = SendEvent::fromArray($array);
        $this->assertInstanceOf(SendEvent::class, $event);
        $this->assertSame($array, $event->getPayload());
    }
}
