import React from 'react';
import { wp, WPSelectControlOption } from 'wp';
import moment from "moment";
import { ListBlockOptions } from '../types';
import SearchableChecklist, { SearchableChecklistItem } from './searchable-checklist';

declare var wp: wp;
declare var listBlockOptions: ListBlockOptions;
const { __ } = wp.i18n;

/**
 * Interface describing component props
 */
interface Props {
  getAttribute: (attribute: string) => string,
  setAttribute: (attribute: string, value: string) => void
}

/**
 * Interface describing component state
 */
interface State {
  version: number
}

/**
 * Event list component
 */
class EventList extends React.Component<Props, State> {

  /*
   * Constructor
   * 
   * @param props props
   */
  constructor(props: Props) {
    super(props);
    this.state = {
      version: 0
    };
  }

  /**
   * Component did update life-cycle event
   * 
   * @param prevProps previous props
   * @param prevState previous state
   */
  public componentDidUpdate(prevProps: Props, prevState: State) {
    
  }

  /**
   * Component render method
   */
  public render() {
    const { InspectorControls } = wp.editor;

    return (
      <InspectorControls>
        { this.renderStartFilter() }
        { this.renderEndFilter() }
        { this.renderBBoxFilter() }
        { this.renderLocationFilter() }
      </InspectorControls>
    );
  }
  
  /**
   * Renders start filter
   */
  private renderStartFilter = () => {
    const title = __("Start", "linkedevents");
    const hint = __("Show events beginning or ending after this time. Date can be today or specified date", "linkedevents");
    return this.renderDateControlFilter(title, hint, "start");
  } 

  /**
   * Renders end filter
   */
  private renderEndFilter = () => {
    const title = __("End", "linkedevents");
    const hint = __("Show events beginning or ending before this time. Date can be today or specified date", "linkedevents");
    return this.renderDateControlFilter(title, hint, "end");
  } 

  /**
   * Renders bbox filter
   */
  private renderBBoxFilter = () => {
    const title = __("Bounding Box", "linkedevents");
    const hint = __("Show events that are within this bounding box. Coordinates are given in order west, south, east, north. Period is used as decimal separator.", "linkedevents");
    return this.renderTextControlFilter(title, hint, "bbox");
  } 

  /**
   * Renders location filter
   */
  private renderLocationFilter = () => {
    const title = __("Locations", "linkedevents");
    const hint = __("Show events in given locations", "linkedevents");
    return this.renderSearchableChecklistFilter(title, hint, "location", this.searchPlaces, this.findPlace);
  }

  /**
   * Searches places by free text
   * 
   * @param search search query
   * @returns found places
   */
  private searchPlaces = async (search: string): Promise<SearchableChecklistItem[]> => {
    const text = encodeURIComponent(search) ;
    const result = await this.fetchFromLinkedEvents(`/place/?text=${text}`);

    return result.data.map((place: any) => {
      return {
        id: place.id,
        text: this.getLocalizedValue(place.name)
      };
    });
  } 

  /**
   * Finds a place by id from the API
   * 
   * @param id place id
   * @return place or null if not found
   */
  private findPlace = async (id: string): Promise<SearchableChecklistItem> => {
    const result = await this.fetchFromLinkedEvents(`/place/${id}`);
    if (!result || !result.id) {
      return null;
    }
    
    return {
      id: result.id,
      text: this.getLocalizedValue(result.name)
    };
  } 

  /**
   * Returns most appropriate localized value
   * 
   * @param localized localized item
   * @returns most appropriate localized value
   */
  private getLocalizedValue(localized: { [key: string]: string }): string | null {
    const result = localized[this.getCurrentLanguage()];
    if (result) {
      return result;
    }

    const languages: string[] = Object.keys(localized);
    if (!languages || !languages.length) {
      return null;
    }

    return localized[languages[0]];
  }

  /**
   * Makes an query into the LinkedEvents API
   * 
   * @param url query URL without the server part
   * @returns result
   */
  private fetchFromLinkedEvents = async (url: string) => {
    const result = await (await fetch(`${listBlockOptions.apiUrl}/${url}`, {
      headers: {
        "Accept": "application/json"
      }
    })).json();

    return result;
  }

  /**
   * Returns current language
   * 
   * @returns current language
   */
  private getCurrentLanguage(): string {
    return listBlockOptions.language;
  }

  /**
   * Renders date control filter
   * 
   * @param title filter title
   * @param hint filter hint text
   * @param attribute attribute for storing filter value
   * @param onSearch method to execute the search
   * @param onGetItem method to find single match
   */
  private renderSearchableChecklistFilter = (title: string, hint: string, attribute: string, onSearch: (search: string) => Promise<SearchableChecklistItem[]>, onGetItem: (id: string) => Promise<SearchableChecklistItem>) => {
    const { Tooltip } = wp.components;
    const value = (this.props.getAttribute(`filter-${attribute}`) || "").split(",").filter((id: string) => {
      return !!id;
    });

    const onChange = (ids: string[]) => {
      this.props.setAttribute(`filter-${attribute}`, ids.join(","));
    }

    return (
      <div>
        <Tooltip text={ hint } >
          <label> { title } </label>
        </Tooltip>
        <SearchableChecklist minCharacters={ 3 } onSearch={ onSearch } onGetItem={ onGetItem } value={ value } onChange={ onChange }/>
      </div>
    );
  }

  /**
   * Renders date control filter
   * 
   * @param title filter title
   * @param hint filter hint text
   * @param attribute attribute for storing filter value
   */
  private renderDateControlFilter = (title: string, hint: string, attribute: string) => {
    const { DatePicker, SelectControl, Tooltip } = wp.components;
    const options: WPSelectControlOption[] = [{
      label: __("Show all", "linkedevents"),
      value: ""
    }, {
      label: __("Today", "linkedevents"),
      value: "today"
    }, {
      label: __("Date", "linkedevents"),
      value: "date"
    }];

    const value = this.props.getAttribute(`filter-${attribute}`);
    const selectValue = !value ? "" : value == "today" ? "today" : "date";
    const onSelectChange = (value: string) => {
      if (value == "" || value == "today") {
        this.props.setAttribute(`filter-${attribute}`, value);
      } else {
        this.props.setAttribute(`filter-${attribute}`, moment().format());
      }
    };

    const pickerVisible = value && value !== 'today'; 

    return (
      <div>
        <Tooltip text={ hint } >
          <label> { title } </label>
        </Tooltip>
        <SelectControl value={ selectValue } onChange={ onSelectChange } options={ options }></SelectControl>
        { pickerVisible ? <DatePicker currentDate={ value } onChange={(value: string) => this.props.setAttribute(`filter-${attribute}`, value) } /> : null }
      </div>
    );
  }

  /**
   * Renders text control filter
   * 
   * @param title filter title
   * @param hint filter hint text
   * @param attribute attribute for storing filter value
   */
  private renderTextControlFilter = (title: string, hint: string, attribute: string) => {
    const { TextControl, Tooltip } = wp.components;

    return (
      <div>
        <Tooltip text={ hint } >
          <label> { title } </label>
        </Tooltip>
        <TextControl value={ this.props.getAttribute(`filter-${attribute}`) } onChange={(value: string) => this.props.setAttribute(`filter-${attribute}`, value) }></TextControl>
      </div>
    );
  }

}

export default EventList;