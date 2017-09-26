# Metatavu\LinkedEvents\SearchApi

All URIs are relative to *https://api.hel.fi/linkedevents/v1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**eventSearch**](SearchApi.md#eventSearch) | **GET** /search/ | Search through events and places


# **eventSearch**
> \Metatavu\LinkedEvents\Model\InlineResponse200 eventSearch($page, $pageSize, $type, $q, $input, $start, $end)

Search through events and places

<h2 id=\"using-search-endpoint\">Using the search endpoint</h2> <p>This is the supposedly intelligent Elasticsearch Finnish full-text search for both events and places.     The results are sorted by relevance score shown in the <code>score</code> field. The search parameter is <code>?q=</code>. <h4 id=\"specifying-resource-type\">Specifying resource type</h4> <p>In the default case, both events and places are returned. The type of each resource is returned in the     <code>resource_type</code> field. You may use the parameter <code>type</code> to ask for only <code>event</code> or <code>place</code>. <h4 id=\"events-with-decay-score\">Future events with time decay</h4> <p>When searching for events only (<code>?type=event</code>), by default only future events are returned, with a decay function applied so that next     events get a higher relevance score. This means that     events in the near future are returned first:</p> <pre><code>search/?type=event&q=sibelius </code></pre> <p><a href=\"?type=event&q=sibelius\" title=\"json\">See the result</a></p> <h4 id=\"events-with-time-filtering\">Events with time filtering</h4> <p>When searching for events only (<code>?type=event</code>), you may also search for events in the specified time range using     <code>start</code> or <code>end</code> or both. In this case, relevance score is calculated only based on Finnish tokenization of the search string, with no time preference:</p> <pre><code>search/?type=event&q=sibelius&start=2017-01-01 </code></pre> <p><a href=\"?type=event&q=sibelius&start=2017-01-01\" title=\"json\">See the result</a></p> <h4 id=\"autocomplete\">Autocomplete</h4>         <p>For fast autocomplete substring match in the resource name, use the <code>?input=</code> parameter instead.             When searching for events only (<code>?type=event</code>), by default only future events are returned, with a decay function applied so that next     events get a higher relevance score. May be combined with <code>start</code> and <code>end</code>. <pre><code>search/?type=place&input=sibe </code></pre> <p><a href=\"?type=event&input=sibe\" title=\"json\">See the result</a></p>

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\SearchApi();
$page = 56; // int | request particular page in paginated results
$pageSize = 56; // int | request that server delivers page_size results in response
$type = "type_example"; // string | Comma-delimited list of resource types to search for. Currently allowed values are `event` and `place`. `type=event` must be specified for event date filtering and relevancy sorting.
$q = "q_example"; // string | Search for events and places matching this string. Mutually exclusive with `input` typeahead search.
$input = "input_example"; // string | Return autocompletition suggestions for this string. Mutually exclusive with `q` full-text search.
$start = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Search for events beginning or ending after this time. Dates can be specified using ISO 8601 (\"2016-01-12\") and additionally \"today\".
$end = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Search for events beginning or ending before this time. Dates can be specified using ISO 8601 (\"2016-01-12\") and additionally \"today\".

try {
    $result = $api_instance->eventSearch($page, $pageSize, $type, $q, $input, $start, $end);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SearchApi->eventSearch: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **page** | **int**| request particular page in paginated results | [optional]
 **pageSize** | **int**| request that server delivers page_size results in response | [optional]
 **type** | **string**| Comma-delimited list of resource types to search for. Currently allowed values are &#x60;event&#x60; and &#x60;place&#x60;. &#x60;type&#x3D;event&#x60; must be specified for event date filtering and relevancy sorting. | [optional]
 **q** | **string**| Search for events and places matching this string. Mutually exclusive with &#x60;input&#x60; typeahead search. | [optional]
 **input** | **string**| Return autocompletition suggestions for this string. Mutually exclusive with &#x60;q&#x60; full-text search. | [optional]
 **start** | **\DateTime**| Search for events beginning or ending after this time. Dates can be specified using ISO 8601 (\&quot;2016-01-12\&quot;) and additionally \&quot;today\&quot;. | [optional]
 **end** | **\DateTime**| Search for events beginning or ending before this time. Dates can be specified using ISO 8601 (\&quot;2016-01-12\&quot;) and additionally \&quot;today\&quot;. | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\InlineResponse200**](../Model/InlineResponse200.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

