<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Event;

use SmartEmailing\Types\PrimitiveTypes;

/**
 * Information about a Rendering Failure event
 */
final class FailureEvent implements IEvent
{

    /**
     * The name of the template used to send the email
     *
     * @var string
     */
    private $templateName;

    /**
     * A message that provides more information about the rendering failure
     *
     * @var string
     */
    private $errorMessage;

    /**
     * @var mixed[]
     */
    private $payload = [];

    /**
     * @param string $templateName
     * @param string $errorMessage
     */
    private function __construct(
        string $templateName,
        string $errorMessage
    ) {
        $this->templateName = $templateName;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param mixed[] $data
     * @return FailureEvent
     */
    public static function fromArray(array $data): self
    {
        $self = new self(
            PrimitiveTypes::extractString($data, 'templateName'),
            PrimitiveTypes::extractString($data, 'errorMessage')
        );

        $self->payload = $data;

        return $self;
    }

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
