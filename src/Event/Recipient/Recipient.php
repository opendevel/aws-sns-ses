<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event\Recipient;

use SmartEmailing\Types\PrimitiveTypes;

//@todo unittests

class Recipient
{
    /**
     * @var string
     */
    private $emailAddress;

    /**
     * @param string $emailAddress
     */
    public function __construct(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @param mixed[] $data
     * @return Recipient
     */
    public static function fromArray(array $data): self
    {
        return new self(
            PrimitiveTypes::extractString($data, 'emailAddress')
        );
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}
