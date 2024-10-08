<?php

namespace SpecDoc\Specificator\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use SpecDoc\Specificator\Kernel;
use SpecDoc\Specificator\Exception\CouldNotBeLoadedException;
use SpecDoc\Specificator\Contracts\Specification\SpecificationInterface;
use SpecDoc\Specificator\Tests\Fixtures\FirstEmptySpecification;
use SpecDoc\Specificator\Tests\Fixtures\SecondEmptySpecification;

class KernelTest extends TestCase
{
    /**
     * @var Kernel|null setUp/tearDown use
     */
    private ?Kernel $kernel = null;

    /**
     * @dataProvider contentProvider
     */
    public function testLoad($source)
    {
        $kernel = clone $this->kernel;
        $this->assertNotEquals($this->kernel->load($source), $kernel);
    }

    public function testCouldNotBeLoadException()
    {
        $this->expectException(CouldNotBeLoadedException::class);
        $this->kernel->load(__DIR__ . '/TestData/WithoutContent');
    }

    public function testGetSpecifications()
    {
        $this->assertIsArray($this->kernel->getSpecifications());
        $this->assertEmpty($this->kernel->getSpecifications());
    }

    public function testSetSpecification()
    {
        $specification = $this->createMock(SpecificationInterface::class);

        $this->assertInstanceOf(Kernel::class, $this->kernel->setSpecification($specification));
        $this->assertInstanceOf(SpecificationInterface::class, $this->kernel->getSpecification());
    }

    public function testGetSpecification()
    {
        $specification = $this->createMock(SpecificationInterface::class);
        $this->kernel->setSpecification($specification);

        $this->assertInstanceOf(SpecificationInterface::class, $this->kernel->getSpecification());
    }

    public function testGetNullSpecification()
    {
        $this->assertNull($this->kernel->getSpecification());
    }

    /**
     * @dataProvider specificationProvider
     */
    public function testAddNewSpecification($specification, int $count)
    {
        $this->kernel->addSpecification($specification);

        $this->assertCount($count, $this->kernel->getSpecifications());
    }

    public function testAddExistSpecification()
    {
        $specification = $this->createMock(SpecificationInterface::class);
        $this->kernel->addSpecification($specification);
        $this->kernel->addSpecification($specification);

        $this->assertCount(1, $this->kernel->getSpecifications());
    }

    protected function setUp(): void
    {
        $this->kernel = new Kernel();
    }

    protected function tearDown(): void
    {
        $this->kernel = null;
    }

    public static function contentProvider(): iterable
    {
        return [
            'source file' => [__DIR__ . '/TestData/WithContent'],
            'source string' => ['string']
        ];
    }

    public static function specificationProvider(): iterable
    {
        return [
            'object test' => [
                new FirstEmptySpecification(),
                1
            ],
            'string test' => [
                SecondEmptySpecification::class,
                1
            ],
            'array test' => [
                [
                    new FirstEmptySpecification(),
                    SecondEmptySpecification::class
                ],
                2
            ]
        ];
    }
}
