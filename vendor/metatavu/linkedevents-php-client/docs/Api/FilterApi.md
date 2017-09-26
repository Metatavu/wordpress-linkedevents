# Metatavu\LinkedEvents\FilterApi

All URIs are relative to *https://api.hel.fi/linkedevents/v1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**keywordCreate**](FilterApi.md#keywordCreate) | **POST** /keyword/ | Creates new keyword
[**keywordDelete**](FilterApi.md#keywordDelete) | **DELETE** /keyword/{id}/ | Delete a keyword
[**keywordList**](FilterApi.md#keywordList) | **GET** /keyword/ | Returns a list of keywords used for describing events
[**keywordRetrieve**](FilterApi.md#keywordRetrieve) | **GET** /keyword/{id}/ | Return information for single keyword
[**keywordSetList**](FilterApi.md#keywordSetList) | **GET** /keyword_set/ | Return as list of keyword_sets for grouping keywords
[**keywordSetRetrieve**](FilterApi.md#keywordSetRetrieve) | **GET** /keyword_set/{id} | Return information about single keyword_set
[**keywordUpdate**](FilterApi.md#keywordUpdate) | **PUT** /keyword/{id}/ | Update a keyword
[**placeCreate**](FilterApi.md#placeCreate) | **POST** /place/ | Creates new place
[**placeDelete**](FilterApi.md#placeDelete) | **DELETE** /place/{id}/ | Delete a place
[**placeList**](FilterApi.md#placeList) | **GET** /place/ | Returns list of places used for describing events
[**placeRetrieve**](FilterApi.md#placeRetrieve) | **GET** /place/{id}/ | Return information for single place
[**placeUpdate**](FilterApi.md#placeUpdate) | **PUT** /place/{id}/ | Update a place


# **keywordCreate**
> \Metatavu\LinkedEvents\Model\Keyword keywordCreate($keywordObject)

Creates new keyword

Endpoint for creating new keywords

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$keywordObject = new \Metatavu\LinkedEvents\Model\Keyword(); // \Metatavu\LinkedEvents\Model\Keyword | 

try {
    $result = $api_instance->keywordCreate($keywordObject);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->keywordCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **keywordObject** | [**\Metatavu\LinkedEvents\Model\Keyword**](../Model/Keyword.md)|  | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\Keyword**](../Model/Keyword.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **keywordDelete**
> keywordDelete($id)

Delete a keyword

Deletes a keyword

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$id = "id_example"; // string | Identifier for the keyword to be deleted

try {
    $api_instance->keywordDelete($id);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->keywordDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Identifier for the keyword to be deleted |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **keywordList**
> \Metatavu\LinkedEvents\Model\InlineResponse2002 keywordList($page, $pageSize, $include, $showAllKeywords, $dataSource, $text, $sort)

Returns a list of keywords used for describing events

<h2 id=\"using-keyword-endpoint\">Using the keyword endpoint</h2> <p>Here, categories for events are listed. Events in each category can be found at the <code>event</code> endpoint using the query parameter <code>keyword</code>. The common keywords used in all events originate from the <a href=\"https://finto.fi/yso/en/\">general Finnish ontology (yso)</a>, hence the format <code>yso:p4354</code>. Default ordering is decreasing order by the number of events found.</p> <h4 id=\"keyword-text\">Keyword text</h4> <p>To find keywords that contain a specific string, use the query parameter <code>text</code>.</p> <p>Example:</p> <pre><code>keyword/?text=lapset </code></pre> <p><a href=\"?text=lapset\" title=\"json\">See the result</a></p> <h4 id=\"keyword-source\">Keyword source</h4> <p>Will restrict keywords to a specific data source.</p> <p>Example:</p> <pre><code>keyword/?data_source=yso </code></pre> <p><a href=\"?data_source=yso\" title=\"json\">See the result</a></p> <h4 id=\"showing-all-keyword\">Showing all keywords</h4> <p>By default, only those keywords which have listed events are displayed. You may display keywords with zero <code>n_events</code> with the     query parameter <code>show_all_keywords</code>.</p>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$page = 56; // int | request particular page in paginated results
$pageSize = 56; // int | request that server delivers page_size results in response
$include = array("include_example"); // string[] | Embed given reference-type fields directly into the response, otherwise they are returned as URI references.
$showAllKeywords = true; // bool | Show all keywords, including those that are not associated with any events. Otherwise such keywords are hidden. When show_all_keywords is specified, no other filter is applied, **except** \"filter\" (match for keywords beginning with string)
$dataSource = "dataSource_example"; // string | Search for keywords (**note**: NOT events) that come from the specified data source (see data source in keyword definition).
$text = "text_example"; // string | Search for keywords (**note**: NOT events) that contain the given string. This applies even when show_all_keywords is specified.
$sort = "sort_example"; // string | Sort the returned keywords in the given order. Possible sorting criteria are 'n_events', 'id', 'name', 'data_source'. The default ordering is '-data_source', '-n_events'.

try {
    $result = $api_instance->keywordList($page, $pageSize, $include, $showAllKeywords, $dataSource, $text, $sort);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->keywordList: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **page** | **int**| request particular page in paginated results | [optional]
 **pageSize** | **int**| request that server delivers page_size results in response | [optional]
 **include** | [**string[]**](../Model/string.md)| Embed given reference-type fields directly into the response, otherwise they are returned as URI references. | [optional]
 **showAllKeywords** | **bool**| Show all keywords, including those that are not associated with any events. Otherwise such keywords are hidden. When show_all_keywords is specified, no other filter is applied, **except** \&quot;filter\&quot; (match for keywords beginning with string) | [optional]
 **dataSource** | **string**| Search for keywords (**note**: NOT events) that come from the specified data source (see data source in keyword definition). | [optional]
 **text** | **string**| Search for keywords (**note**: NOT events) that contain the given string. This applies even when show_all_keywords is specified. | [optional]
 **sort** | **string**| Sort the returned keywords in the given order. Possible sorting criteria are &#39;n_events&#39;, &#39;id&#39;, &#39;name&#39;, &#39;data_source&#39;. The default ordering is &#39;-data_source&#39;, &#39;-n_events&#39;. | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\InlineResponse2002**](../Model/InlineResponse2002.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **keywordRetrieve**
> \Metatavu\LinkedEvents\Model\Keyword keywordRetrieve($id)

Return information for single keyword

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$id = "id_example"; // string | Same as id in keyword schema

try {
    $result = $api_instance->keywordRetrieve($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->keywordRetrieve: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Same as id in keyword schema |

### Return type

[**\Metatavu\LinkedEvents\Model\Keyword**](../Model/Keyword.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **keywordSetList**
> \Metatavu\LinkedEvents\Model\InlineResponse2003 keywordSetList($page, $pageSize, $include)

Return as list of keyword_sets for grouping keywords

Unlike other endpoints, keyword_sets do not support filtering. It is expected that the full result set will remain small enough.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$page = 56; // int | request particular page in paginated results
$pageSize = 56; // int | request that server delivers page_size results in response
$include = array("include_example"); // string[] | Embed given reference-type fields directly into the response, otherwise they are returned as URI references.

try {
    $result = $api_instance->keywordSetList($page, $pageSize, $include);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->keywordSetList: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **page** | **int**| request particular page in paginated results | [optional]
 **pageSize** | **int**| request that server delivers page_size results in response | [optional]
 **include** | [**string[]**](../Model/string.md)| Embed given reference-type fields directly into the response, otherwise they are returned as URI references. | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\InlineResponse2003**](../Model/InlineResponse2003.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **keywordSetRetrieve**
> \Metatavu\LinkedEvents\Model\KeywordSet keywordSetRetrieve($id)

Return information about single keyword_set

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$id = "id_example"; // string | Same as id in keyword_set schema

try {
    $result = $api_instance->keywordSetRetrieve($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->keywordSetRetrieve: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Same as id in keyword_set schema |

### Return type

[**\Metatavu\LinkedEvents\Model\KeywordSet**](../Model/KeywordSet.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **keywordUpdate**
> \Metatavu\LinkedEvents\Model\Keyword keywordUpdate($id, $keywordObject)

Update a keyword

Keywords can be updated if the user has appropriate access permissions.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$id = "id_example"; // string | Identifier for the keyword to be updated
$keywordObject = new \Metatavu\LinkedEvents\Model\Keyword(); // \Metatavu\LinkedEvents\Model\Keyword | Keyword object that should replace the existing keyword

try {
    $result = $api_instance->keywordUpdate($id, $keywordObject);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->keywordUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Identifier for the keyword to be updated |
 **keywordObject** | [**\Metatavu\LinkedEvents\Model\Keyword**](../Model/Keyword.md)| Keyword object that should replace the existing keyword | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\Keyword**](../Model/Keyword.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **placeCreate**
> \Metatavu\LinkedEvents\Model\Place placeCreate($placeObject)

Creates new place

Endpoint for creating new places

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$placeObject = new \Metatavu\LinkedEvents\Model\Place(); // \Metatavu\LinkedEvents\Model\Place | 

try {
    $result = $api_instance->placeCreate($placeObject);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->placeCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **placeObject** | [**\Metatavu\LinkedEvents\Model\Place**](../Model/Place.md)|  | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\Place**](../Model/Place.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **placeDelete**
> placeDelete($id)

Delete a place

Deletes a place

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$id = "id_example"; // string | Identifier for the place to be deleted

try {
    $api_instance->placeDelete($id);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->placeDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Identifier for the place to be deleted |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **placeList**
> \Metatavu\LinkedEvents\Model\InlineResponse2004 placeList($page, $pageSize, $showAllPlaces, $division, $dataSource, $text, $sort)

Returns list of places used for describing events

<h2 id=\"using-place-endpoint\">Using the place endpoint</h2> <p>Here, locations for events are listed. Events in each location can be found at the <code>event</code> endpoint using the query parameter <code>location</code>. Most locations     originate from the <a href=\"https://servicemap.hel.fi\">Helsinki service point registry (tprek)</a>, hence the id format <code>tprek:28473</code>. An easy way to locate service points is         to browse our <a href=\"https://servicemap.hel.fi\">Service Map</a>, which uses the same location ids, e.g. <code>servicemap.hel.fi/unit/28473</code>.</p> <h4 id=\"district\">District</h4> <p>To restrict the retrieved places to city district(s), use the query parameter <code>division</code>, separating values by commas if you wish to query for several divisions.</p> <p>City of Helsinki neighborhoods (kaupunginosa), districts (peruspiiri) and subdistricts (osa-alue) are supported.     <a href=\"http://kartta.hel.fi/?setlanguage=en&e=25498873&n=6674660&r=16&w=***&l=Karttasarja%2Ckaupunginosat%2Cosaalueet_WFS%2Cperuspiiri_WFS&o=100%2C100%2C100%2C100&swtab=kaikki\">         Check the divisions on the Helsinki map service.</a> </p> <p>You may query either by specific OCD division type <code>peruspiiri:malmi</code>, or by division name <code>malmi</code>. The latter query checks all divisions with the name, regardless of division type.</p> <p>Example:</p> <pre><code>place/?division=malmi </code></pre> <p><a href=\"?division=malmi\" title=\"json\">See the result</a></p> <h4 id=\"ordering\">Ordering</h4> <p>Default ordering is decreasing order by the number of events found.     You may also order results by <code>name</code>, <code>street_address</code> or <code>postal_code</code>.     For example:</p> <pre><code>place/?sort=name </code></pre> <p><a href=\"?sort=name\" title=\"json\">See the result</a></p> <h4 id=\"showing-all-places\">Showing all places</h4> <p>By default, only those locations which have listed events are displayed. You may display locations with zero <code>n_events</code> with the     query parameter <code>show_all_places</code>.</p>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$page = 56; // int | request particular page in paginated results
$pageSize = 56; // int | request that server delivers page_size results in response
$showAllPlaces = true; // bool | Show all places, including those that are not hosting any events. Otherwise such places are hidden. When show_all_places is specified, no other filter is applied.
$division = "division_example"; // string | You may filter places by specific OCD division id, or by division name. The latter query checks all divisions with the name, regardless of division type.
$dataSource = "dataSource_example"; // string | Search for places that come from specified data source
$text = "text_example"; // string | 
$sort = "sort_example"; // string | Sort the returned places in the given order. Possible sorting criteria are 'n_events', 'id', 'name', 'street_address' and 'postal_code'. The default ordering is '-n_events'.

try {
    $result = $api_instance->placeList($page, $pageSize, $showAllPlaces, $division, $dataSource, $text, $sort);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->placeList: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **page** | **int**| request particular page in paginated results | [optional]
 **pageSize** | **int**| request that server delivers page_size results in response | [optional]
 **showAllPlaces** | **bool**| Show all places, including those that are not hosting any events. Otherwise such places are hidden. When show_all_places is specified, no other filter is applied. | [optional]
 **division** | **string**| You may filter places by specific OCD division id, or by division name. The latter query checks all divisions with the name, regardless of division type. | [optional]
 **dataSource** | **string**| Search for places that come from specified data source | [optional]
 **text** | **string**|  | [optional]
 **sort** | **string**| Sort the returned places in the given order. Possible sorting criteria are &#39;n_events&#39;, &#39;id&#39;, &#39;name&#39;, &#39;street_address&#39; and &#39;postal_code&#39;. The default ordering is &#39;-n_events&#39;. | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\InlineResponse2004**](../Model/InlineResponse2004.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **placeRetrieve**
> \Metatavu\LinkedEvents\Model\Place placeRetrieve($id)

Return information for single place

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$id = "id_example"; // string | Id as defined in place model

try {
    $result = $api_instance->placeRetrieve($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->placeRetrieve: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Id as defined in place model |

### Return type

[**\Metatavu\LinkedEvents\Model\Place**](../Model/Place.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **placeUpdate**
> \Metatavu\LinkedEvents\Model\Place placeUpdate($id, $placeObject)

Update a place

Place can be updated if the user has appropriate access permissions.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\FilterApi();
$id = "id_example"; // string | Identifier for the place to be updated
$placeObject = new \Metatavu\LinkedEvents\Model\Place(); // \Metatavu\LinkedEvents\Model\Place | Place object that should replace the existing place.

try {
    $result = $api_instance->placeUpdate($id, $placeObject);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling FilterApi->placeUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Identifier for the place to be updated |
 **placeObject** | [**\Metatavu\LinkedEvents\Model\Place**](../Model/Place.md)| Place object that should replace the existing place. | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\Place**](../Model/Place.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

