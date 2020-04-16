<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Opendevel\Aws\SnsSes\Event\Recipient\Recipient;
use SmartEmailing\Types\Arrays;
use SmartEmailing\Types\PrimitiveTypes;

/**
 * information about a Delivery event
 */
final class DeliveryEvent implements IEvent
{

    /**
     * Datetime when Amazon SES delivered the email to the recipient's mail server
     *
     * @var \DateTimeInterface
     */
    private $timestamp;

    /**
     * The time in milliseconds between when Amazon SES accepted the request from the sender
     * to when Amazon SES passed the message to the recipient's mail server.
     *
     * @var int
     */
    private $processingTimeMillis;

    /**
     * Intended recipients that the delivery event applies to.
     *
     * @var Recipient[]
     */
    private $recipients = [];

    /**
     * The SMTP response message of the remote ISP that accepted the email from Amazon SES.
     * This message will vary by email, by receiving mail server, and by receiving ISP.
     *
     * @var string
     */
    private $smtpResponse;

    /**
     * The host name of the Amazon SES mail server that sent the mail.
     *
     * @var string
     */
    private $reportingMTA;

    /**
     * @var string|null
     */
    private $remoteMtaIp = null;

    /**
     * @var mixed[]
     */
    private $payload = [];

    /**
     * @param \DateTimeInterface $timestamp
     * @param int $processingTimeMillis
     * @param Recipient[] $recipients
     * @param string $smtpResponse
     * @param string $reportingMTA
     * @param string|null $remoteMtaIp
     */
    private function __construct(
        \DateTimeInterface $timestamp,
        int $processingTimeMillis,
        array $recipients,
        string $smtpResponse,
        string $reportingMTA,
        ?string $remoteMtaIp
    ) {
        $this->timestamp = $timestamp;
        $this->processingTimeMillis = $processingTimeMillis;
        $this->recipients = $recipients;
        $this->smtpResponse = $smtpResponse;
        $this->reportingMTA = $reportingMTA;
        $this->remoteMtaIp = $remoteMtaIp;
    }

    /**
     * @param mixed[] $data
     * @return DeliveryEvent
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $recipients = [];
        foreach (Arrays::extractArray($data, 'recipients') as $key => $value) {
            $recipients[] = Recipient::fromArray(['emailAddress' => $value]);
        }

        $self = new self(
            new \DateTimeImmutable(PrimitiveTypes::extractString($data, 'timestamp'), new \DateTimeZone('UTC')),
            PrimitiveTypes::extractInt($data, 'processingTimeMillis'),
            $recipients,
            PrimitiveTypes::extractString($data, 'smtpResponse'),
            PrimitiveTypes::extractString($data, 'reportingMTA'),
            PrimitiveTypes::extractStringOrNull($data, 'remoteMtaIp')
        );

        $self->payload = $data;

        return $self;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getProcessingTimeMillis(): int
    {
        return $this->processingTimeMillis;
    }

    /**
     * @return Recipient[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @return string
     */
    public function getSmtpResponse(): string
    {
        return $this->smtpResponse;
    }

    /**
     * @return string
     */
    public function getReportingMTA(): string
    {
        return $this->reportingMTA;
    }

    /**
     * @return string|null
     */
    public function getRemoteMtaIp(): ?string
    {
        return $this->remoteMtaIp;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
