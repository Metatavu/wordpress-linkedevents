<?php
/**
 * Keyword
 *
 * PHP version 5
 *
 * @category Class
 * @package  Metatavu\LinkedEvents
 * @author   Swaagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Linked Events information API
 *
 * Linked Events provides categorized data on events and places using JSON-LD format.  Events can be searched by date and location. Location can be exact address or larger area such as neighbourhood or borough  JSON-LD format is streamlined using include mechanism. API users can request that certain fields are included directly into the result, instead of being hyperlinks to objects.  Several fields are multilingual. These are implemented as object with each language variant as property. In this specification each multilingual field has (fi,sv,en) property triplet as example.
 *
 * OpenAPI spec version: v1
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Metatavu\LinkedEvents\Model;

use \ArrayAccess;

/**
 * Keyword Class Doc Comment
 *
 * @category    Class
 * @description Keywords are used to describe events. Linked events uses namespaced keywords in order to support having events from different sources. Namespaces are needed because keywords are defined by the organization sourcing the events and can therefore overlap in meaning. Conversely the meaning of same keyword can vary between organizations. Organization sourcing the keyword can be identified by data_source field. Data_source field will later specify standardized namespaces as well.
 * @package     Metatavu\LinkedEvents
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Keyword implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'keyword';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'id' => 'string',
        'name' => '\Metatavu\LinkedEvents\Model\KeywordName',
        'images' => '\Metatavu\LinkedEvents\Model\Image[]',
        'originId' => 'string',
        'createdTime' => '\DateTime',
        'lastModifiedTime' => '\DateTime',
        'aggregate' => 'bool',
        'dataSource' => 'string',
        'createdBy' => 'string',
        'lastModifiedBy' => 'string',
        'altLabels' => 'string[]'
    ];

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'id' => 'id',
        'name' => 'name',
        'images' => 'images',
        'originId' => 'origin_id',
        'createdTime' => 'created_time',
        'lastModifiedTime' => 'last_modified_time',
        'aggregate' => 'aggregate',
        'dataSource' => 'data_source',
        'createdBy' => 'created_by',
        'lastModifiedBy' => 'last_modified_by',
        'altLabels' => 'alt_labels'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'id' => 'setId',
        'name' => 'setName',
        'images' => 'setImages',
        'originId' => 'setOriginId',
        'createdTime' => 'setCreatedTime',
        'lastModifiedTime' => 'setLastModifiedTime',
        'aggregate' => 'setAggregate',
        'dataSource' => 'setDataSource',
        'createdBy' => 'setCreatedBy',
        'lastModifiedBy' => 'setLastModifiedBy',
        'altLabels' => 'setAltLabels'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'id' => 'getId',
        'name' => 'getName',
        'images' => 'getImages',
        'originId' => 'getOriginId',
        'createdTime' => 'getCreatedTime',
        'lastModifiedTime' => 'getLastModifiedTime',
        'aggregate' => 'getAggregate',
        'dataSource' => 'getDataSource',
        'createdBy' => 'getCreatedBy',
        'lastModifiedBy' => 'getLastModifiedBy',
        'altLabels' => 'getAltLabels'
    ];

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    public static function setters()
    {
        return self::$setters;
    }

    public static function getters()
    {
        return self::$getters;
    }

    

    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['name'] = isset($data['name']) ? $data['name'] : null;
        $this->container['images'] = isset($data['images']) ? $data['images'] : null;
        $this->container['originId'] = isset($data['originId']) ? $data['originId'] : null;
        $this->container['createdTime'] = isset($data['createdTime']) ? $data['createdTime'] : null;
        $this->container['lastModifiedTime'] = isset($data['lastModifiedTime']) ? $data['lastModifiedTime'] : null;
        $this->container['aggregate'] = isset($data['aggregate']) ? $data['aggregate'] : null;
        $this->container['dataSource'] = isset($data['dataSource']) ? $data['dataSource'] : null;
        $this->container['createdBy'] = isset($data['createdBy']) ? $data['createdBy'] : null;
        $this->container['lastModifiedBy'] = isset($data['lastModifiedBy']) ? $data['lastModifiedBy'] : null;
        $this->container['altLabels'] = isset($data['altLabels']) ? $data['altLabels'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

        if ($this->container['id'] === null) {
            $invalid_properties[] = "'id' can't be null";
        }
        if ($this->container['dataSource'] === null) {
            $invalid_properties[] = "'dataSource' can't be null";
        }
        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        if ($this->container['id'] === null) {
            return false;
        }
        if ($this->container['dataSource'] === null) {
            return false;
        }
        return true;
    }


    /**
     * Gets id
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     * @param string $id Consists of source prefix and source specific identifier. These should be URIs uniquely identifying the keyword, and preferably also well formed http-URLs pointing to more information about the keyword.
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets name
     * @return \Metatavu\LinkedEvents\Model\KeywordName
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     * @param \Metatavu\LinkedEvents\Model\KeywordName $name
     * @return $this
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets images
     * @return \Metatavu\LinkedEvents\Model\Image[]
     */
    public function getImages()
    {
        return $this->container['images'];
    }

    /**
     * Sets images
     * @param \Metatavu\LinkedEvents\Model\Image[] $images
     * @return $this
     */
    public function setImages($images)
    {
        $this->container['images'] = $images;

        return $this;
    }

    /**
     * Gets originId
     * @return string
     */
    public function getOriginId()
    {
        return $this->container['originId'];
    }

    /**
     * Sets originId
     * @param string $originId Identifier for the keyword in the organization using this keyword. For standardized namespaces this will be a shared identifier.
     * @return $this
     */
    public function setOriginId($originId)
    {
        $this->container['originId'] = $originId;

        return $this;
    }

    /**
     * Gets createdTime
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->container['createdTime'];
    }

    /**
     * Sets createdTime
     * @param \DateTime $createdTime Creation time for the keyword entry.
     * @return $this
     */
    public function setCreatedTime($createdTime)
    {
        $this->container['createdTime'] = $createdTime;

        return $this;
    }

    /**
     * Gets lastModifiedTime
     * @return \DateTime
     */
    public function getLastModifiedTime()
    {
        return $this->container['lastModifiedTime'];
    }

    /**
     * Sets lastModifiedTime
     * @param \DateTime $lastModifiedTime Time this place was modified in the datastore behind the API (not necessarily in the originating system)
     * @return $this
     */
    public function setLastModifiedTime($lastModifiedTime)
    {
        $this->container['lastModifiedTime'] = $lastModifiedTime;

        return $this;
    }

    /**
     * Gets aggregate
     * @return bool
     */
    public function getAggregate()
    {
        return $this->container['aggregate'];
    }

    /**
     * Sets aggregate
     * @param bool $aggregate FIXME(verify) This keyword is an combination of several keywords at source
     * @return $this
     */
    public function setAggregate($aggregate)
    {
        $this->container['aggregate'] = $aggregate;

        return $this;
    }

    /**
     * Gets dataSource
     * @return string
     */
    public function getDataSource()
    {
        return $this->container['dataSource'];
    }

    /**
     * Sets dataSource
     * @param string $dataSource Source of the keyword, typically API provider specific identifier. Will also be used to specify standardized namespaces as they are brought into use.
     * @return $this
     */
    public function setDataSource($dataSource)
    {
        $this->container['dataSource'] = $dataSource;

        return $this;
    }

    /**
     * Gets createdBy
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->container['createdBy'];
    }

    /**
     * Sets createdBy
     * @param string $createdBy FIXME(verify) URL reference to the user that created this record (user endpoint)
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->container['createdBy'] = $createdBy;

        return $this;
    }

    /**
     * Gets lastModifiedBy
     * @return string
     */
    public function getLastModifiedBy()
    {
        return $this->container['lastModifiedBy'];
    }

    /**
     * Sets lastModifiedBy
     * @param string $lastModifiedBy FIXME(verify) URL reference to the user that last modfied this record (user endpoint)
     * @return $this
     */
    public function setLastModifiedBy($lastModifiedBy)
    {
        $this->container['lastModifiedBy'] = $lastModifiedBy;

        return $this;
    }

    /**
     * Gets altLabels
     * @return string[]
     */
    public function getAltLabels()
    {
        return $this->container['altLabels'];
    }

    /**
     * Sets altLabels
     * @param string[] $altLabels FIXME(verify) alternative labels for this keyword, no language specified. Use case?
     * @return $this
     */
    public function setAltLabels($altLabels)
    {
        $this->container['altLabels'] = $altLabels;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\Metatavu\LinkedEvents\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Metatavu\LinkedEvents\ObjectSerializer::sanitizeForSerialization($this));
    }
}


