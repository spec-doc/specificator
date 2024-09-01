<?php

namespace SpecDoc\Specificator\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use SpecDoc\Specificator\Kernel;
use SpecDoc\Specificator\Exception\CouldNotBeLoadedException;
use SpecDoc\Specificator\Contracts\Specification\SpecificationInterface;

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
        $this->assertInstanceOf(Kernel::class, $this->kernel->load($source));
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

    public function testAddNewSpecification()
    {
        $specification = $this->createMock(SpecificationInterface::class);
        $this->kernel->addSpecification($specification);

        $this->assertCount(1, $this->kernel->getSpecifications());
    }

    public function testAddExistSpecification()
    {
        $specification = $this->createMock(SpecificationInterface::class);
        $this->kernel->addSpecification($specification);
        $this->kernel->addSpecification($specification);

        $this->assertCount(1, $this->kernel->getSpecifications());
    }

    public function testUp()
    {
        $this->markTestIncomplete();
    }

    public function testUpTo()
    {
        $this->markTestIncomplete();
    }

    public function testDown()
    {
        $this->markTestIncomplete();
    }

    public function testDownTo()
    {
        $this->markTestIncomplete();
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
}
