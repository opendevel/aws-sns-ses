<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event\Enum;

use Consistence\Enum\Enum;

class BounceType extends Enum
{
    /**
     * You may be able to successfully resend to an address that initially resulted in a Transient bounce in the future.
     *
     * @var string
     */
    public const UNDETERMINED = 'Undetermined';

    /**
     * Remove the corresponding email addresses from your mailing list;
     * you will not be able to send to them in the future.
     *
     * @var string
     */
    public const PERMANENT = 'Permanent';

    /**
     * When a message has soft bounced several times, and Amazon SES has stopped trying to re-deliver it.
     *
     * @var string
     */
    public const TRANSIENT = 'Transient';
}
