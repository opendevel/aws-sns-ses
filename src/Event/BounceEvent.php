<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Opendevel\Aws\SnsSes\Event\Recipient\BouncedRecipient;
use SmartEmailing\Types\Arrays;
use SmartEmailing\Types\PrimitiveTypes;

final class BounceEvent implements IEvent
{

    /**
     * The type of bounce, as determined by Amazon SES.
     *
     * @var string
     */
    private $bounceType;

    /**
     * The subtype of the bounce, as determined by Amazon SES.
     *
     * @var string
     */
    private $bounceSubType;

    /**
     * @var BouncedRecipient[]
     */
    private $bouncedRecipients = [];

    /**
     * The datetime when the ISP sent the bounce notification
     *
     * @var \DateTimeInterface
     */
    private $timestamp;

    /**
     * A unique ID for the bounce
     *
     * @var string
     */
    private $feedbackId;

    /**
     * The IP address of the MTA to which Amazon SES attempted to deliver the email
     *
     * @var string|null
     */
    private $remoteMtaIp = null;

    /**
     * The value of the Reporting-MTA field from the DSN.
     * (value of the Message Transfer Authority (MTA) that attempted to perform the delivery, relay,
     * or gateway operation described in the DSN.)
     * (only appears if a delivery status notification (DSN) was attached to the bounce.)
     *
     * @var string|null
     */
    private $reportingMTA = null;

    /**
     * @var mixed[]
     */
    private $payload = [];

    /**
     * @param string $bounceType
     * @param string $bounceSubType
     * @param BouncedRecipient[] $bouncedRecipients
     * @param \DateTimeInterface $timestamp
     * @param string $feedbackId
     * @param string|null $remoteMtaIp
     * @param string|null $reportingMTA
     */
    private function __construct(
        string $bounceType,
        string $bounceSubType,
        array $bouncedRecipients,
        \DateTimeInterface $timestamp,
        string $feedbackId,
        ?string $remoteMtaIp,
        ?string $reportingMTA
    ) {
        $this->bounceType = $bounceType;
        $this->bounceSubType = $bounceSubType;
        $this->bouncedRecipients = $bouncedRecipients;
        $this->timestamp = $timestamp;
        $this->feedbackId = $feedbackId;
        $this->remoteMtaIp = $remoteMtaIp;
        $this->reportingMTA = $reportingMTA;
    }

    /**
     * @param mixed[] $data
     * @return BounceEvent
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $bouncedRecipients = [];
        foreach (Arrays::extractArray($data, 'bouncedRecipients') as $bouncedRecipient) {
            $bouncedRecipients[] = BouncedRecipient::fromArray($bouncedRecipient);
        }

        $self = new self(
            PrimitiveTypes::extractString($data, 'bounceType'),
            PrimitiveTypes::extractString($data, 'bounceSubType'),
            $bouncedRecipients,
            new \DateTimeImmutable(PrimitiveTypes::extractString($data, 'timestamp'), new \DateTimeZone('UTC')),
            PrimitiveTypes::extractString($data, 'feedbackId'),
            PrimitiveTypes::extractStringOrNull($data, 'remoteMtaIp'),
            PrimitiveTypes::extractStringOrNull($data, 'reportingMTA')
        );

        $self->payload = $data;

        return $self;
    }

    /**
     * @return string
     */
    public function getBounceType(): string
    {
        return $this->bounceType;
    }

    /**
     * @return string
     */
    public function getBounceSubType(): string
    {
        return $this->bounceSubType;
    }

    /**
     * @return BouncedRecipient[]
     */
    public function getBouncedRecipients(): array
    {
        return $this->bouncedRecipients;
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
    public function getFeedbackId(): string
    {
        return $this->feedbackId;
    }

    /**
     * @return string|null
     */
    public function getRemoteMtaIp(): ?string
    {
        return $this->remoteMtaIp;
    }

    /**
     * @return string|null
     */
    public function getReportingMTA(): ?string
    {
        return $this->reportingMTA;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
