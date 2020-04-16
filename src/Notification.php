<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes;

use SmartEmailing\Types\JsonString;
use SmartEmailing\Types\PrimitiveTypes;

/**
 * Amazon SNS HTTP/HTTPS Notification
 */
final class Notification
{

    /**
     * Message ID
     * (resent message has the same ID)
     *
     * @var string
     */
    private $messageId;

    /**
     * The datetime when the notification was published
     *
     * @var \DateTimeInterface
     */
    private $timestamp;

    /**
     * Signature of the Message
     * Base64-encoded SHA1withRSA
     * (MessageId, Subject (if present), Type, Timestamp, and TopicArn values)
     *
     * @var string
     */
    private $signature;

    /**
     * Version of the Amazon SNS signature used
     *
     * @var string
     */
    private $signatureVersion;

    /**
     * URL to the certificate that was used to sign the message
     *
     * @var string  //@todo make URL type
     */
    private $signingCertURL;

    /**
     * The Amazon Resource Name (ARN) for the topic that this message was published to
     *
     * @var string  //@todo make ARN type
     */
    private $topicArn;

    /**
     * A URL that you can use to unsubscribe the endpoint from this topic.
     * If you visit this URL, Amazon SNS unsubscribes the endpoint and stops sending notifications to this endpoint
     *
     * @var string  //@todo make URL type
     */
    private $unsubscribeURL;

    /**
     * Message value
     *
     * @var Message
     */
    private $message;

    /**
     * The Subject parameter specified when the notification was published to the topic
     *
     * @var string|null
     */
    private $subject = null;

    /**
     * Notification payload
     *
     * @var mixed[]
     */
    private $payload = [];

    /**
     * Notification constructor.
     * @param string $messageId
     * @param \DateTimeInterface $timestamp
     * @param string $signature
     * @param string $signatureVersion
     * @param string $signingCertURL
     * @param string $topicArn
     * @param string $unsubscribeURL
     * @param Message $message
     * @param string|null $subject
     */
    private function __construct(
        string $messageId,
        \DateTimeInterface $timestamp,
        string $signature,
        string $signatureVersion,
        string $signingCertURL,
        string $topicArn,
        string $unsubscribeURL,
        ?Message $message,
        ?string $subject
    ) {
        $this->messageId = $messageId;
        $this->timestamp = $timestamp;
        $this->signature = $signature;
        $this->signatureVersion = $signatureVersion;
        $this->signingCertURL = $signingCertURL;
        $this->topicArn = $topicArn;
        $this->unsubscribeURL = $unsubscribeURL;
        $this->message = $message;
        $this->subject = $subject;
    }


    /**
     * @param mixed[] $data
     * @return Notification
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $message = null;
        $messageJson = JsonString::extractOrNull($data, 'Message');
        if ($messageJson) {
            $messageArray = $messageJson->getDecodedValue();
            $message = Message::fromArray($messageArray);
        }

        $self = new self(
            PrimitiveTypes::extractString($data, 'MessageId'),
            new \DateTimeImmutable(PrimitiveTypes::extractString($data, 'Timestamp'), new \DateTimeZone('UTC')),
            PrimitiveTypes::extractString($data, 'Signature'),
            PrimitiveTypes::extractString($data, 'SignatureVersion'),
            PrimitiveTypes::extractString($data, 'SigningCertURL'),
            PrimitiveTypes::extractString($data, 'TopicArn'),
            PrimitiveTypes::extractString($data, 'UnsubscribeURL'),
            $message,
            PrimitiveTypes::extractStringOrNull($data, 'Subject', true)
        );

        $self->payload = $data;

        return $self;
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
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
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @return string
     */
    public function getSignatureVersion(): string
    {
        return $this->signatureVersion;
    }

    /**
     * @return string
     */
    public function getSigningCertURL(): string
    {
        return $this->signingCertURL;
    }

    /**
     * @return string
     */
    public function getTopicArn(): string
    {
        return $this->topicArn;
    }

    /**
     * @return string
     */
    public function getUnsubscribeURL(): string
    {
        return $this->unsubscribeURL;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
