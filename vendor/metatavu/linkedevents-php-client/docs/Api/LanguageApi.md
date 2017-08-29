# Metatavu\LinkedEvents\LanguageApi

All URIs are relative to *https://api.hel.fi/linkedevents/v1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**languageList**](LanguageApi.md#languageList) | **GET** /language/ | Return a list of languages used for describing events
[**languageRetrieve**](LanguageApi.md#languageRetrieve) | **GET** /language/{id}/ | Return information for single language


# **languageList**
> \Metatavu\LinkedEvents\Model\InlineResponse2005 languageList()

Return a list of languages used for describing events

The returned list describes languages used for describing events in this Linked events instance

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\LanguageApi();

try {
    $result = $api_instance->languageList();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LanguageApi->languageList: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**\Metatavu\LinkedEvents\Model\InlineResponse2005**](../Model/InlineResponse2005.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **languageRetrieve**
> \Metatavu\LinkedEvents\Model\Language languageRetrieve($id)

Return information for single language

Can be used to retrieve translations for a single language

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\LanguageApi();
$id = "id_example"; // string | 

try {
    $result = $api_instance->languageRetrieve($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LanguageApi->languageRetrieve: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**|  |

### Return type

[**\Metatavu\LinkedEvents\Model\Language**](../Model/Language.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

