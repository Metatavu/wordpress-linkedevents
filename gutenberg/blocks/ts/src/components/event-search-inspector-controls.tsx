import React from 'react';
import { wp, WPSelectControlOption } from 'wp';

declare var wp: wp;
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
}

/**
 * Event list component
 */
class EventSearchInspectorControls extends React.Component<Props, State> {

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
        { this.renderDateFilterVisible() }
        { this.renderSortVisible() }
        { this.renderKeywordsVisible() }
        { this.renderLocationVisible() }
        { this.renderLocationOptions() }
        { this.renderAudienceVisible() }
        { this.renderCategoriesVisible() }
      </InspectorControls>
    );
  }

  /**
   * Renders select for selecting whether date filter is visible or not
   */
  private renderDateFilterVisible = () => {
    const title = __("Date filter visible", "linkedevents");
    const hint = __("Whether to show the date filter in search widget or not", "linkedevents");

    return this.renderSelectControl(title, hint, "dateFilterVisible", [{
      value: "",
      label: __("Hidden", "linkedevents")
    }, {
      value: "true",
      label: __("Visible", "linkedevents")
    }]);
  }

  /**
   * Renders select for selecting whether sort is visible or not
   */
  private renderSortVisible = () => {
    const title = __("Sort visible", "linkedevents");
    const hint = __("Whether to show sort select in search widget", "linkedevents");

    return this.renderSelectControl(title, hint, "sortVisible", [{
      value: "",
      label: __("Hidden", "linkedevents")
    }, {
      value: "true",
      label: __("Visible", "linkedevents")
    }]);
  }

  /**
   * Renders select for selecting whether keywords is visible or not
   */
  private renderKeywordsVisible = () => {
    const title = __("Keywords visible", "linkedevents");
    const hint = __("Whether to show keywords in search widget", "linkedevents");

    return this.renderSelectControl(title, hint, "keywordsVisible", [{
      value: "",
      label: __("Hidden", "linkedevents")
    }, {
      value: "true",
      label: __("Visible", "linkedevents")
    }]);
  }

   /**
   * Renders select for selecting whether location is visible or not
   */
  private renderLocationVisible = () => {
    const title = __("Location visible", "linkedevents");
    const hint = __("Whether to show location in search widget", "linkedevents");

    return this.renderSelectControl(title, hint, "locationVisible", [{
      value: "",
      label: __("Hidden", "linkedevents")
    }, {
      value: "true",
      label: __("Visible", "linkedevents")
    }]);
  }

  /**
   * Renders location options
   */
  private renderLocationOptions = () => {
    const visible = this.props.getAttribute("locationVisible");
    if (visible) {
      const title = __("Add Locations", "linkedevents");
      const hint = __("Add possible locations for users to fetch", "linkedevents");

      return this.renderTextControl(title, hint, "locations");
      //return this.renderSubmittableTextForm(title, hint, "locations");
    }
  }

  /**
   * Renders select for selecting whether audience is visible or not
   */
  private renderAudienceVisible = () => {
    const title = __("Audience visible", "linkedevents");
    const hint = __("Whether to show audience in search widget", "linkedevents");

    return this.renderSelectControl(title, hint, "audienceVisible", [{
      value: "",
      label: __("Hidden", "linkedevents")
    }, {
      value: "true",
      label: __("Visible", "linkedevents")
    }]);
  }

  /**
   * Renders select for selecting whether categories are visible or not
   */
  private renderCategoriesVisible = () => {
    const title = __("Categories visible", "linkedevents");
    const hint = __("Whether to show categories in search widget", "linkedevents");

    return this.renderSelectControl(title, hint, "categoriesVisible", [{
      value: "",
      label: __("Hidden", "linkedevents")
    }, {
      value: "true",
      label: __("Visible", "linkedevents")
    }]);
  }

  /**
   * Renders select control
   * 
   * @param title filter title
   * @param hint filter hint text
   * @param attribute attribute for storing value
   * @param options options
   */
  private renderSelectControl = (title: string, hint: string, attribute: string, options: WPSelectControlOption[]) => {
    const { SelectControl, Tooltip } = wp.components;

    return (
      <div>
        <Tooltip text={ hint } >
          <label> { title } </label>
        </Tooltip>
        <SelectControl value={ this.props.getAttribute(attribute) } onChange={(value: string) => this.props.setAttribute(attribute, value) } options={ options }></SelectControl>
      </div>
    );
  }

  /**
   * Renders text control
   *
   * @param title title
   * @param hint hint text
   * @param attribute attribute for storing value
   */
  private renderTextControl = (title: string, hint: string, attribute: string) => {
    const { TextControl, Tooltip } = wp.components;

    return (
      <div>
        <Tooltip text={hint}>
          <label> { title } </label>
        </Tooltip>
        <TextControl value={this.props.getAttribute(attribute)} onChange={(value: string) => this.props.setAttribute(attribute, value)}></TextControl>
      </div>
    );
  };

}

export default EventSearchInspectorControls;