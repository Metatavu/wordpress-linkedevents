import React from 'react';
import { wp, WPBlockTypeEditParams } from 'wp';
import ListIcon from "./list-icon";
import EventList from './components/event-list';
import EventListInspectorControls from './components/event-list-inspector-controls';

declare var wp: wp;
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

/**
 * Registers block type
 */
registerBlockType('linkedevents/list-block', {
  title: __( 'LinkedEvents list', 'linkedevents' ),
  icon: ListIcon,
  category: 'linkedevents',

  attributes: {
    "filter-start": {
      type: 'string'
    },
    "filter-end": {
      type: 'string'
    },
    "filter-bbox": {
      type: 'string'
    },
    "filter-location": {
      type: 'string'
    }
  },

  /**
   * Block type edit method 
   */
  edit: ((params: WPBlockTypeEditParams) => {
    const { isSelected } = params;

    const setAttribute = (attribute: string, value: string) => {
      const attributes: { [key: string]: string } = {}; 
      attributes[attribute] = value; 
      params.setAttributes(attributes);
    }

    const getAttribute = (attribute: string): string => {
      return params.attributes[attribute];
    }

    return (
      <div>
        { isSelected ? <EventListInspectorControls getAttribute={ getAttribute } setAttribute={ (attribute: string, value: string) => setAttribute(attribute, value)  }/> : null }
        <EventList/>
      </div>
    );
  }),

  /**
   * Block type save method 
   */
  save: (): any => {
    return null;
  }

});

