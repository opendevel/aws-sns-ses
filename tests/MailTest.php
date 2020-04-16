<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\Event\Recipient\Recipient;
use Opendevel\Aws\SnsSes\Object\Header;
use SmartEmailing\Types\JsonString;

final class MailTest extends TestCase
{
    use ObjectMixinTrait;

    public function testTest(): void
    {
        $json = <<<JSON
{
    "timestamp": "2016-10-19T23:20:52.240Z",
    "source": "Sender Name <sender@example.com>",
    "sourceArn": "arn:aws:ses:us-east-1:123456789012:identity/sender@example.com",
    "sendingAccountId": "123456789012",
    "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
    "destination": [
        "recipient@example.com",
        "abc@example.com"
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
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $mail = Mail::fromArray($array);

        $this->assertSame($array['timestamp'], $mail->getTimestamp()->format($this->datetimeFormat));
        $this->assertSame($array['source'], $mail->getSource()->getFullAddress());
        $this->assertSame($array['sourceArn'], $mail->getSourceArn());
        $this->assertSame($array['sendingAccountId'], $mail->getSendingAccountId());
        $this->assertSame($array['messageId'], $mail->getMessageId());
        $this->assertSame($array['headersTruncated'], $mail->isHeadersTruncated());

        // destination
        $destinations = $mail->getDestination();
        $destination0 = $destinations[0];
        $this->assertInstanceOf(Recipient::class, $destination0);
        $this->assertSame($array['destination'][0], $destination0->getEmailAddress());

        $headers = $mail->getHeaders();
        $header0 = $headers[0];
        $this->assertInstanceOf(Header::class, $header0);
        $this->assertSame($array['headers'][0]['name'], $header0->getName());
        $this->assertSame($array['headers'][0]['value'], $header0->getValue());

        $commonHeaders = $mail->getCommonHeaders();
        $commonHeader0 = $commonHeaders[0];
        $this->assertSame('from', $commonHeader0->getName());
        $this->assertSame($array['commonHeaders']['from'], $commonHeader0->getValue());

        $commonHeader2 = $commonHeaders[2];
        $this->assertSame('messageId', $commonHeader2->getName());
        $this->assertSame($array['commonHeaders']['messageId'], $commonHeader2->getValue());

        //@todo tags
    }
}
