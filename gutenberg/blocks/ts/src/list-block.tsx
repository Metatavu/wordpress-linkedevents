import React from 'react';
import { wp } from 'wp';
import ListIcon from "./list-icon";
import EventList from './components/event-list';

declare var wp: wp;
const { __ } = wp.i18n;

/**
 * Registers block type
 */
wp.blocks.registerBlockType('linkedevents/list-block', {
  title: __( 'LinkedEvents list', 'linkedevents' ),
  icon: ListIcon,
  category: 'linkedevents',

  attributes: {
  },

  /**
   * Block type edit method 
   */
  edit: ((params: any) => {
    return (
      <div>
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

