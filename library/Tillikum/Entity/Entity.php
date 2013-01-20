<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity;

/**
 * Provides basic Doctrine entity functionality
 */
abstract class Entity
{
    protected static $propertyToMethodCache = array();

    /**
     * Overloading: 'get' internal data
     *
     * @param  string $property
     * @return mixed
     */
    public function __get($property)
    {
        $method = 'get' . $this->propertyToMethodName($property);

        if (is_callable(array($this, $method))) {
            return $this->{$method}();
        }

        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        return null;
    }

    /**
     * Overloading: test if internal data member is set
     *
     * @param  string $property
     * @return bool
     */
    public function __isset($property)
    {
        $method = 'isset' . $this->propertyToMethodName($property);

        if (is_callable(array($this, $method))) {
            return $this->{$method}();
        }

        if (property_exists($this, $property)) {
            return isset($this->{$property});
        }

        return false;
    }

    /**
     * Overloading: 'set' internal data
     *
     * @param  string $property
     * @param  mixed  $value
     * @return void
     */
    public function __set($property, $value)
    {
        $method = 'set' . $this->propertyToMethodName($property);

        if (is_callable(array($this, $method))) {
            $this->{$method}($value);

            return;
        }

        if (property_exists($this, $property)) {
            $this->{$property} = $value;

            return;
        }
    }

    /**
     * Overloading: unset internal data
     *
     * @param  string $property
     * @return void
     */
    public function __unset($property)
    {
        $method = 'unset' . $this->propertyToMethodName($property);

        if (is_callable(array($this, $method))) {
            $this->{$method}();

            return;
        }

        if (property_exists($this, $property)) {
            unset($this->{$property});

            return;
        }
    }

    /**
     * Convert the domain object into an array
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    protected function propertyToMethodName($property)
    {
        if (!array_key_exists($property, self::$propertyToMethodCache)) {
            $method = str_replace(
                ' ',
                '',
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $property
                    )
                )
            );

            self::$propertyToMethodCache[$property] = $method;
        }

        return self::$propertyToMethodCache[$property];
    }
}
