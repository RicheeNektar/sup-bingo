<?php

namespace App\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Psr\Log\LoggerInterface;

class AbstractEnumType extends Type
{
    public const NAME = '';
    protected string $enumClass;

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $values = call_user_func("$this->enumClass::cases");
        $values = array_map(fn ($val) => "'$val->value'", $values);
        return sprintf('ENUM (%s)', implode(',', $values));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (is_string($value)) {
            return $value;
        }
        return $value->value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        try {
            return call_user_func("$this->enumClass::from", $value);
        } catch (\Throwable $exception) {
            throw new \RuntimeException(json_encode(debug_backtrace()));
        }
    }
}