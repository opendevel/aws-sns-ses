<?php

declare(strict_types=1);

namespace Opendevel\Aws\SnsSes\Object;

use Consistence\Type\ObjectMixinTrait;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use SmartEmailing\Types\Domain;
use SmartEmailing\Types\ExtractableTraits\StringExtractableTrait;
use SmartEmailing\Types\InvalidEmailaddressException;
use SmartEmailing\Types\InvalidTypeException;
use SmartEmailing\Types\ToStringTrait;

//@todo move to another library
//@todo refactor
//@todo make tests

class EmailAddress
{
    /**
     * person@place.com
     * person <person@place.com>
     * person
     * Another Person <person@place.com>
     * 'Another Person' <person@place.com>
     * "Another Person" <person@place.com>
     */
    use ObjectMixinTrait;
    use StringExtractableTrait;
    use ToStringTrait;

    /**
     * Email address name
     * @var string|null
     */
    private $name = null;

    /**
     * @var string
     */
    private $value;
    /**
     * @var string
     */
    private $localPart;
    /**
     * @var \SmartEmailing\Types\Domain
     */
    private $domain;

    public function __construct(string $value)
    {
        try {
            $ok = $this->initialize($value);
        } catch (InvalidTypeException $e) {
            $ok = false;
        }

        if (!$ok) {
            throw new InvalidEmailaddressException('Invalid emailaddress: ' . $value);
        }
    }

    public static function preprocessEmailaddress(string $emailaddress): string
    {
        $sanitized = Strings::lower(Strings::toAscii(Strings::trim($emailaddress)));
        return \strtr(
            $sanitized,
            [
                '>' => '',
                '<' => '',
            ]
        );
    }

    public function getLocalPart(): string
    {
        return $this->localPart;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getFullAddress(): string
    {
        return !empty($this->name)
            ? $this->name . ' <' . $this->value . '>'
            : $this->value;
    }

    private function initialize(string $value): bool
    {
        $emailSplit = $this->parseAddress($value);

        $this->name = $emailSplit['name'];

        $emailaddress = self::preprocessEmailaddress($emailSplit['email']);
        if (
            Strings::startsWith($emailaddress, '-')
            || !Strings::contains($emailaddress, '@')
            || Strings::contains($emailaddress, '"')
            || Strings::contains($emailaddress, ' ')
            || !Validators::isEmail($emailaddress)
        ) {
            return false;
        }

        $exploded = \explode('@', $emailaddress);
        [$this->localPart, $domain] = $exploded;
        $this->domain = Domain::from($domain);
        $this->value = $emailaddress;
        return true;
    }

    /**
     * @param string $address
     * @return string[]
     */
    private function parseAddress(string $address): array
    {
        preg_match(
            '/([\w\s\'\"]+[\s]+)?(<)?(([\w\-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/',
            $address . " ",
            $matches
        );
        return [
            'name' => trim((isset($matches[1])) ? $matches[1] : ''),
            'email' => trim((isset($matches[3])) ? $matches[3] : '')
        ];
    }
}
