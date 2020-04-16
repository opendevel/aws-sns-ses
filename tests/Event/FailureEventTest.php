<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\TestCase;
use SmartEmailing\Types\JsonString;

final class FailureEventTest extends TestCase
{
    use ObjectMixinTrait;

    public function testEvent(): void
    {
        $json = <<<JSON
{
    "errorMessage": "Attribute 'attributeName' is not present in the rendering data.",
    "templateName": "MyTemplate"
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $event = FailureEvent::fromArray($array);
        $this->assertInstanceOf(FailureEvent::class, $event);
        $this->assertSame($array, $event->getPayload());

        $this->assertSame($array['errorMessage'], $event->getErrorMessage());
        $this->assertSame($array['templateName'], $event->getTemplateName());
    }
}
