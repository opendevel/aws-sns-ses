<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes;

use Consistence\Type\ObjectMixinTrait;
use Opendevel\Aws\SnsSes\Event\BounceEvent;
use Opendevel\Aws\SnsSes\Event\ClickEvent;
use Opendevel\Aws\SnsSes\Event\ComplaintEvent;
use Opendevel\Aws\SnsSes\Event\DeliveryEvent;
use Opendevel\Aws\SnsSes\Event\FailureEvent;
use Opendevel\Aws\SnsSes\Event\OpenEvent;
use Opendevel\Aws\SnsSes\Event\RejectEvent;
use Opendevel\Aws\SnsSes\Event\SendEvent;
use SmartEmailing\Types\JsonString;

/**
 * @see https://docs.aws.amazon.com/ses/latest/DeveloperGuide/event-publishing-retrieving-sns-examples.html
 */
final class MessageTest extends TestCase
{
    use ObjectMixinTrait;

    public function testGetMessage(): void
    {
        $json = <<<JSON
{
    "eventType": "Bounce",
    "bounce": {
        "bounceType": "Permanent",
        "bounceSubType": "General",
        "bouncedRecipients": [
            {
                "emailAddress": "recipient@example.com",
                "action": "failed",
                "status": "5.1.1",
                "diagnosticCode": "smtp; 550 5.1.1 user unknown"
            }
        ],
        "timestamp": "2017-08-05T00:41:02.669Z",
        "feedbackId": "01000157c44f053b-61b59c11-9236-11e6-8f96-7be8aexample-000000",
        "reportingMTA": "dsn; mta.example.com"
    },
    "mail": {
        "timestamp": "2017-08-05T00:40:02.012Z",
        "source": "Sender Name <sender@example.com>",
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
                "value": "Sender Name <sender@example.com>"
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
                "value": "multipart/alternative; boundary=\"----=_Part_7307378_1629847660.1516840721503\""
            }
        ],
        "commonHeaders": {
            "from": [
                "Sender Name <sender@example.com>"
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
            ]
        }
    }
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertSame($array['eventType'], $message->getEventType());
        $this->assertInstanceOf(Mail::class, $message->getMail());
    }

    public function testGetBounceEvent(): void
    {
        $json = <<<JSON
{
    "eventType": "Bounce",
    "bounce": {
        "bounceType": "Permanent",
        "bounceSubType": "General",
        "bouncedRecipients": [
            {
                "emailAddress": "recipient@example.com",
                "action": "failed",
                "status": "5.1.1",
                "diagnosticCode": "smtp; 550 5.1.1 user unknown"
            }
        ],
        "timestamp": "2017-08-05T00:41:02.669Z",
        "feedbackId": "01000157c44f053b-61b59c11-9236-11e6-8f96-7be8aexample-000000",
        "reportingMTA": "dsn; mta.example.com"
    },
    "mail": {
        "timestamp": "2017-08-05T00:40:02.012Z",
        "source": "Sender Name <sender@example.com>",
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
                "value": "Sender Name <sender@example.com>"
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
                "value": "multipart/alternative; boundary=\"----=_Part_7307378_1629847660.1516840721503\""
            }
        ],
        "commonHeaders": {
            "from": [
                "Sender Name <sender@example.com>"
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
            ]
        }
    }
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertInstanceOf(BounceEvent::class, $message->getEvent());
    }

    public function testGetComplaintEvent(): void
    {
        $json = <<<JSON
{
    "eventType": "Complaint",
    "complaint": {
        "complainedRecipients": [
            {
                "emailAddress": "recipient@example.com"
            }
        ],
        "timestamp": "2017-08-05T00:41:02.669Z",
        "feedbackId": "01000157c44f053b-61b59c11-9236-11e6-8f96-7be8aexample-000000",
        "userAgent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        "complaintFeedbackType": "abuse",
        "arrivalDate": "2017-08-05T00:41:02.669Z"
    },
    "mail": {
        "timestamp": "2017-08-05T00:40:01.123Z",
        "source": "Sender Name <sender@example.com>",
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
                "value": "Sender Name <sender@example.com>"
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
                "value": "multipart/alternative; boundary=\"----=_Part_7298998_679725522.1516840859643\""
            }
        ],
        "commonHeaders": {
            "from": [
                "Sender Name <sender@example.com>"
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
            ]
        }
    }
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertInstanceOf(ComplaintEvent::class, $message->getEvent());
    }

    public function testGetDeliveryEvent(): void
    {
        $json = <<<JSON
{
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
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertInstanceOf(DeliveryEvent::class, $message->getEvent());
    }

    public function testGetSendEvent(): void
    {
        $json = <<<JSON
{
    "eventType": "Send",
    "mail": {
        "timestamp": "2016-10-14T05:02:16.645Z",
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
                "value": "multipart/mixed;  boundary=\"----=_Part_0_716996660.1476421336341\""
            },
            {
                "name": "X-SES-MESSAGE-TAGS",
                "value": "myCustomTag1=myCustomTagValue1, myCustomTag2=myCustomTagValue2"
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
            "myCustomTag1": [
                "myCustomTagValue1"
            ],
            "myCustomTag2": [
                "myCustomTagValue2"
            ]
        }
    },
    "send": {}
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertInstanceOf(SendEvent::class, $message->getEvent());
    }

    public function testGetRejectEvent(): void
    {
        $json = <<<JSON
{
    "eventType": "Reject",
    "mail": {
        "timestamp": "2016-10-14T17:38:15.211Z",
        "source": "sender@example.com",
        "sourceArn": "arn:aws:ses:us-east-1:123456789012:identity/sender@example.com",
        "sendingAccountId": "123456789012",
        "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
        "destination": [
            "sender@example.com"
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
                "value": "multipart/mixed; boundary=\"qMm9M+Fa2AknHoGS\""
            },
            {
                "name": "X-SES-MESSAGE-TAGS",
                "value": "myCustomTag1=myCustomTagValue1, myCustomTag2=myCustomTagValue2"
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
            "myCustomTag1": [
                "myCustomTagValue1"
            ],
            "myCustomTag2": [
                "myCustomTagValue2"
            ]
        }
    },
    "reject": {
        "reason": "Bad content"
    }
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertInstanceOf(RejectEvent::class, $message->getEvent());
    }

    public function testGetOpenEvent(): void
    {
        $json = <<<JSON
{
    "eventType": "Open",
    "mail": {
        "commonHeaders": {
            "from": [
                "sender@example.com"
            ],
            "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
            "subject": "Message sent from Amazon SES",
            "to": [
                "recipient@example.com"
            ]
        },
        "destination": [
            "recipient@example.com"
        ],
        "headers": [
            {
                "name": "X-SES-CONFIGURATION-SET",
                "value": "ConfigSet"
            },
            {
                "name": "X-SES-MESSAGE-TAGS",
                "value": "myCustomTag1=myCustomValue1, myCustomTag2=myCustomValue2"
            },
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
                "value": "multipart/alternative; boundary=\"XBoundary\""
            }
        ],
        "headersTruncated": false,
        "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
        "sendingAccountId": "123456789012",
        "source": "sender@example.com",
        "tags": {
            "myCustomTag1": [
                "myCustomValue1"
            ],
            "myCustomTag2": [
                "myCustomValue2"
            ],
            "ses:caller-identity": [
                "ses-user"
            ],
            "ses:configuration-set": [
                "ConfigSet"
            ],
            "ses:from-domain": [
                "example.com"
            ],
            "ses:source-ip": [
                "192.0.2.0"
            ]
        },
        "timestamp": "2017-08-09T21:59:49.927Z"
    },
    "open": {
        "ipAddress": "192.0.2.1",
        "timestamp": "2017-08-09T22:00:19.652Z",
        "userAgent": "Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_3 like Mac OS X)"
    }
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertInstanceOf(OpenEvent::class, $message->getEvent());
    }

    public function testGetClickEvent(): void
    {
        $json = <<<JSON
{
    "eventType": "Click",
    "click": {
        "ipAddress": "192.0.2.1",
        "link": "http://docs.aws.amazon.com/ses/latest/DeveloperGuide/send-email-smtp.html",
        "linkTags": {
            "samplekey0": [
                "samplevalue0"
            ],
            "samplekey1": [
                "samplevalue1"
            ]
        },
        "timestamp": "2017-08-09T23:51:25.570Z",
        "userAgent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)"
    },
    "mail": {
        "commonHeaders": {
            "from": [
                "sender@example.com"
            ],
            "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
            "subject": "Message sent from Amazon SES",
            "to": [
                "recipient@example.com"
            ]
        },
        "destination": [
            "recipient@example.com"
        ],
        "headers": [
            {
                "name": "X-SES-CONFIGURATION-SET",
                "value": "ConfigSet"
            },
            {
                "name": "X-SES-MESSAGE-TAGS",
                "value": "myCustomTag1=myCustomValue1, myCustomTag2=myCustomValue2"
            },
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
                "value": "multipart/alternative; boundary=\"XBoundary\""
            },
            {
                "name": "Message-ID",
                "value": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000"
            }
        ],
        "headersTruncated": false,
        "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
        "sendingAccountId": "123456789012",
        "source": "sender@example.com",
        "tags": {
            "myCustomTag1": [
                "myCustomValue1"
            ],
            "myCustomTag2": [
                "myCustomValue2"
            ],
            "ses:caller-identity": [
                "ses_user"
            ],
            "ses:configuration-set": [
                "ConfigSet"
            ],
            "ses:from-domain": [
                "example.com"
            ],
            "ses:source-ip": [
                "192.0.2.0"
            ]
        },
        "timestamp": "2017-08-09T23:50:05.795Z"
    }
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertInstanceOf(ClickEvent::class, $message->getEvent());
    }

    public function testGetFailureEvent(): void
    {
        $json = <<<JSON
{
    "eventType": "Rendering Failure",
    "mail": {
        "timestamp": "2018-01-22T18:43:06.197Z",
        "source": "sender@example.com",
        "sourceArn": "arn:aws:ses:us-east-1:123456789012:identity/sender@example.com",
        "sendingAccountId": "123456789012",
        "messageId": "EXAMPLE7c191be45-e9aedb9a-02f9-4d12-a87d-dd0099a07f8a-000000",
        "destination": [
            "recipient@example.com"
        ],
        "headersTruncated": false,
        "tags": {
            "ses:configuration-set": [
                "ConfigSet"
            ]
        }
    },
    "failure": {
        "errorMessage": "Attribute 'attributeName' is not present in the rendering data.",
        "templateName": "MyTemplate"
    }
}
JSON;

        $array = JsonString::from($json)->getDecodedValue();

        $message = Message::fromArray($array);

        $this->assertInstanceOf(FailureEvent::class, $message->getEvent());
    }
}
