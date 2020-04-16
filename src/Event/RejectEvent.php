<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use SmartEmailing\Types\PrimitiveTypes;

/**
 * information about the rejection.
 */
final class RejectEvent implements IEvent
{
    /**
     * The reason the email was rejected.
     * (The only possible value is Bad content, which means that Amazon SES detected that the email contained a virus.
     * When a message is rejected, Amazon SES stops processing it, and doesn't attempt to deliver it to the recipient's
     * mail server.)
     *
     * @var string
     */
    private $reason;

    /**
     * @var mixed[]
     */
    private $payload = [];

    /**
     * @param string $reason
     */
    private function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    /**
     * @param mixed[] $data
     * @return RejectEvent
     */
    public static function fromArray(array $data): self
    {
        $self = new self(PrimitiveTypes::extractString($data, 'reason'));

        $self->payload = $data;

        return $self;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
