<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

/**
 * Information about a send event
 */
final class SendEvent implements IEvent
{

    /**
     * @var mixed[]
     */
    private $payload = [];

    private function __construct()
    {
    }

    /**
     * @param mixed[] $data
     * @return SendEvent
     */
    public static function fromArray(array $data): self
    {
        $self = new self();

        $self->payload = $data;

        return $self;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
