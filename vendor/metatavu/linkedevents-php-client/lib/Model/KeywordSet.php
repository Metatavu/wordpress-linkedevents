<?php
/**
 * KeywordSet
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
 * KeywordSet Class Doc Comment
 *
 * @category    Class
 * @description Keyword sets are used to group keywords together into classification groups. For example, one set of keywords might describe themes used by an event provider and another could be used to describe audience groups.
 * @package     Metatavu\LinkedEvents
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class KeywordSet implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'keyword_set';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'id' => 'string',
        'name' => 'string',
        'originId' => 'string',
        'createdTime' => '\DateTime',
        'lastModifiedTime' => '\DateTime',
        'dataSource' => 'string',
        'lastModifiedBy' => 'string',
        'usage' => 'string',
        'organization' => 'string',
        'keywords' => '\Metatavu\LinkedEvents\Model\Keyword[]'
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
        'originId' => 'origin_id',
        'createdTime' => 'created_time',
        'lastModifiedTime' => 'last_modified_time',
        'dataSource' => 'data_source',
        'lastModifiedBy' => 'last_modified_by',
        'usage' => 'usage',
        'organization' => 'organization',
        'keywords' => 'keywords'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'id' => 'setId',
        'name' => 'setName',
        'originId' => 'setOriginId',
        'createdTime' => 'setCreatedTime',
        'lastModifiedTime' => 'setLastModifiedTime',
        'dataSource' => 'setDataSource',
        'lastModifiedBy' => 'setLastModifiedBy',
        'usage' => 'setUsage',
        'organization' => 'setOrganization',
        'keywords' => 'setKeywords'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'id' => 'getId',
        'name' => 'getName',
        'originId' => 'getOriginId',
        'createdTime' => 'getCreatedTime',
        'lastModifiedTime' => 'getLastModifiedTime',
        'dataSource' => 'getDataSource',
        'lastModifiedBy' => 'getLastModifiedBy',
        'usage' => 'getUsage',
        'organization' => 'getOrganization',
        'keywords' => 'getKeywords'
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

    const USAGE_ANY = 'any';
    const USAGE_KEYWORD = 'keyword';
    const USAGE_AUDIENCE = 'audience';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getUsageAllowableValues()
    {
        return [
            self::USAGE_ANY,
            self::USAGE_KEYWORD,
            self::USAGE_AUDIENCE,
        ];
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
        $this->container['originId'] = isset($data['originId']) ? $data['originId'] : null;
        $this->container['createdTime'] = isset($data['createdTime']) ? $data['createdTime'] : null;
        $this->container['lastModifiedTime'] = isset($data['lastModifiedTime']) ? $data['lastModifiedTime'] : null;
        $this->container['dataSource'] = isset($data['dataSource']) ? $data['dataSource'] : null;
        $this->container['lastModifiedBy'] = isset($data['lastModifiedBy']) ? $data['lastModifiedBy'] : null;
        $this->container['usage'] = isset($data['usage']) ? $data['usage'] : null;
        $this->container['organization'] = isset($data['organization']) ? $data['organization'] : null;
        $this->container['keywords'] = isset($data['keywords']) ? $data['keywords'] : null;
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
        if ($this->container['name'] === null) {
            $invalid_properties[] = "'name' can't be null";
        }
        $allowed_values = ["any", "keyword", "audience"];
        if (!in_array($this->container['usage'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'usage', must be one of 'any', 'keyword', 'audience'.";
        }

        if ($this->container['keywords'] === null) {
            $invalid_properties[] = "'keywords' can't be null";
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
        if ($this->container['name'] === null) {
            return false;
        }
        $allowed_values = ["any", "keyword", "audience"];
        if (!in_array($this->container['usage'], $allowed_values)) {
            return false;
        }
        if ($this->container['keywords'] === null) {
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
     * @param string $id Unique identifier for this keyword_set. These should be URIs identifying the source and the keyword_set itself, and preferably also well formed http-URLs pointing to more information about the keyword.
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets name
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     * @param string $name Name for this keyword_set. This should be human readable, such that it could be shown as label in UI
     * @return $this
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

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
     * @param string $originId Set identifier in the originating system, if any
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
     * @param \DateTime $createdTime Time when this keyword_set was created (ISO 8601)
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
     * @param \DateTime $lastModifiedTime Time when this keyword_set was last modified (ISO 8601)
     * @return $this
     */
    public function setLastModifiedTime($lastModifiedTime)
    {
        $this->container['lastModifiedTime'] = $lastModifiedTime;

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
     * @param string $dataSource Unique identifier (URI)for the system where this keyword_set originated, if any
     * @return $this
     */
    public function setDataSource($dataSource)
    {
        $this->container['dataSource'] = $dataSource;

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
     * @param string $lastModifiedBy FIXME(verify) Which API user most recently edited this keyword
     * @return $this
     */
    public function setLastModifiedBy($lastModifiedBy)
    {
        $this->container['lastModifiedBy'] = $lastModifiedBy;

        return $this;
    }

    /**
     * Gets usage
     * @return string
     */
    public function getUsage()
    {
        return $this->container['usage'];
    }

    /**
     * Sets usage
     * @param string $usage Usage type for this keyword_set. These are allow UIs to show the set in appropriate place. FIXME: set of types is not finalized by any stretch
     * @return $this
     */
    public function setUsage($usage)
    {
        $allowed_values = array('any', 'keyword', 'audience');
        if (!is_null($usage) && (!in_array($usage, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'usage', must be one of 'any', 'keyword', 'audience'");
        }
        $this->container['usage'] = $usage;

        return $this;
    }

    /**
     * Gets organization
     * @return string
     */
    public function getOrganization()
    {
        return $this->container['organization'];
    }

    /**
     * Sets organization
     * @param string $organization Organization that has defined this keyword_set
     * @return $this
     */
    public function setOrganization($organization)
    {
        $this->container['organization'] = $organization;

        return $this;
    }

    /**
     * Gets keywords
     * @return \Metatavu\LinkedEvents\Model\Keyword[]
     */
    public function getKeywords()
    {
        return $this->container['keywords'];
    }

    /**
     * Sets keywords
     * @param \Metatavu\LinkedEvents\Model\Keyword[] $keywords Keywords that belong to this keyword_set
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->container['keywords'] = $keywords;

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


