<?php

declare(strict_types=1);

namespace SpecDoc\Specificator;

use SpecDoc\Specificator\Contracts\Specification\SpecificationInterface;
use SpecDoc\Specificator\Exception\CouldNotBeLoadedException;

use function file_get_contents;
use function in_array;
use function is_array;
use function is_file;
use function is_subclass_of;
use function is_string;
use function mb_strlen;
use function sprintf;

/**
 * Implements document management methods in accordance with their specifications.
 */
final class Kernel
{
    /**
     * @var string|null
     */
    private ?string $content = null;

    /**
     * @var array Available specifications
     */
    private array $specifications = [];

    /**
     * @var SpecificationInterface|null Current specification
     */
    private ?SpecificationInterface $specification = null;

    /**
     * The method of loading a source into the kernel for subsequent processing.
     *
     * @param string $source Source for processing
     *
     * @return self
     *
     * @throws CouldNotBeLoadedException
     */

    public function load(string $source): self
    {
        try {
            $content = is_file($source) ? file_get_contents($source) : $source;
            0 !== mb_strlen($content)
                ? $this->content = $content
                : throw new CouldNotBeLoadedException('The source should not be empty');
        } catch (\Exception) {
            throw new CouldNotBeLoadedException(sprintf('The %s source could not be loaded', $source));
        }

        return $this;
    }

    /**
     * Returns list of available specifications.
     *
     * @return array
     */
    public function getSpecifications(): array
    {
        return $this->specifications;
    }

    /**
     * Adds the specification to the list of available.
     *
     * @param SpecificationInterface|string|array $specification
     *
     * @return self
     */
    public function addSpecification(SpecificationInterface|string|array $specification): self
    {
        if (is_subclass_of($specification, SpecificationInterface::class)) {
            in_array($specification, $this->specifications) ?: $this->specifications[] = $specification;
        } elseif (is_array($specification)) {
            foreach ($specification as $specValue) {
                $this->addSpecification($specValue);
            }
        } elseif (is_string($specification)) {
            $object = new $specification();
            $this->addSpecification($object);
        }

        return $this;
    }

    /**
     * Returns the current specification.
     *
     * @return SpecificationInterface|null
     */
    public function getSpecification(): ?SpecificationInterface
    {
        return $this->specification;
    }

    /**
     * Sets a specific specification to work.
     *
     * @param SpecificationInterface $specification
     *
     * @return self
     */
    public function setSpecification(SpecificationInterface $specification): self
    {
        $this->specification = $specification;

        return $this;
    }
}
