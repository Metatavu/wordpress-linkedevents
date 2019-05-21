import React from 'react';
import { wp } from 'wp';

declare var wp: wp;
const { __ } = wp.i18n;

/**
 * Interface describing component props
 */
interface Props {
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
    return (
      <div>
        <wp.components.ServerSideRender block="linkedevents/list-block" 
          attributes={{ }} 
          urlQueryArgs={{ preview: true, version: this.state.version }} />
      </div>
    );
  }

}

export default EventList;