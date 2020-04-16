<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use SmartEmailing\Types\PrimitiveTypes;

/**
 * Contains information about a Open event
 */
final class OpenEvent implements IEvent
{

    /**
     * The recipient's IP address
     *
     * @var string
     */
    private $ipAddress;

    /**
     * The datetime when the open event occurred
     *
     * @var \DateTimeInterface
     *
     */
    private $timestamp;

    /**
     * The user agent of the device or email client that the recipient used to open the email
     *
     * @var string
     */
    private $userAgent;

    /**
     * @var mixed[]
     */
    private $payload = [];

    /**
     * @param string $ipAddress
     * @param \DateTimeInterface $timestamp
     * @param string $userAgent
     */
    private function __construct(
        string $ipAddress,
        \DateTimeInterface $timestamp,
        string $userAgent
    ) {
        $this->ipAddress = $ipAddress;
        $this->timestamp = $timestamp;
        $this->userAgent = $userAgent;
    }

    /**
     * @param mixed[] $data
     * @return OpenEvent
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $self = new self(
            PrimitiveTypes::extractString($data, 'ipAddress'),
            new \DateTimeImmutable(PrimitiveTypes::extractString($data, 'timestamp'), new \DateTimeZone('UTC')),
            PrimitiveTypes::extractString($data, 'userAgent')
        );

        $self->payload = $data;

        return $self;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
