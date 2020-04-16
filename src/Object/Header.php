<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Object;

use SmartEmailing\Types\PrimitiveTypes;

//@todo unittests

/**
 * Email's original header
 */
class Header
{

    /**
     * @var string
     */
    private $name;
/**
     * @var mixed|mixed[]|null  //@todo check possible header value types
     */
    private $value = null;
/**
     * @param string $name
     * @param mixed|mixed[]|null $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param string[] $data
     * @return Header
     */
    public static function fromArray(array $data): self
    {
        return new self(PrimitiveTypes::extractString($data, 'name'), PrimitiveTypes::extractStringOrNull($data, 'value'));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed|mixed[]|null
     */
    public function getValue()
    {
        return $this->value;
    }
}
