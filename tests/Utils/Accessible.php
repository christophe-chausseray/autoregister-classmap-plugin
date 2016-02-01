<?php

namespace Utils;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class Accessible
 */
abstract class Accessible
{
    /**
     * Gets object's property value even if it isn't accessible.
     *
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     */
    public static function getPropertyValue($object, $propertyName)
    {
        $property = static::setAccessibleOnProperty(get_class($object), $propertyName);

        return $property->getValue($object);
    }

    /**
     * Gets class' static property value even if it isn't accessible.
     *
     * @param string $className
     * @param string $propertyName
     *
     * @return mixed
     */
    public static function getStaticPropertyValue($className, $propertyName)
    {
        $property = static::setAccessibleOnProperty($className, $propertyName);

        return $property->getValue();
    }

    /**
     * Sets object's property value even if it isn't accessible.
     *
     * @param object $object
     * @param string $propertyName
     * @param mixed  $value
     */
    public static function setPropertyValue($object, $propertyName, $value)
    {
        $property = static::setAccessibleOnProperty(get_class($object), $propertyName);

        $property->setValue($object, $value);
    }

    /**
     * Sets class' static property value even if it isn't accessible.
     *
     * @param string $className
     * @param string $propertyName
     * @param mixed  $value
     */
    public static function setStaticPropertyValue($className, $propertyName, $value)
    {
        $property = static::setAccessibleOnProperty($className, $propertyName);

        $property->setValue($value);
    }

    /**
     * Invokes an object's method with its arguments even if isn't accessible.
     *
     * @param object $object
     * @param string $methodName
     * @param array  $methodArguments
     *
     * @return mixed
     */
    public static function invokeMethod($object, $methodName, array $methodArguments = [])
    {
        $method = static::setAccessibleOnMethod(get_class($object), $methodName);

        return $method->invokeArgs($object, $methodArguments);
    }

    /**
     * Invokes an class' static method with its arguments even if isn't accessible.
     *
     * @param string $className
     * @param string $methodName
     * @param array  $methodArguments
     *
     * @return mixed
     */
    public static function invokeStaticMethod($className, $methodName, array $methodArguments = [])
    {
        $method = static::setAccessibleOnMethod($className, $methodName);

        return $method->invokeArgs($className, $methodArguments);
    }

    /**
     * Set accessible on the method
     *
     * @param string $className
     * @param string $methodName
     *
     * @return ReflectionMethod
     */
    protected static function setAccessibleOnMethod($className, $methodName)
    {
        $objectReflection = new ReflectionClass($className);
        $method           = $objectReflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Set accessible on the property
     *
     * @param string $className
     * @param string $propertyName
     *
     * @return ReflectionProperty
     */
    protected static function setAccessibleOnProperty($className, $propertyName)
    {
        $objectReflection = new ReflectionClass($className);
        $property         = $objectReflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }
}
