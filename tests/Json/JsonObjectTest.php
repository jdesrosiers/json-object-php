<?php

namespace JDesrosiers\Tests\Json;

use JDesrosiers\Json\JsonObject;

require __DIR__ . "/../../vendor/autoload.php";

class JsonObjectTest extends \PHPUnit_Framework_TestCase
{
    public function dataProviderSetProperty()
    {
        return array(
            array("object", "value"),
        );
    }

    /**
     * @dataProvider dataProviderSetProperty
     */
    public function testSetProperty($schema, $value)
    {
        $json = new JsonObject(new \stdClass(), "file://" . realpath(__DIR__ . "/../schemas/$schema.json"));
        $json->key = $value;

        $this->assertInstanceOf("JDesrosiers\Json\JsonObject", $json->key);
        $this->assertEquals($value, $json->key->getValue());
    }

    public function dataProviderSetPropertyFail()
    {
        return array(
            array("object", 3),
            array("object", new \stdClass()),
            array("object", array()),
            array("object", 2.5),
//            array("object", null),
        );
    }

    /**
     * @dataProvider dataProviderSetPropertyFail
     * @expectedException InvalidArgumentException
     */
    public function testSetPropertyFail($schema, $value)
    {
        $json = new JsonObject(new \stdClass(), "file://" . realpath(__DIR__ . "/../schemas/$schema.json"));
        $json->key = $value;
    }

    public function dataProviderAdditionalProperties()
    {
        return array(
            array("object", "value"),
            array("object", 3),
            array("object", array()),
            array("object", new \stdClass()),
            array("object", 3.4),
            array("object", null),
            array("objectAddPropString", "value"),
        );
    }

    /**
     * @dataProvider dataProviderAdditionalProperties
     */
    public function testSetAdditionalProperties($schema, $value)
    {
        $json = new JsonObject(new \stdClass(), "file://" . realpath(__DIR__ . "/../schemas/$schema.json"));
        $json->key2 = $value;

        $this->assertInstanceOf("JDesrosiers\Json\JsonObject", $json->key2);
        $this->assertEquals($value, $json->key2->getValue());
    }

    public function dataProviderAdditionalPropertiesFail()
    {
        return array(
            array("objectAddPropFalse", "value"),
            array("objectAddPropFalse", 3),
            array("objectAddPropFalse", 3.5),
            array("objectAddPropFalse", array()),
            array("objectAddPropFalse", new \stdClass()),
            array("objectAddPropFalse", null),
            array("objectAddPropString", 3),
            array("objectAddPropString", 3.5),
            array("objectAddPropString", array()),
            array("objectAddPropString", new \stdClass()),
            array("objectAddPropString", null),
        );
    }

    /**
     * @dataProvider dataProviderAdditionalPropertiesFail
     * @expectedException InvalidArgumentException
     */
    public function testSetAdditionalPropertiesFail($schema, $value)
    {
        $json = new JsonObject(new \stdClass(), "file://" . realpath(__DIR__ . "/../schemas/$schema.json"));
        $json->key2 = $value;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testObjectArrayAccess()
    {
        $schema = "object";
        $data = new \stdClass();
        $data->key = "value";

        $json = new JsonObject($data, "file://" . realpath(__DIR__ . "/../schemas/$schema.json"));
        $json["key"];
    }

    public function testSetArray()
    {
        $schema = "array";
        $value = "value";

        $json = new JsonObject(array(), "file://" . realpath(__DIR__ . "/../schemas/$schema.json"));
        $json[0] = $value;

        $this->assertInstanceOf("JDesrosiers\Json\JsonObject", $json[0]);
        $this->assertEquals($value, $json[0]->getValue());
    }

    public function testAppendArray()
    {
        $schema = "array";
        $value = "value";

        $json = new JsonObject(array(), "file://" . realpath(__DIR__ . "/../schemas/$schema.json"));
        $json[] = $value;

        $this->assertInstanceOf("JDesrosiers\Json\JsonObject", $json[0]);
        $this->assertEquals($value, $json[0]->getValue());
    }
}
