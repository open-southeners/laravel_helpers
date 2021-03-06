<?php

namespace OpenSoutheners\LaravelHelpers\Enums;

use BackedEnum;
use Exception;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;

/**
 * Check if class or object is a valid PHP enum.
 *
 * @param  class-string|object  $objectOrClass
 * @return bool
 */
function is_enum($objectOrClass)
{
    try {
        $classReflection = new ReflectionClass($objectOrClass);
        /** @phpstan-ignore-next-line */
    } catch (ReflectionException $e) {
        return false;
    }

    return $classReflection->isEnum();
}

/**
 * Check wether the enum is backed.
 *
 * @param  mixed  $object
 * @return bool
 */
function enum_is_backed($objectOrClass)
{
    if (! is_enum($objectOrClass)) {
        throw new Exception('Class or object is not a valid enum.');
    }

    return (new ReflectionEnum($objectOrClass))->isBacked();
}

/**
 * Check if enum class or object has a case.
 *
 * @param  class-string<object>|object  $objectOrClass
 * @param  string  $case
 * @return bool
 */
function has_case($objectOrClass, string $case)
{
    if (! is_enum($objectOrClass)) {
        throw new Exception('Class or object is not a valid enum.');
    }

    $enumReflection = new ReflectionEnum($objectOrClass);

    return $enumReflection->hasCase($case);
}

/**
 * Get enum class from object instance.
 *
 * @param  mixed  $object
 * @return \BackedEnum|\UnitEnum
 *
 * @throws \Exception
 */
function get_enum_class($object)
{
    if (! is_enum($object)) {
        throw new Exception('Object is not a valid enum.');
    }

    return (new ReflectionEnum($object))->getName();
}

/**
 * Convert enum class or object to array.
 *
 * @param  \BackedEnum|\UnitEnum|object  $objectOrClass
 * @return array
 *
 * @throws \Exception
 */
function enum_to_array($objectOrClass)
{
    $enumClass = is_object($objectOrClass) ? get_enum_class($objectOrClass) : $objectOrClass;

    if (! is_enum($enumClass)) {
        throw new Exception('Class or object is not a valid enum.');
    }

    $enumArr = [];

    foreach ($enumClass::cases() as $enumCase) {
        $enumCase instanceof BackedEnum
            ? $enumArr[$enumCase->name] = $enumCase->value
            : $enumArr[] = $enumCase->name;
    }

    return $enumArr;
}

/**
 * Returns array of enum case values, false otherwise.
 *
 * @param  mixed  $objectOrClass
 * @return false|array
 */
function enum_values($objectOrClass)
{
    if (! enum_is_backed($objectOrClass)) {
        return false;
    }

    return array_values(enum_to_array($objectOrClass));
}
