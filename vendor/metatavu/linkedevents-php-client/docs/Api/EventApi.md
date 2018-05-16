# Metatavu\LinkedEvents\EventApi

All URIs are relative to *https://api.hel.fi/linkedevents/v1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**eventCreate**](EventApi.md#eventCreate) | **POST** /event/ | Create a new event
[**eventDelete**](EventApi.md#eventDelete) | **DELETE** /event/{id}/ | Delete an event
[**eventList**](EventApi.md#eventList) | **GET** /event/ | Return a list of events
[**eventRetrieve**](EventApi.md#eventRetrieve) | **GET** /event/{id}/ | Retrieve single event by id
[**eventUpdate**](EventApi.md#eventUpdate) | **PUT** /event/{id}/ | Update an event


# **eventCreate**
> \Metatavu\LinkedEvents\Model\Event eventCreate($eventObject)

Create a new event

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\EventApi();
$eventObject = new \Metatavu\LinkedEvents\Model\Event(); // \Metatavu\LinkedEvents\Model\Event | 

try {
    $result = $api_instance->eventCreate($eventObject);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling EventApi->eventCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **eventObject** | [**\Metatavu\LinkedEvents\Model\Event**](../Model/Event.md)|  | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\Event**](../Model/Event.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **eventDelete**
> eventDelete($id)

Delete an event

Deletes an event

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\EventApi();
$id = "id_example"; // string | Identifier for the event to be deleted

try {
    $api_instance->eventDelete($id);
} catch (Exception $e) {
    echo 'Exception when calling EventApi->eventDelete: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Identifier for the event to be deleted |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **eventList**
> \Metatavu\LinkedEvents\Model\InlineResponse200 eventList($include, $text, $lastModifiedSince, $start, $end, $bbox, $dataSource, $location, $showAll, $division, $keyword, $recurring, $minDuration, $maxDuration, $publisher, $sort, $page, $pageSize, $addressLocalityFi, $addressLocalitySv, $addressLocalityEn, $publicationStatus)

Return a list of events

<h2 id=\"filtering-retrieved-events\">Filtering retrieved events</h2> <p>Query parameters can be used to filter the retrieved events by the following criteria.</p> <h3 id=\"event-time\">Event time</h3> <p>Use <code>start</code> and <code>end</code> to restrict the date range of returned events. Any events that intersect with the given date range will be returned.</p> <p>The parameters <code>start</code> and <code>end</code> can be given in the following formats:</p> <ul> <li>ISO 8601 (including the time of day)</li> <li>yyyy-mm-dd</li> </ul> <p>In addition, <code>today</code> can be used as the value.</p> <p>Example:</p> <pre><code>event/?start=2014-01-15&amp;end=2014-01-20 </code></pre> <p><a href=\"?start=2014-01-15&amp;end=2014-01-20\" title=\"json\">See the result</a></p> <h3 id=\"event-location\">Event location</h3> <h4 id=\"bounding-box\">Bounding box</h4> <p>To restrict the retrieved events to a geographical region, use the query parameter <code>bbox</code> in the format</p> <pre><code>bbox=west,south,east,north </code></pre> <p>Where <code>west</code> is the longitude of the rectangle's western boundary, <code>south</code> is the latitude of the rectangle's southern boundary, and so on.</p> <p>Example:</p> <pre><code>event/?bbox=24.9348,60.1762,24.9681,60.1889 </code></pre> <p><a href=\"?bbox=24.9348,60.1762,24.9681,60.1889\" title=\"json\">See the result</a></p> <h4 id=\"specific-location\">Specific location</h4> <p>To restrict the retrieved events to a known location(s), use the query parameter <code>location</code>, separating values by commas if you wish to query for several locations.</p> <p>Location ids are found at the <code>place</code> endpoint, which lists the locations in decreasing number of events found. Most locations originate from the Helsinki service point registry (tprek), hence the format <code>tprek:28473</code>. An easy way to locate service points is to browse <code>servicemap.hel.fi</code>, which uses the same location ids, e.g. <code>servicemap.hel.fi/unit/28473</code>.</p> <p>Example:</p> <pre><code>event/?location=tprek:28473 </code></pre> <p><a href=\"?location=tprek:28473\" title=\"json\">See the result</a></p> <h4 id=\"district\">District</h4> <p>To restrict the retrieved events to city district(s), use the query parameter <code>division</code>, separating values by commas if you wish to query for several divisions.</p> <p>City of Helsinki neighborhoods (kaupunginosa), districts (peruspiiri) and subdistricts (osa-alue) are supported.     <a href=\"http://kartta.hel.fi/?setlanguage=en&e=25498873&n=6674660&r=16&w=***&l=Karttasarja%2Ckaupunginosat%2Cosaalueet_WFS%2Cperuspiiri_WFS&o=100%2C100%2C100%2C100&swtab=kaikki\">         Check the divisions on the Helsinki map service.</a> </p> <p> You may query either by specific OCD division type <code>peruspiiri:malmi</code>, or by division name <code>malmi</code>. The latter query checks all divisions with the name, regardless of division type.</p> <p>Example:</p> <pre><code>event/?division=malmi </code></pre> <p><a href=\"?division=malmi\" title=\"json\">See the result</a></p> <h3 id=\"event-category\">Event category</h3> <p>To restrict the retrieved events by category, use the query parameter <code>keyword</code>, separating values by commas if you wish to query for several locations.</p> <p>Keyword ids are found at the <code>keyword</code> endpoint, which lists the keywords in decreasing number of events found. The common keywords used in all events originate from the general Finnish ontology (yso), hence the format <code>yso:p4354</code>.</p> <p>Example:</p> <pre><code>event/?keyword=yso:p4354 </code></pre> <p><a href=\"?keyword=yso:p4354\" title=\"json\">See the result</a></p> <h3 id=\"event-text\">Event text</h3> <p>To find out events that contain a specific string in any of the text fields, use the query parameter <code>text</code>.</p> <p>Example:</p> <pre><code>event/?text=shostakovich </code></pre> <p><a href=\"?text=shostakovich\" title=\"json\">See the result</a></p> <h2 id=\"getting-detailed-data\">Getting detailed data</h2> <p>In the default case, keywords, locations, and other fields that refer to separate resources are only displayed as simple references.</p> <p>If you want to include the complete data from related resources in the current response, use the keyword <code>include</code>. For example:</p> <pre><code>event/?include=location,keywords </code></pre> <p><a href=\"?include=location,keywords\" title=\"json\">See the result</a></p>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\EventApi();
$include = array("include_example"); // string[] | Embed given reference-type fields directly into the response, otherwise they are returned as URI references.
$text = "text_example"; // string | Search (case insensitive) through all multilingual text fields (name, description, short_description, info_url) of an event (every language). Multilingual fields contain the text that users are expected to care about, thus multilinguality is useful discriminator.
$lastModifiedSince = "lastModifiedSince_example"; // string | Search for events that have been modified since or at this time.
$start = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Search for events beginning or ending after this time. Dates can be specified using ISO 8601 (\"2016-01-12\") and additionally \"today\".
$end = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Search for events beginning or ending before this time. Dates can be specified using ISO 8601 (\"2016-01-12\") and additionally \"today\".
$bbox = array("bbox_example"); // string[] | Search for events that are within this bounding box. Decimal coordinates are given in order west, south, east, north. Period is used as decimal separator.
$dataSource = "dataSource_example"; // string | Search for events that come from the specified source system
$location = array(56); // int[] | Search for events in given locations as specified by id. Multiple ids are separated by comma
$showAll = true; // bool | Show all events (optional)
$division = "division_example"; // string | You may filter places by specific OCD division id, or by division name. The latter query checks all divisions with the name, regardless of division type.
$keyword = "keyword_example"; // string | Search for events with given keywords as specified by id. Multiple ids are separated by comma
$recurring = "recurring_example"; // string | Search for events based on whether they are part of recurring event set. 'super' specifies recurring, while 'sub' is non-recurring.
$minDuration = 56; // int | Search for events that are longer than given time in seconds
$maxDuration = 56; // int | Search for events that are shorter than given time in seconds
$publisher = "publisher_example"; // string | Search for events published by the given organization
$sort = "sort_example"; // string | Sort the returned events in the given order. Possible sorting criteria are 'start_time', 'end_time', 'days_left' and 'last_modified_time'. The default ordering is '-last_modified_time'.
$page = 56; // int | request particular page in paginated results
$pageSize = 56; // int | request that server delivers page_size results in response
$addressLocalityFi = "addressLocalityFi_example"; // string | Search for events in given address localities (fi). Multiple localities can be entered by separating them by a comma
$addressLocalitySv = "addressLocalitySv_example"; // string | Search for events in given address localities (sv). Multiple localities can be entered by separating them by a comma
$addressLocalityEn = "addressLocalityEn_example"; // string | Search for events in given address localities (en). Multiple localities can be entered by separating them by a comma
$publicationStatus = "publicationStatus_example"; // string | Filter events by publication status (either draft or public)

try {
    $result = $api_instance->eventList($include, $text, $lastModifiedSince, $start, $end, $bbox, $dataSource, $location, $showAll, $division, $keyword, $recurring, $minDuration, $maxDuration, $publisher, $sort, $page, $pageSize, $addressLocalityFi, $addressLocalitySv, $addressLocalityEn, $publicationStatus);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling EventApi->eventList: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **include** | [**string[]**](../Model/string.md)| Embed given reference-type fields directly into the response, otherwise they are returned as URI references. | [optional]
 **text** | **string**| Search (case insensitive) through all multilingual text fields (name, description, short_description, info_url) of an event (every language). Multilingual fields contain the text that users are expected to care about, thus multilinguality is useful discriminator. | [optional]
 **lastModifiedSince** | **string**| Search for events that have been modified since or at this time. | [optional]
 **start** | **\DateTime**| Search for events beginning or ending after this time. Dates can be specified using ISO 8601 (\&quot;2016-01-12\&quot;) and additionally \&quot;today\&quot;. | [optional]
 **end** | **\DateTime**| Search for events beginning or ending before this time. Dates can be specified using ISO 8601 (\&quot;2016-01-12\&quot;) and additionally \&quot;today\&quot;. | [optional]
 **bbox** | [**string[]**](../Model/string.md)| Search for events that are within this bounding box. Decimal coordinates are given in order west, south, east, north. Period is used as decimal separator. | [optional]
 **dataSource** | **string**| Search for events that come from the specified source system | [optional]
 **location** | [**int[]**](../Model/int.md)| Search for events in given locations as specified by id. Multiple ids are separated by comma | [optional]
 **showAll** | **bool**| Show all events (optional) | [optional]
 **division** | **string**| You may filter places by specific OCD division id, or by division name. The latter query checks all divisions with the name, regardless of division type. | [optional]
 **keyword** | **string**| Search for events with given keywords as specified by id. Multiple ids are separated by comma | [optional]
 **recurring** | **string**| Search for events based on whether they are part of recurring event set. &#39;super&#39; specifies recurring, while &#39;sub&#39; is non-recurring. | [optional]
 **minDuration** | **int**| Search for events that are longer than given time in seconds | [optional]
 **maxDuration** | **int**| Search for events that are shorter than given time in seconds | [optional]
 **publisher** | **string**| Search for events published by the given organization | [optional]
 **sort** | **string**| Sort the returned events in the given order. Possible sorting criteria are &#39;start_time&#39;, &#39;end_time&#39;, &#39;days_left&#39; and &#39;last_modified_time&#39;. The default ordering is &#39;-last_modified_time&#39;. | [optional]
 **page** | **int**| request particular page in paginated results | [optional]
 **pageSize** | **int**| request that server delivers page_size results in response | [optional]
 **addressLocalityFi** | **string**| Search for events in given address localities (fi). Multiple localities can be entered by separating them by a comma | [optional]
 **addressLocalitySv** | **string**| Search for events in given address localities (sv). Multiple localities can be entered by separating them by a comma | [optional]
 **addressLocalityEn** | **string**| Search for events in given address localities (en). Multiple localities can be entered by separating them by a comma | [optional]
 **publicationStatus** | **string**| Filter events by publication status (either draft or public) | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\InlineResponse200**](../Model/InlineResponse200.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **eventRetrieve**
> \Metatavu\LinkedEvents\Model\Event eventRetrieve($id)

Retrieve single event by id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\EventApi();
$id = "id_example"; // string | Event identifier as defined in event schema

try {
    $result = $api_instance->eventRetrieve($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling EventApi->eventRetrieve: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Event identifier as defined in event schema |

### Return type

[**\Metatavu\LinkedEvents\Model\Event**](../Model/Event.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **eventUpdate**
> \Metatavu\LinkedEvents\Model\Event eventUpdate($id, $eventObject)

Update an event

Events can be updated if the user has appropriate access permissions. The original implementation behaves like PATCH, ie. if some field is left out from the PUT call, its value is retained in database. In order to ensure consistent behaviour, users should always supply every field in PUT call.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\EventApi();
$id = "id_example"; // string | Identifier for the event to be updated
$eventObject = new \Metatavu\LinkedEvents\Model\Event(); // \Metatavu\LinkedEvents\Model\Event | Event object that should replace the existing event, note that some implementations may retain unspecified fields at their original values.

try {
    $result = $api_instance->eventUpdate($id, $eventObject);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling EventApi->eventUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Identifier for the event to be updated |
 **eventObject** | [**\Metatavu\LinkedEvents\Model\Event**](../Model/Event.md)| Event object that should replace the existing event, note that some implementations may retain unspecified fields at their original values. | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\Event**](../Model/Event.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

