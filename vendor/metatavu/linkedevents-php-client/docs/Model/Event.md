# Event

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | consists of source prefix and source specific identifier. These should be URIs uniquely identifying the event, and preferably also well formed http-URLs pointing to more information about the event. | [optional] 
**location** | [**\Metatavu\LinkedEvents\Model\IdRef**](IdRef.md) |  | 
**keywords** | [**\Metatavu\LinkedEvents\Model\IdRef[]**](IdRef.md) | array of keyword uri references | 
**inLanguage** | [**\Metatavu\LinkedEvents\Model\Language[]**](Language.md) | the languages spoken or supported at the event | [optional] 
**superEvent** | [**\Metatavu\LinkedEvents\Model\IdRef**](IdRef.md) | references the aggregate event containing this event | [optional] 
**superEventType** | **string** | If the event has sub_events, describes the type of the event. Current options are &#39;null&#39; and &#39;recurring&#39;, which means a repeating event. | [optional] 
**eventStatus** | **string** | As defined in schema.org/Event. Postponed events do not have a date set, rescheduled events have been moved to different date. | [optional] 
**publicationStatus** | **string** | Only available in POST/PUT. Specifies whether the event should be published in the API (&#39;public&#39;) or not (&#39;draft&#39;). | 
**externalLinks** | [**\Metatavu\LinkedEvents\Model\Eventlink[]**](Eventlink.md) | See external link definition | [optional] 
**offers** | [**\Metatavu\LinkedEvents\Model\Offer[]**](Offer.md) | See offer definition | [optional] 
**subEvents** | [**\Metatavu\LinkedEvents\Model\IdRef[]**](IdRef.md) | for aggregate events this contains references to all sub events. Usually this means that the sub events are part of series. The field &#39;super_event_type&#39; tells the type of the aggregate event. | [optional] 
**customData** | **string** | Key value field for custom data. FIXME: is there 6Aika-wide use case for this? | [optional] 
**name** | [**\Metatavu\LinkedEvents\Model\EventName**](EventName.md) |  | [optional] 
**images** | [**\Metatavu\LinkedEvents\Model\Image[]**](Image.md) |  | [optional] 
**createdTime** | [**\DateTime**](\DateTime.md) | Creation time for the event entry. | [optional] 
**lastModifiedTime** | [**\DateTime**](\DateTime.md) | Time this event was modified in the datastore behind the API (not necessarily in the originating system) | [optional] 
**infoUrl** | [**\Metatavu\LinkedEvents\Model\EventInfoUrl**](EventInfoUrl.md) |  | [optional] 
**description** | **object** | Description for the event, several chapters(FIXME, verify) | [optional] 
**shortDescription** | **object** | Short description for the event, recommended limit 140 characters | [optional] 
**context** | **string** |  | [optional] 
**type** | **string** |  | [optional] 
**datePublished** | [**\DateTime**](\DateTime.md) | Date this event is free to be published | [optional] 
**provider** | **object** | organization responsible for the practical implementation of the event | [optional] 
**locationExtraInfo** | **object** | Unstructured extra info about location (like \&quot;eastern door of railway station\&quot;) | [optional] 
**startTime** | [**\DateTime**](\DateTime.md) | Time the event will start | 
**endTime** | [**\DateTime**](\DateTime.md) | Time the event will end | [optional] 
**hasStartTime** | **bool** |  | [optional] 
**hasEndTime** | **bool** |  | [optional] 
**audience** | [**\Metatavu\LinkedEvents\Model\Keyword[]**](Keyword.md) |  | [optional] 
**dataSource** | **string** | Unique identifier (URI)for the system from which this event came from, preferably URL with more information about the system and its policies | [optional] 
**createdBy** | **string** | FIXME(verify) Which API user created this keyword | [optional] 
**lastModifiedBy** | **string** | FIXME(verify) Which API user most recently edited this keyword | [optional] 
**publisher** | **string** | Organization responsible for this event record. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


