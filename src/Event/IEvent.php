<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

interface IEvent
{

    /**
     * @return mixed[]
     */
    public function getPayload(): array;
}
