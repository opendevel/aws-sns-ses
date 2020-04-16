<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use Opendevel\Aws\SnsSes\Event\Recipient\Recipient;
use SmartEmailing\Types\Arrays;
use SmartEmailing\Types\PrimitiveTypes;

final class ComplaintEvent implements IEvent
{
    /**
     * A list that contains information about recipients that may have submitted the complaint
     *
     * Most ISPs redact the email addresses of recipients who submit complaints. For this reason,
     * the complainedRecipients field includes a list of everyone who was sent the email whose address
     * is on the domain that issued the complaint notification
     *
     * @var Recipient[]
     */
    private $complainedRecipients = [];

    /**
     * The datetime when the ISP sent the complaint notification
     *
     * @var \DateTimeInterface
     */
    private $timestamp;

    /**
     * A unique ID for the complaint
     *
     * @var string
     */
    private $feedbackId;

    /**
     * The subtype of the complaint, as determined by Amazon SES
     *
     * @var string|null
     */
    private $complaintSubType = null;

    /**
     * The value of the User-Agent field from the feedback report.
     * This indicates the name and version of the system that generated the report
     *
     * @var string|null
     */
    private $userAgent = null;

    /**
     * The value of the Feedback-Type field from the feedback report received from the ISP.
     * This contains the type of feedback
     *
     * @var string|null
     */
    private $complaintFeedbackType = null;

    /**
     * The value of the Arrival-Date or Received-Date field from the feedback report.
     * This field may be absent in the report (and therefore also absent in the JSON)
     *
     * @var string|null //@todo datetime ISO8601 format
     */
    private $arrivalDate = null;

    /**
     * @var mixed[]
     */
    private $payload = [];

    /**
     * @param Recipient[] $complainedRecipients
     * @param \DateTimeInterface $timestamp
     * @param string $feedbackId
     * @param string|null $complaintSubType
     * @param string|null $userAgent
     * @param string|null $complaintFeedbackType
     * @param string|null $arrivalDate
     */
    private function __construct(
        array $complainedRecipients,
        \DateTimeInterface $timestamp,
        string $feedbackId,
        ?string $complaintSubType,
        ?string $userAgent,
        ?string $complaintFeedbackType,
        ?string $arrivalDate
    ) {
        $this->complainedRecipients = $complainedRecipients;
        $this->timestamp = $timestamp;
        $this->feedbackId = $feedbackId;
        $this->complaintSubType = $complaintSubType;
        $this->userAgent = $userAgent;
        $this->complaintFeedbackType = $complaintFeedbackType;
        $this->arrivalDate = $arrivalDate;
    }

    /**
     * @param mixed[] $data
     * @return ComplaintEvent
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $complainedRecipients = [];
        foreach (Arrays::extractArray($data, 'complainedRecipients') as $complainedRecipient) {
            $complainedRecipients[] = Recipient::fromArray($complainedRecipient);
        }

        $self = new self(
            $complainedRecipients,
            new \DateTimeImmutable(PrimitiveTypes::extractString($data, 'timestamp'), new \DateTimeZone('UTC')),
            PrimitiveTypes::extractString($data, 'feedbackId'),
            PrimitiveTypes::extractStringOrNull($data, 'complaintSubType'),
            PrimitiveTypes::extractStringOrNull($data, 'userAgent'),
            PrimitiveTypes::extractStringOrNull($data, 'complaintFeedbackType'),
            PrimitiveTypes::extractStringOrNull($data, 'arrivalDate')
        );

        $self->payload = $data;

        return $self;
    }

    /**
     * @return Recipient[]
     */
    public function getComplainedRecipients(): array
    {
        return $this->complainedRecipients;
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
    public function getComplaintSubType(): ?string
    {
        return $this->complaintSubType;
    }

    /**
     * @return string|null
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * @return string|null
     */
    public function getComplaintFeedbackType(): ?string
    {
        return $this->complaintFeedbackType;
    }

    /**
     * @return string|null  //@todo DatetimeInterface?
     */
    public function getArrivalDate(): ?string
    {
        return $this->arrivalDate;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
