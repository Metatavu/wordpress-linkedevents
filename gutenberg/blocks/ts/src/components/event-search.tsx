import React from 'react';
import { wp, WPSelectControlOption } from 'wp';
import EventSearchInspectorControls from './event-search-inspector-controls';
import { LinkedEventsOptions } from '../types';
import { LinkedEventsApi } from '../linkedevents/api';
import LinkedEventsUtils from '../linkedevents/utils';

declare var wp: wp;
const { __ } = wp.i18n;
declare var linkedEventsOptions: LinkedEventsOptions;

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
  keywords: any[],
  locations: string[],
  audiences: any[],
  categories: any[]
}

/**
 * Event list component
 */
class EventSearch extends React.Component<Props, State> {

  private linkedEventsApi: LinkedEventsApi;
  
  /*
   * Constructor
   * 
   * @param props props
   */
  constructor(props: Props) {
    super(props);
    this.state = { 
      keywords: [],
      locations: [],
      audiences: [],
      categories: []
    };

    this.linkedEventsApi = new LinkedEventsApi(linkedEventsOptions.apiUrl);
  }

  /**
   * Component did mount life-cycle event
   */
  public componentDidMount = async () => {
    const locationsProp = this.props.getAttribute('locations').split(',');

    const locations: string[] = [];

    for (let i = 0; i < locationsProp.length; i++) {
      locations.push(locationsProp[i]);
    }

    const keywordSets = await this.linkedEventsApi.listKeywordSets({include: "keywords"});
    const audiences = keywordSets.find(keywordSet => keywordSet.usage === 'audience').keywords; 
    const categories = keywordSets.find(keywordSet => keywordSet.usage === 'any').keywords;

    this.setState({
      keywords: await this.linkedEventsApi.listKeywords(),
      locations,
      audiences,
      categories
    });
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
        { this.renderKeywords() }
        { this.renderLocation() }
        { this.renderAudience() }
        { this.renderCategories() }

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

  /**
   * Render keywords select if visible
   */
  private renderKeywords = () => {
    const { RichText } = wp.editor;
    const { CheckboxControl } = wp.components;
    const keywordsVisible = this.props.getAttribute("keywordsVisible");

    if (!keywordsVisible) {
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
            value={ this.props.getAttribute("keywordsLabel") }
            onChange={ ( text: string ) => this.props.setAttribute("keywordsLabel", text ) }
          />
        </div>
        <div>
          {
            this.state.keywords.map((keyword) => {
              return (
                <CheckboxControl className="keyword-checkbox" label={ LinkedEventsUtils.getLocalizedValue(keyword.name) }></CheckboxControl>
              );
            })
          }
        </div>
      </div>
    );
  }

    /**
   * Render location select if visible
   */
  private renderLocation = () => {
    const { RichText } = wp.editor;
    const { CheckboxControl } = wp.components;
    const locationVisible = this.props.getAttribute("locationVisible");

    if (!locationVisible) {
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
            value={ this.props.getAttribute("locationLabel") }
            onChange={ ( text: string ) => this.props.setAttribute("locationLabel", text ) }
          />
        </div>
        <div>
          {
            this.state.locations.map((location) => {
              return (
                <CheckboxControl className="keyword-checkbox" label={ location }></CheckboxControl>
              );
            })
          }
        </div>
      </div>
    );
  }

      /**
   * Render audience select if visible
   */
  private renderAudience = () => {
    const { RichText } = wp.editor;
    const { CheckboxControl } = wp.components;
    const audienceVisible = this.props.getAttribute("audienceVisible");

    if (!audienceVisible) {
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
            value={ this.props.getAttribute("audienceLabel") }
            onChange={ ( text: string ) => this.props.setAttribute("audienceLabel", text ) }
          />
        </div>
        <div>
          {
            this.state.audiences.map((audience) => {
              return (
                <CheckboxControl className="keyword-checkbox" label={ LinkedEventsUtils.getLocalizedValue(audience.name) }></CheckboxControl>
              );
            })
          }
        </div>
      </div>
    );
  }

  
      /**
   * Render categories select if visible
   */
  private renderCategories = () => {
    const { RichText } = wp.editor;
    const { CheckboxControl } = wp.components;
    const audienceVisible = this.props.getAttribute("categoriesVisible");

    if (!audienceVisible) {
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
            value={ this.props.getAttribute("categoriesLabel") }
            onChange={ ( text: string ) => this.props.setAttribute("categoriesLabel", text ) }
          />
        </div>
        <div>
          {
            this.state.categories.map((category) => {
              return (
                <CheckboxControl className="keyword-checkbox" label={ LinkedEventsUtils.getLocalizedValue(category.name) }></CheckboxControl>
              );
            })
          }
        </div>
      </div>
    );
  }
}

export default EventSearch;