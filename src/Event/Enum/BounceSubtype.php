<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event\Enum;

use Consistence\Enum\Enum;

class BounceSubtype extends Enum
{
    /**
     * Amazon SES was unable to determine a specific bounce reason.
     *
     * @var string
     */
    public const UNDETERMINED = 'Undetermined';

    /**
     * @var string
     */
    public const GENERAL = 'General';

    /**
     * Amazon SES received a permanent hard bounce because the target email address does not exist.
     * If you receive this type of bounce, you should remove the recipient's email address from your mailing list.
     *
     * @var string
     */
    public const NOEMAIL = 'NoEmail';

    /**
     * Amazon SES has suppressed sending to this address because it has a recent history of bouncing
     * as an invalid address. For information about how to remove an address from the suppression list,
     * see Using the Amazon SES Global Suppression List.
     *
     * @var string
     */
    public const SUPPRESSED = 'Suppressed';

    /**
     * Amazon SES has suppressed sending to this address because it is on the account-level suppression list.
     *
     * @var string
     */
    public const ONACCOUNTSUPPRESSIONLIST = 'OnAccountSuppressionList';

    /**
     * @var string
     */
    public const MAILBOXFULL = 'MailboxFull';

    /**
     * @var string
     */
    public const MESSAGETOOLARGE = 'MessageTooLarge';

    /**
     * @var string
     */
    public const CONTENTREJECTED = 'ContentRejected';

    /**
     * @var string
     */
    public const ATTACHMENTREJECTED = 'AttachmentRejected';
}
