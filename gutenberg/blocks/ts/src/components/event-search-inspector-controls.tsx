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

}

export default EventSearchInspectorControls;