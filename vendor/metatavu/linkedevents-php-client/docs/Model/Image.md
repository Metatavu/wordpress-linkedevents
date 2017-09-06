# Image

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | image id | [optional] 
**name** | **string** |  | [optional] 
**publisher** | **string** | The organization responsible for the image. | [optional] 
**createdTime** | [**\DateTime**](\DateTime.md) | Creation time for the image. | [optional] 
**lastModifiedTime** | [**\DateTime**](\DateTime.md) | Time this image was modified in the datastore behind the API (not necessarily in the originating system) | [optional] 
**createdBy** | **string** | URL reference to the user that created this record (user endpoint) | [optional] 
**lastModifiedBy** | **string** | URL reference to the user that last modfied this record (user endpoint) | [optional] 
**url** | **string** | The image file URL. | 
**cropping** | **string** | Cropping data for the image. | [optional] 
**license** | **string** | License data for the image. May be \&quot;cc_by\&quot; (default) or \&quot;event_only\&quot;. The latter license restricts use of the image and is specified on the API front page. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


