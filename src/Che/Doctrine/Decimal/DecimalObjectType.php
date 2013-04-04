<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Che\Doctrine\Decimal;

use Che\Math\Decimal\BigDecimal;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Description of DecimalType
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class DecimalObjectType extends Type
{
    const DECIMAL_OBJECT = 'decimal_object';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::DECIMAL_OBJECT;
    }

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDecimalTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof BigDecimal) {
            throw new DBALException('Value is not an instance of "BigDecimal"');
        }

        return $value->value();
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        try {
            return new BigDecimal($value);
        } catch (\Exception $e) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), BigDecimal::STRING_FORMAT_REGEX);
        }
    }
}
