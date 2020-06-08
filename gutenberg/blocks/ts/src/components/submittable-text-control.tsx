import React from 'react';
import { wp } from 'wp';

declare var wp: wp;
const { __ } = wp.i18n;

/**
 * Interface component props
 */
interface Props {
  value: string[];
  onChange: (value: string[]) => void;
}

/**
 * Interface component state
 */
interface State {
  items: string[];
  formValue: string;
}

/**
 * Submittable text control component
 */
class SubmittableTextControl extends React.Component<Props, State> {
  /*
   * Constructor
   *
   * @param props props
   */
  constructor(props: Props) {
    super(props);
    this.state = {
      items: [],
      formValue: ''
    };
    this.onItemDelete = this.onItemDelete.bind(this);
  }

  /**
   * Component did mount life-cycle event
   */
  public componentDidMount = async () => {
    const items: string[] = [];

    for (let i = 0; i < this.props.value.length; i++) {
      items.push(this.props.value[i]);
    }

    this.setState({
      items
    });
  };

  /**
   * Component render method
   */
  public render() {
    return (
      <div>
        {this.renderForm()}
        {this.renderItems()}
      </div>
    );
  }

  /**
   * Renders form
   */
  private renderForm = () => {
    const handleChange = (event: any) => {
      this.setState({formValue: event.target.value});
    }

    return (
      <div>
        <form onSubmit={() => this.onItemAdd()}>
          <label>
            Name:
            <input type='text' value={this.state.formValue} onChange={handleChange} />
          </label>
          <input type='submit' value={__('Add', 'linkedevents')} />
        </form>
      </div>
    );
  };

  /**
   * Renders added items
   */
  private renderItems = () => {
    const { Button } = wp.components;

    return this.state.items.map(item => {
      return (
        <div>
          {item}
          <Button onClick={() => this.onItemDelete(item)}>{__('Remove', 'linkedevents')}</Button>
        </div>
      );
    });
  };

  /**
   * Event handler for selected item change
   *
   * @param removedItem item removed from selected list
   */
  private onItemDelete = (removedItem: string) => {
    const items = this.state.items.filter(item => {
      return item !== removedItem;
    });

    this.setState({
      items
    });

    this.triggerSelectedChange(items);
  };

  /**
   * Event handler for item add
   *
   * @param event 
   */
  private onItemAdd = () => {
    const items = this.state.items.concat([this.state.formValue]);

    event.preventDefault();

    this.setState({
      items,
      formValue: ''
    });

    this.triggerSelectedChange(items);
  };

  /**
   * Triggers a selected change
   *
   * @param items selected items
   */
  private triggerSelectedChange = (items: string[]) => {
    this.props.onChange(items);
  };
}

export default SubmittableTextControl;
