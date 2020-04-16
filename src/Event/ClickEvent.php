<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use SmartEmailing\Types\Arrays;
use SmartEmailing\Types\PrimitiveTypes;

final class ClickEvent implements IEvent
{
    /**
     * The recipient's IP address
     *
     * @var string
     */
    private $ipAddress;

    /**
     * The datetime when the click event occurred
     *
     * @var \DateTimeInterface
     */
    private $timestamp;

    /**
     * The user agent of the client that the recipient used to click a link in the email
     *
     * @var string
     */
    private $userAgent;

    /**
     * The URL of the link that the recipient clicked
     *
     * @var string
     */
    private $link;

    /**
     * A list of tags that were added to the link using the ses:tags attribute
     *
     * @var mixed[]
     */
    private $linkTags = [];

    /**
     * @var mixed[]
     */
    private $payload = [];

    /**
     * @param string $ipAddress
     * @param \DateTimeInterface $timestamp
     * @param string $userAgent
     * @param string $link
     * @param mixed[] $linkTags
     */
    private function __construct(
        string $ipAddress,
        \DateTimeInterface $timestamp,
        string $userAgent,
        string $link,
        array $linkTags
    ) {
        $this->ipAddress = $ipAddress;
        $this->timestamp = $timestamp;
        $this->userAgent = $userAgent;
        $this->link = $link;
        $this->linkTags = $linkTags;
    }

    /**
     * @param mixed[] $data
     * @return ClickEvent
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $self =  new self(
            PrimitiveTypes::extractString($data, 'ipAddress'),
            new \DateTimeImmutable(PrimitiveTypes::extractString($data, 'timestamp'), new \DateTimeZone('UTC')),
            PrimitiveTypes::extractString($data, 'userAgent'),
            PrimitiveTypes::extractString($data, 'link'),
            Arrays::extractArrayOrNull($data, 'linkTags')
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
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return mixed[]
     */
    public function getLinkTags(): array
    {
        return $this->linkTags;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
