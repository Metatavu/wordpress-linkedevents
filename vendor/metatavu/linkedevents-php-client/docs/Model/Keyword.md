# Keyword

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | Consists of source prefix and source specific identifier. These should be URIs uniquely identifying the keyword, and preferably also well formed http-URLs pointing to more information about the keyword. | 
**name** | [**\Metatavu\LinkedEvents\Model\KeywordName**](KeywordName.md) |  | [optional] 
**images** | [**\Metatavu\LinkedEvents\Model\Image[]**](Image.md) |  | [optional] 
**originId** | **string** | Identifier for the keyword in the organization using this keyword. For standardized namespaces this will be a shared identifier. | [optional] 
**createdTime** | [**\DateTime**](\DateTime.md) | Creation time for the keyword entry. | [optional] 
**lastModifiedTime** | [**\DateTime**](\DateTime.md) | Time this place was modified in the datastore behind the API (not necessarily in the originating system) | [optional] 
**aggregate** | **bool** | FIXME(verify) This keyword is an combination of several keywords at source | [optional] 
**dataSource** | **string** | Source of the keyword, typically API provider specific identifier. Will also be used to specify standardized namespaces as they are brought into use. | 
**createdBy** | **string** | FIXME(verify) URL reference to the user that created this record (user endpoint) | [optional] 
**lastModifiedBy** | **string** | FIXME(verify) URL reference to the user that last modfied this record (user endpoint) | [optional] 
**altLabels** | **string[]** | FIXME(verify) alternative labels for this keyword, no language specified. Use case? | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


