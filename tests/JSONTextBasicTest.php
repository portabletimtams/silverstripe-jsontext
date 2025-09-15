<?php

/**
 * @package silverstripe-jsontext
 * @subpackage fields
 * @author Russell Michell <russ@theruss.com>
 * @todo Add 'object' fixture to each
 */

use PhpTek\JSONText\ORM\FieldType\JSONText;
use SilverStripe\Dev\SapphireTest;

class JSONTextBasicTest extends SapphireTest
{
    /**
     * @var array
     */
    protected $fixtures = [
        'array'     => __DIR__ . '/fixtures/json/array.json',
        'object'    => __DIR__ . '/fixtures/json/object.json'
    ];

    /**
     * @var \JSONText\ORM\FieldType\JSONText
     */
    protected $sut;

    /**
     * Setup the System Under Test for this test suite.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = JSONText::create('MyJSON');
    }

    public function testGetValueAsJSONStore()
    {
        $field = $this->sut;

        $field->setValue('');
        $this->assertEquals([], $field->getStoreAsArray());
    }

    public function testFirst()
    {
        $field = $this->sut;

        // Test: Source data is simple JSON array
        // Return type: Array
        $field->setReturnType('array');
        $field->setValue($this->getFixture('array'));
        $this->assertIsArray($field->first());
        $this->assertCount(1, $field->first());
        $this->assertEquals([0 => 'great wall'], $field->first());

        // Test: Source data is simple JSON array
        // Return type: JSON
        $field->setReturnType('json');
        $field->setValue($this->getFixture('array'));
        $this->assertIsString($field->first());
        $this->assertEquals('["great wall"]', $field->first());

        // Test: Source data is simple JSON array
        // Return type: SilverStripe
        $field->setReturnType('silverstripe');
        $field->setValue($this->getFixture('array'));
        $this->assertIsArray($field->first());
        $this->assertInstanceOf('\SilverStripe\ORM\FieldType\DBVarchar', $field->first()[0]);
        $this->assertEquals('great wall', $field->first()[0]->getValue());

        // Test: Empty
        $field->setReturnType('array');
        $field->setValue('');
        $this->assertIsArray($field->first());
        $this->assertCount(0, $field->first());
    }

    public function testLast()
    {
        $field = $this->sut;

        // Test: Source data is simple JSON array
        // Return type: Array
        $field->setReturnType('array');
        $field->setValue($this->getFixture('array'));
        $this->assertIsArray($field->last());
        $this->assertCount(1, $field->last());
        $this->assertEquals([6 => 33.3333], $field->last());

        // Test: Source data is simple JSON array
        // Return type: JSON
        $field->setReturnType('json');
        $field->setValue($this->getFixture('array'));
        $this->assertIsString($field->last());
        $this->assertEquals('{"6":33.3333}', $field->last());

        // Test: Source data is simple JSON array
        // Return type: SilverStripe
        $field->setReturnType('silverstripe');
        $field->setValue($this->getFixture('array'));
        $this->assertIsArray($field->last());
        $this->assertInstanceOf('\SilverStripe\ORM\FieldType\DBFloat', $field->last()[6]);
        $this->assertEquals(33.3333, $field->last()[6]->getValue());

        // Test: Empty
        $field->setReturnType('array');
        $field->setValue('');
        $this->assertIsArray($field->last());
        $this->assertCount(0, $field->last());
    }

    public function testNth()
    {
        $field = $this->sut;

        // Test: Source data is simple JSON array
        // Return type: Array
        $field->setReturnType('array');
        $field->setValue($this->getFixture('array'));
        $this->assertIsArray($field->nth(1));
        $this->assertCount(1, $field->nth(1));
        $this->assertEquals([1 => true], $field->nth(1));

        // Test: Source data is simple JSON array
        // Return type: JSON
        $field->setReturnType('json');
        $field->setValue($this->getFixture('array'));
        $this->assertIsString($field->nth(1));
        $this->assertEquals('{"1":true}', $field->nth(1));

        // Test: Source data is simple JSON array
        // Return type: SilverStripe
        $field->setReturnType('silverstripe');
        $field->setValue($this->getFixture('array'));
        $this->assertIsArray($field->nth(1));
        $this->assertInstanceOf('\SilverStripe\ORM\FieldType\DBBoolean', $field->nth(1)[1]);
        $this->assertEquals(true, $field->nth(1)[1]->getValue());

        // Test: Empty
        $field->setReturnType('array');
        $field->setValue('');
        $this->assertIsArray($field->nth(1));
        $this->assertCount(0, $field->nth(1));
    }

    /**
     * Get the contents of a fixture
     *
     * @param string $fixture
     * @return string
     */
    private function getFixture($fixture)
    {
        $files = $this->fixtures;

        return file_get_contents($files[$fixture]);
    }
}
