import React from 'react';
import { wp, WPBlockTypeEditParams } from 'wp';
import ListIcon from "./list-icon";

declare var wp: wp;
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

/**
 * Registers block type
 */
registerBlockType('linkedevents/event-search-block', {
  title: __( 'LinkedEvents event search', 'linkedevents' ),
  icon: ListIcon,
  category: 'linkedevents',
  attributes: {
    "label": {
      type: 'string'
    },
    "buttonText": {
      type: 'string'
    },
    "textPlaceholder": {
      type: 'string'
    }
  },

  /**
   * Block type edit method 
   */
  edit: ((params: WPBlockTypeEditParams) => {
    
    const { RichText} = wp.editor;

    const getAttribute = (attribute: string): string => {
      return params.attributes[attribute];
    }

    const setAttribute = (attribute: string, value: string) => {
      const attributes: { [key: string]: string } = {}; 
      attributes[attribute] = value; 
      params.setAttributes(attributes);
    }

    return (
      <div style={{ display: "flex", flexWrap: "wrap" }}>
        <div style={{ width: "100%" }}>
          <RichText
            style={{ fontWeight: "bold" }}
            aria-label={ __( 'Label text' ) }
            placeholder={ __( 'Add label...' ) }
            withoutInteractiveFormatting
            value={ getAttribute("label") }
            onChange={ ( label: string ) => setAttribute("label", label ) }
          />
        </div>
        <input
          style={{ flexGrow: 1, "border": "1px solid #8d96a0", borderRadius: "4px", color: "rgba(14,28,46,.62)", fontSize: "13px" }}
          aria-label={ __( 'Optional placeholder text' ) }
          placeholder={ getAttribute("textPlaceholder") ? undefined : __( 'Optional placeholder...' ) }
          value={ getAttribute("textPlaceholder") }
          onChange={ ( event ) => setAttribute("textPlaceholder", event.target.value ) }
        />
        <div style={{ marginLeft: "10px" }}>
          <RichText
            style={{ background: "#f7f7f7", borderRadius: "4px", border: "1px solid #ccc", boxShadow: "inset 0 -1px 0 #ccc", padding: "6px 10px", fontSize: "13px" }}
            aria-label={ __( 'Button text' ) }
            placeholder={ __( 'Add button text…' ) }
            withoutInteractiveFormatting
            value={ getAttribute("buttonText") }
            onChange={ ( buttonText: string ) => setAttribute("buttonText", buttonText ) }
          />
        </div>
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

