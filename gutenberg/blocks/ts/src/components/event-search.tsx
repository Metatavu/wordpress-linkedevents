import React from 'react';
import { wp, WPSelectControlOption } from 'wp';
import EventSearchInspectorControls from './event-search-inspector-controls';

declare var wp: wp;
const { __ } = wp.i18n;

/**
 * Interface describing component props
 */
interface Props {
  getAttribute: (attribute: string) => string;
  setAttribute: (attribute: string, value:  string) => void;
}

/**
 * Interface describing component state
 */
interface State {
}

/**
 * Event list component
 */
class EventSearch extends React.Component<Props, State> {

  /*
   * Constructor
   * 
   * @param props props
   */
  constructor(props: Props) {
    super(props);
    this.state = {
    };
  }

  /**
   * Component render method
   */
  public render() {
    const { RichText } = wp.editor;
    
    return (
      <div className="linkedevents-event-search-block">
        <div className="linkedevents-event-search-widget-label-container">
          <RichText
            className="linkedevents-event-search-widget-label"
            aria-label={ __( 'Label text' ) }
            placeholder={ __( 'Add label...' ) }
            withoutInteractiveFormatting
            value={ this.props.getAttribute("label") }
            onChange={ ( label: string ) => this.props.setAttribute("label", label ) }
          />
        </div>
        <div>
          <input
            className="linkedevents-event-search-widget-placeholder"
            aria-label={ __( 'Optional placeholder text' ) }
            placeholder={ this.props.getAttribute("textPlaceholder") ? undefined : __( 'Optional placeholder...' ) }
            value={ this.props.getAttribute("textPlaceholder") as string }
            onChange={ ( event ) => this.props.setAttribute("textPlaceholder", event.target.value ) }
          />
        </div>
        { this.renderDateFilter() }
        { this.renderSort() }
        <div className="linkedevents-event-search-widget-button-container">
          <RichText
            className="linkedevents-event-search-widget-button"
            aria-label={ __( 'Button text' ) }
            placeholder={ __( 'Add button text...' ) }
            withoutInteractiveFormatting
            value={ this.props.getAttribute("buttonText") }
            onChange={ ( buttonText: string ) => this.props.setAttribute("buttonText", buttonText ) }
          />
        </div>
        <EventSearchInspectorControls getAttribute={ this.props.getAttribute } setAttribute={ this.props.setAttribute }/>
      </div>
    );    
  }

  /**
   * Render date filter is visible
   */
  private renderDateFilter = () => {
    const { RichText } = wp.editor;
    const dateFilterVisible = this.props.getAttribute("dateFilterVisible");

    if (!dateFilterVisible) {
      return null;
    }

    return (
      <div>
        <div className="linkedevents-event-search-widget-option-label-container">
          <RichText
            className="linkedevents-event-search-widget-option-label"
            aria-label={ __( 'Label text' ) }
            placeholder={ __( 'Add label text...' ) }
            withoutInteractiveFormatting
            value={ this.props.getAttribute("dateFilterLabel") }
            onChange={ ( text: string ) => this.props.setAttribute("dateFilterLabel", text ) }
          />
        </div>
        <input
          className="linkedevents-event-search-widget-date-input"
          type="date"
          placeholder={ __("Start time", "linkedevents") }
        />
        <span className="linkedevents-event-search-widget-date-range-separator"> - </span>
        <input
          className="linkedevents-event-search-widget-date-input"
          type="date"
          placeholder={ __("End time", "linkedevents") }
        />
      </div>
    );
  }

  /**
   * Render date filter is visible
   */
  private renderSort = () => {
    const { RichText } = wp.editor;
    const { SelectControl } = wp.components;
    const sortVisible = this.props.getAttribute("sortVisible");

    if (!sortVisible) {
      return null;
    }

    const options: WPSelectControlOption[] = [{
      value: null,
      label: __("Last modification time", "linkedevents")
    }, {
      value: "start_time",
      label: __("Start time", "linkedevents")
    }, {
      value: "end_time",
      label: __("End time", "linkedevents")
    }, {
      value: "days_left",
      label: __("Days left", "linkedevents")
    }];

    return (
      <div>
        <div className="linkedevents-event-search-widget-option-label-container">
          <RichText
            className="linkedevents-event-search-widget-option-label"
            aria-label={ __( 'Label text' ) }
            placeholder={ __( 'Add label text...' ) }
            withoutInteractiveFormatting
            value={ this.props.getAttribute("sortLabel") }
            onChange={ ( text: string ) => this.props.setAttribute("sortLabel", text ) }
          />
        </div>
        <SelectControl options={ options }></SelectControl>
      </div>
    );
  }
}

export default EventSearch;