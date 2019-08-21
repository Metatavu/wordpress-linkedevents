# Wordpress LinkedEvents

This is a Wordpress plugin that adds features for using LinkedEvents API data with Wordpress installation.

Key features:

  - Support for managing events from Wordpress admin view
  - Gutenberg block for listing evesnt from API
  - Fully translated to english and finnish
  
## Installation

    1. Upload folder into /wp-content/plugins -directory
    2. Activate the plugin in 'Plugins' menu
    3. Configure settings (see Configuration section for details)

## Usage

### Gutenberg event list block

Gutenberg event list block can be used to list events from the LinkedEvents API.

#### Basic usage

The plugin adds new block category LinkedEvents into Gutenberg. The category contains option for adding a list:

![Gutenberg list add](https://static.metatavu.io/wordpress-linkedevents/gutenberg-list-add.png)

The block can be added to the page by clicking the list item. When added to the page, the plugin display a preview of the events list in page. 

![Gutenberg list preview](https://static.metatavu.io/wordpress-linkedevents/gutenberg-list-preview.png)

#### Block options

There are lots of options for controlling how the event list works 

![Gutenberg list block options](https://static.metatavu.io/wordpress-linkedevents/gutenberg-list-inspector.png)

All options have localized usage help texts in editor. Help texts can be displayed by hovering over the option title in the editor view.

Here is a brief summary of each option:

##### Start
Show events beginning or ending after this time. Date can be today or specified date

#### End
Show events beginning or ending before this time. Date can be today or specified date

#### Bounding Box
Show events that are within this bounding box. Coordinates are given in order west, south, east, north. Period is used as decimal separator.

#### Locations
Show events in given locations

#### Division
You may filter places by specific OCD division id, or by division name

#### Address locality (fi)
You may filter events by place address locality name (finnish)

#### Keywords
Show events with specified keywords

#### Recurring
Show events based on whether they are part of recurring event set

#### Sort
Sort the returned events in the given order

#### Max results
Show maximum number of results in list

#### Duration (min - max)
Filter events by their duration (in seconds)

### Event administration

Plugin adds functionality to manage events directly from Wordpress admin view. All admin functions can be found from the admin dashboard menu Events

![Event management menu](https://static.metatavu.io/wordpress-linkedevents/event-management-menu.png)

Management view events list contains list of events from the API. Events can be filtered by their status (published, draft or all)

![Event management status](https://static.metatavu.io/wordpress-linkedevents/event-management-status.png)

Event list also has a free-text search and pagination support

![Event management search](https://static.metatavu.io/wordpress-linkedevents/event-management-search.png)

When hovering over the event with mouse, available actions for specific event are displayed. 

![Event management actions](https://static.metatavu.io/wordpress-linkedevents/event-management-actions.png)

Edit action opens an event edit view. Edit view can be used to directly modify the event in the API.

![Event management edit](https://static.metatavu.io/wordpress-linkedevents/event-management-edit.png)

Delete action can be used to delete event

![Event management delete](https://static.metatavu.io/wordpress-linkedevents/event-management-delete.png)

Menu also contains similar lists for controlling keywords and places.

## Configuration

Plugin configuration can be done from the administration panel settings menu.

![Configuration menu](https://static.metatavu.io/wordpress-linkedevents/configuration-menu.png)

View opening from the menu contains all the settings for the plugin

![Configuration view](https://static.metatavu.io/wordpress-linkedevents/configuration-view.png)

Here is a brief summary for each option

##### API URL

API URL for the LinkedEvents server. This option is required for all installation

##### Datasource	

Datasource for events. This option is required only for installation using event management functions.

##### Publisher Organization	

Publisher organization for events. This option is required only for installation using event management functions.

##### Timezone	

Used timezone when displaying event dates. Defaults to Europe/Helsinki

##### Google Maps Key

Google maps API key. This option is required only for installation using event management functions.

### Theming
