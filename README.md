# Wordpress LinkedEvents

This is a Wordpress plugin that adds features for using LinkedEvents API data with Wordpress installation.

Key features:

  - Support for managing events from Wordpress admin view
  - Gutenberg block for listing evesnt from API
  - Fully translated to english and finnish

## Usage

### Gutenberg event list block

Gutenberg event list block can be used to list events from the LinkedEvents API.

### Basic usage

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

## Configuration

### Installation

### Theming
