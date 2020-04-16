<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event\Enum;

use Consistence\Enum\Enum;

class ComplaintType extends Enum
{
    /**
     * Indicates unsolicited email or some other kind of email abuse.
     *
     * @var string
     */
    public const ABUSE = 'abuse';

    /**
     * Email authentication failure report.
     *
     * @var string
     */
    public const AUTH_FAILURE = 'auth-failure';

    /**
     * Indicates some kind of fraud or phishing activity.
     *
     * @var string
     */
    public const FRAUD = 'fraud';

    /**
     * Indicates that the entity providing the report does not consider the message to be spam.
     * This may be used to correct a message that was incorrectly tagged or categorized as spam.
     *
     * @var string
     */
    public const NOT_SPAM = 'not-spam';

    /**
     * Indicates any other feedback that does not fit into other registered types.
     *
     * @var string
     */
    public const OTHER = 'other';

    /**
     * Reports that a virus is found in the originating message.
     *
     * @var string
     */
    public const VIRUS = 'virus';
}
