<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event\Recipient;

use SmartEmailing\Types\PrimitiveTypes;

//@todo unittests

/**
 * Recipient whose email address produced a bounce
 */
class BouncedRecipient
{
    /**
     * @var string
     */
    public $emailAddress;

    /**
     * @var string|null
     */
    private $action = null;

    /**
     * @var string|null
     */
    private $status = null;

    /**
     * @var string|null
     */
    private $diagnosticCode = null;

    /**
     * @param string $emailAddress
     * @param string|null $action
     * @param string|null $status
     * @param string|null $diagnosticCode
     */
    public function __construct(
        string $emailAddress,
        ?string $action,
        ?string $status,
        ?string $diagnosticCode
    ) {
        $this->emailAddress = $emailAddress;
        $this->action = $action;
        $this->status = $status;
        $this->diagnosticCode = $diagnosticCode;
    }

    /**
     * @param mixed[] $data
     * @return BouncedRecipient
     */
    public static function fromArray(array $data): self
    {
        return new self(
            PrimitiveTypes::extractString($data, 'emailAddress'),
            PrimitiveTypes::extractStringOrNull($data, 'action'),
            PrimitiveTypes::extractStringOrNull($data, 'status'),
            PrimitiveTypes::extractStringOrNull($data, 'diagnosticCode')
        );
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getDiagnosticCode(): ?string
    {
        return $this->diagnosticCode;
    }
}
