# Place

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | Consists of source prefix and source specific identifier. These should be URIs uniquely identifying the place, and preferably also well formed http-URLs pointing to more information about the place. | [optional] 
**customData** | [**\Metatavu\LinkedEvents\Model\PlaceCustomData[]**](PlaceCustomData.md) | Key value field for custom data. FIXME: is there 6Aika-wide use case for this? | [optional] 
**name** | [**\Metatavu\LinkedEvents\Model\PlaceName**](PlaceName.md) |  | [optional] 
**images** | [**\Metatavu\LinkedEvents\Model\Image[]**](Image.md) |  | [optional] 
**originId** | **string** | Place identifier in the originating system, these should be in same format as id but variations are more likely than with id. | [optional] 
**createdTime** | [**\DateTime**](\DateTime.md) | Creation time for the place entry. | [optional] 
**lastModifiedTime** | [**\DateTime**](\DateTime.md) | Time this place was modified in the datastore behind the API (not necessarily in the originating system) | [optional] 
**infoUrl** | [**\Metatavu\LinkedEvents\Model\PlaceInfoUrl**](PlaceInfoUrl.md) |  | [optional] 
**description** | [**\Metatavu\LinkedEvents\Model\PlaceDescription**](PlaceDescription.md) |  | [optional] 
**position** | [**\Metatavu\LinkedEvents\Model\PlacePosition**](PlacePosition.md) |  | [optional] 
**email** | **string** | Contact email for the place, note that this is NOT multilingual | [optional] 
**telephone** | [**\Metatavu\LinkedEvents\Model\PlaceTelephone**](PlaceTelephone.md) |  | [optional] 
**contactType** | **string** | FIXME: this seems unused in Helsinki data. Does any 6Aika city have use for describing contact type? | [optional] 
**streetAddress** | [**\Metatavu\LinkedEvents\Model\PlaceStreetAddress**](PlaceStreetAddress.md) |  | [optional] 
**addressLocality** | [**\Metatavu\LinkedEvents\Model\PlaceAddressLocality**](PlaceAddressLocality.md) |  | [optional] 
**addressRegion** | **string** | Larger region for address (like states), not typically used in Finland | [optional] 
**postalCode** | **string** | Postal code of the location (as used by traditional mail) | [optional] 
**postOfficeBoxNum** | **string** | PO box for traditional mail, in case mail is not delivered to the building | [optional] 
**addressCountry** | **string** | Country for the place, NOT multilingual | [optional] 
**deleted** | **bool** | This place entry is not used anymore, but old events still reference it. This might be because of duplicate removal. | [optional] 
**dataSource** | **string** | Identifies the source for data, this is specific to API provider. This is useful for API users, as any data quality issues are likely to be specific to data source and workarounds can be applied as such. | [optional] 
**publisher** | **string** | Organization that provided the event that this place is associated with | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


