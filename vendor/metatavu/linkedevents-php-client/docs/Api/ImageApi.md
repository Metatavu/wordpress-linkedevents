# Metatavu\LinkedEvents\ImageApi

All URIs are relative to *https://api.hel.fi/linkedevents/v1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**imageCreate**](ImageApi.md#imageCreate) | **POST** /image/ | Create a new image
[**imageList**](ImageApi.md#imageList) | **GET** /image/ | Returns a list of images
[**imageRetrieve**](ImageApi.md#imageRetrieve) | **GET** /image/{id}/ | Return information for single image
[**imageUpdate**](ImageApi.md#imageUpdate) | **PUT** /image/{id}/ | Update an image


# **imageCreate**
> \Metatavu\LinkedEvents\Model\Image imageCreate($imageFile, $imageObject)

Create a new image

There are two ways to create an image object. The image file can be posted as a multipart request, but the endpoint also accepts a simple JSON object with an external url in the url field. This allows using external images for events without saving them on the API server.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\ImageApi();
$imageFile = "/path/to/file.txt"; // \SplFileObject | 
$imageObject = new \Metatavu\LinkedEvents\Model\ImageUrl(); // \Metatavu\LinkedEvents\Model\ImageUrl | 

try {
    $result = $api_instance->imageCreate($imageFile, $imageObject);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ImageApi->imageCreate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **imageFile** | **\SplFileObject**|  | [optional]
 **imageObject** | [**\Metatavu\LinkedEvents\Model\ImageUrl**](../Model/ImageUrl.md)|  | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\Image**](../Model/Image.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: multipart/form-data
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **imageList**
> \Metatavu\LinkedEvents\Model\InlineResponse2001 imageList($page, $pageSize, $include, $sort)

Returns a list of images

Image endpoint returns images that are used for events, places or organizers.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\ImageApi();
$page = 56; // int | request particular page in paginated results
$pageSize = 56; // int | request that server delivers page_size results in response
$include = array("include_example"); // string[] | Embed given reference-type fields directly into the response, otherwise they are returned as URI references.
$sort = "sort_example"; // string | return the results in ascending or descending order by the named field. sorting is only supported for some string, integer and datetime fields.

try {
    $result = $api_instance->imageList($page, $pageSize, $include, $sort);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ImageApi->imageList: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **page** | **int**| request particular page in paginated results | [optional]
 **pageSize** | **int**| request that server delivers page_size results in response | [optional]
 **include** | [**string[]**](../Model/string.md)| Embed given reference-type fields directly into the response, otherwise they are returned as URI references. | [optional]
 **sort** | **string**| return the results in ascending or descending order by the named field. sorting is only supported for some string, integer and datetime fields. | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\InlineResponse2001**](../Model/InlineResponse2001.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **imageRetrieve**
> \Metatavu\LinkedEvents\Model\Image imageRetrieve($id)

Return information for single image

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\ImageApi();
$id = "id_example"; // string | The id for the image

try {
    $result = $api_instance->imageRetrieve($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ImageApi->imageRetrieve: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| The id for the image |

### Return type

[**\Metatavu\LinkedEvents\Model\Image**](../Model/Image.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **imageUpdate**
> \Metatavu\LinkedEvents\Model\Image imageUpdate($id, $imageObject)

Update an image

Images can be updated if the user has appropriate access permissions. The original implementation behaves like PATCH, ie. if some field is left out from the PUT call, its value is retained in database. In order to ensure consistent behaviour, users should always supply every field in PUT call.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new Metatavu\LinkedEvents\Api\ImageApi();
$id = "id_example"; // string | Identifier for the image to be updated
$imageObject = new \Metatavu\LinkedEvents\Model\Image(); // \Metatavu\LinkedEvents\Model\Image | Image object that should replace the existing image, note that some implementations may retain unspecified fields at their original values.

try {
    $result = $api_instance->imageUpdate($id, $imageObject);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ImageApi->imageUpdate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | **string**| Identifier for the image to be updated |
 **imageObject** | [**\Metatavu\LinkedEvents\Model\Image**](../Model/Image.md)| Image object that should replace the existing image, note that some implementations may retain unspecified fields at their original values. | [optional]

### Return type

[**\Metatavu\LinkedEvents\Model\Image**](../Model/Image.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

