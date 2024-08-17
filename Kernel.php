<?php

declare(strict_types=1);

namespace SpecDoc\Specificator;

use SpecDoc\Specificator\Contracts\Specification\SpecificationInterface;

use function in_array;

/**
 * Implements document management methods in accordance with their specifications.
 */
final class Kernel
{
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
     */
    public function load(string $source): self
    {
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
     * @param SpecificationInterface $specification
     *
     * @return self
     */
    public function addSpecification(SpecificationInterface $specification): self
    {
        if (!in_array($specification, $this->specifications, true)) {
            $this->specifications[] = $specification;
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
