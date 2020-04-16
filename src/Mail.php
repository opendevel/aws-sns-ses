<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes;

use Opendevel\Aws\SnsSes\Event\Recipient\Recipient;
use Opendevel\Aws\SnsSes\Object\EmailAddress;
use Opendevel\Aws\SnsSes\Object\Header;
use SmartEmailing\Types\Arrays;
use SmartEmailing\Types\PrimitiveTypes;

/**
 * Mail Object
 * Informations about the original email
 */
final class Mail
{

    /**
     * The datetime when the message was sent
     *
     * @var \DateTimeInterface
     */
    private $timestamp;

    /**
     * A unique ID that Amazon SES assigned to the message
     * (returned when the message is sent)
     *
     * @var string
     */
    private $messageId;

    /**
     * The email address that the message was sent from
     * (the envelope MAIL FROM address)
     * e.g.: sender@example.com
     * e.g.: Sender Name <sender@example.com>
     *
     * @var EmailAddress
     */
    private $source;

    /**
     * The Amazon Resource Name (ARN) of the identity that was used to send the email
     *
     * @var string|null
     */
    private $sourceArn = null;

    /**
     * The AWS account ID of the account that was used to send the email
     *
     * @var string
     */
    private $sendingAccountId;

    /**
     * A list of email addresses that were recipients of the original mail
     *
     * @var Recipient[]
     */
    private $destination = [];

    /**
     * Whether the headers are truncated in the notification, which occurs if the headers are larger than 10 KB
     *
     * @var bool
     */
    private $headersTruncated;

    /**
     * A list of the email's original headers
     *
     * @var Header[]
     */
    private $headers = [];

    /**
     * A list of the email's original, commonly used headers.
     *
     * @var Header[]
     */
    private $commonHeaders = [];

    //@todo tags?

    /**
     * Mail constructor.
     * @param \DateTimeInterface $timestamp
     * @param string $messageId
     * @param Emailaddress $source
     * @param string|null $sourceArn
     * @param string $sendingAccountId
     * @param Recipient[] $destination
     * @param bool $headersTruncated
     * @param Header[] $headers
     * @param Header[] $commonHeaders
     */
    private function __construct(
        \DateTimeInterface $timestamp,
        string $messageId,
        Emailaddress $source,
        ?string $sourceArn,
        string $sendingAccountId,
        array $destination,
        bool $headersTruncated,
        array $headers,
        array $commonHeaders
    ) {
        $this->timestamp = $timestamp;
        $this->messageId = $messageId;
        $this->source = $source;
        $this->sourceArn = $sourceArn;
        $this->sendingAccountId = $sendingAccountId;
        $this->destination = $destination;
        $this->headersTruncated = $headersTruncated;
        $this->headers = $headers;
        $this->commonHeaders = $commonHeaders;
    }

    /**
     * @param mixed[] $data
     * @return Mail
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $destination = [];
        foreach (Arrays::extractArray($data, 'destination') as $value) {
            $destination[] = new Recipient(PrimitiveTypes::getString($value));
        }

        $headers = [];
        if (Arrays::extractArrayOrNull($data, 'headers')) {
            foreach (Arrays::extractArray($data, 'headers') as $item) {
                $headers[] = Header::fromArray($item);
            }
        }

        $commonHeaders = [];
        if (Arrays::extractArrayOrNull($data, 'commonHeaders')) {
            foreach (Arrays::extractArray($data, 'commonHeaders') as $key => $value) {
                $commonHeaders[] = new Header(
                    PrimitiveTypes::getString($key),
                    $value
                );
            }
        }

        return new self(
            new \DateTimeImmutable(PrimitiveTypes::extractString($data, 'timestamp'), new \DateTimeZone('UTC')),
            PrimitiveTypes::extractString($data, 'messageId'),
            Emailaddress::extract($data, 'source'),
            PrimitiveTypes::extractStringOrNull($data, 'sourceArn'),
            PrimitiveTypes::extractString($data, 'sendingAccountId'),
            $destination,
            PrimitiveTypes::extractBool($data, 'headersTruncated'),
            $headers,
            $commonHeaders
        );
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
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @return Emailaddress
     */
    public function getSource(): Emailaddress
    {
        return $this->source;
    }

    /**
     * @return string|null
     */
    public function getSourceArn(): ?string
    {
        return $this->sourceArn;
    }

    /**
     * @return string
     */
    public function getSendingAccountId(): string
    {
        return $this->sendingAccountId;
    }

    /**
     * @return Recipient[]
     */
    public function getDestination(): array
    {
        return $this->destination;
    }

    /**
     * @return bool
     */
    public function isHeadersTruncated(): bool
    {
        return $this->headersTruncated;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return Header[]
     */
    public function getCommonHeaders(): array
    {
        return $this->commonHeaders;
    }
}
