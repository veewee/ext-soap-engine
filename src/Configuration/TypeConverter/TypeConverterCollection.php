<?php declare(strict_types=1);

namespace Soap\ExtSoapEngine\Configuration\TypeConverter;

use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<array-key, TypeConverterInterface>
 */
final class TypeConverterCollection implements IteratorAggregate
{
    /**
     * @var array<TypeConverterInterface>
     */
    private array $converters = [];

    /**
     * Construct type converter collection
     *
     * @param array $converters (optional) Array of type converters
     */
    public function __construct(array $converters = [])
    {
        foreach ($converters as $converter) {
            $this->add($converter);
        }
    }


    private function serialize(TypeConverterInterface $converter): string
    {
        return $converter->getTypeNamespace() . ':' . $converter->getTypeName();
    }

    /**
     * Add a type converter to the collection
     *
     * @param TypeConverterInterface $converter Type converter
     *
     */
    public function add(TypeConverterInterface $converter): self
    {
        if ($this->has($converter)) {
            throw new InvalidArgumentException(
                'Converter for this type already exists'
            );
        }

        return $this->set($converter);
    }

    /**
     * Set (overwrite) a type converter in the collection
     *
     * @param TypeConverterInterface $converter Type converter
     *
     */
    public function set(TypeConverterInterface $converter): self
    {
        $serialized = $this->serialize($converter);
        $this->converters[$serialized] = $converter;

        return $this;
    }

    /**
     * Returns true if the collection contains a type converter for a certain
     * namespace and name
     *
     *
     */
    public function has(TypeConverterInterface $converter): bool
    {
        $serialized = $this->serialize($converter);
        if (isset($this->converters[$serialized])) {
            return true;
        }

        return false;
    }

    /**
     * @return ArrayIterator<array-key, TypeConverterInterface>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->converters);
    }
}
