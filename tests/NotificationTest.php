<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes;

use Consistence\Type\ObjectMixinTrait;
use SmartEmailing\Types\JsonString;

final class NotificationTest extends TestCase
{
    use ObjectMixinTrait;

    public function testNotification(): void
    {
        $json = <<<JSON
{
    "Type": "Notification",
    "MessageId": "22b80b92-fdea-4c2c-8f9d-bdfb0c7bf324",
    "TopicArn": "arn:aws:sns:us-west-2:123456789012:MyTopic",
    "Subject": "Amazon SES Email Event Notification",
    "Message": {
        "eventType": "Delivery",
        "mail": {
            "timestamp": "2016-10-19T23:20:52.240Z",
            "source": "sender@example.com",
            "sourceArn": "arn:aws:ses:us-east-1:123456789012:identity/sender@example.com",
            "sendingAccountId": "123456789012",
            "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
            "destination": [
                "recipient@example.com"
            ],
            "headersTruncated": false,
            "headers": [
                {
                    "name": "From",
                    "value": "sender@example.com"
                },
                {
                    "name": "To",
                    "value": "recipient@example.com"
                },
                {
                    "name": "Subject",
                    "value": "Message sent from Amazon SES"
                },
                {
                    "name": "MIME-Version",
                    "value": "1.0"
                },
                {
                    "name": "Content-Type",
                    "value": "text/html; charset=UTF-8"
                },
                {
                    "name": "Content-Transfer-Encoding",
                    "value": "7bit"
                }
            ],
            "commonHeaders": {
                "from": [
                    "sender@example.com"
                ],
                "to": [
                    "recipient@example.com"
                ],
                "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
                "subject": "Message sent from Amazon SES"
            },
            "tags": {
                "ses:configuration-set": [
                    "ConfigSet"
                ],
                "ses:source-ip": [
                    "192.0.2.0"
                ],
                "ses:from-domain": [
                    "example.com"
                ],
                "ses:caller-identity": [
                    "ses_user"
                ],
                "ses:outgoing-ip": [
                    "192.0.2.0"
                ],
                "myCustomTag1": [
                    "myCustomTagValue1"
                ],
                "myCustomTag2": [
                    "myCustomTagValue2"
                ]
            }
        },
        "delivery": {
            "timestamp": "2016-10-19T23:21:04.133Z",
            "processingTimeMillis": 11893,
            "recipients": [
                "recipient@example.com"
            ],
            "smtpResponse": "250 2.6.0 Message received",
            "reportingMTA": "mta.example.com"
        }
    },
    "Timestamp": "2012-05-02T00:54:06.655Z",
    "SignatureVersion": "1",
    "Signature": "EXAMPLEw6JRN...",
    "SigningCertURL": "https://sns.us-west-2.amazonaws.com/SimpleNotificationService-f3ecfb7224c7233fe7bb5f59f96de52f.pem",
    "UnsubscribeURL": "https://sns.us-west-2.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:us-west-2:123456789012:MyTopic:c9135db0-26c4-47ec-8998-413945fb5a96"
}

JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $notification = Notification::fromArray($array);
        $this->assertInstanceOf(Notification::class, $notification);

        $this->assertSame($array['Type'], 'Notification');
        $this->assertSame($array['MessageId'], $notification->getMessageId());
        $this->assertSame($array['TopicArn'], $notification->getTopicArn());
        $this->assertSame($array['Subject'], $notification->getSubject());

        $message = $notification->getMessage();
        $this->assertInstanceOf(Message::class, $message);

        $this->assertSame($array['Timestamp'], $notification->getTimestamp()->format($this->datetimeFormat));

        $this->assertSame($array['SignatureVersion'], $notification->getSignatureVersion());
        $this->assertSame($array['Signature'], $notification->getSignature());
        $this->assertSame($array['SigningCertURL'], $notification->getSigningCertURL());
        $this->assertSame($array['UnsubscribeURL'], $notification->getUnsubscribeURL());
    }
}
