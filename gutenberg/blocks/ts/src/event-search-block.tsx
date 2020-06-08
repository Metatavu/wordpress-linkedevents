import React from 'react';
import { wp, WPBlockTypeEditParams } from 'wp';
import EventSearchIcon from "./events-search-icon";
import "./event-search-block.scss";
import EventSearch from './components/event-search';

declare var wp: wp;
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;
const attributes: any = {
  "label": {
    type: 'string'
  },
  "buttonText": {
    type: 'string'
  },
  "textPlaceholder": {
    type: 'string'
  },
  "dateFilterVisible": {
    type: 'boolean'
  },
  "dateFilterLabel": {
    type: "string"
  },
  "sortVisible": {
    type: 'boolean'
  },
  "sortLabel": {
    type: "string"
  },
  "keywordsVisible": {
    type: 'boolean'
  },
  "keywordsLabel": {
    type: "string"
  },
  "locationVisible": {
    type: 'boolean'
  },
  "locationLabel": {
    type: 'string'
  },
  "locations": {
    type: 'string'
  },
  "audienceVisible": {
    type: 'boolean'
  },
  "audienceLabel": {
    type: 'string'
  },
  "audiences": {
    type: 'string'
  }
};

/**
 * Registers block type
 */
registerBlockType('linkedevents/event-search-block', {
  title: __( 'Event search', 'linkedevents' ),
  icon: EventSearchIcon,
  category: 'linkedevents',
  attributes: attributes,

  /**
   * Block type edit method 
   */
  edit: ((params: WPBlockTypeEditParams) => {

    const getAttributeType = (attribute: string): string  => {
      return attributes[attribute] ? attributes[attribute].type : "string";
    }
    
    const getAttribute = (attribute: string): string => {
      const type = getAttributeType(attribute);
      const value = type == "boolean" ? params.attributes[attribute] ? true : false : params.attributes[attribute];
      return value;
    }

    const setAttribute = (attribute: string, value: string) => {
      const type = getAttributeType(attribute);
      const attributes: { [key: string]:  boolean | string } = {};
      attributes[attribute] = type == "boolean" ? value === "true" : value; 
      params.setAttributes(attributes);
    }

    return (
      <EventSearch getAttribute={ getAttribute } setAttribute={ setAttribute }/>
    );
  }),

  /**
   * Block type save method 
   */
  save: (): any => {
    return null;
  }

});

