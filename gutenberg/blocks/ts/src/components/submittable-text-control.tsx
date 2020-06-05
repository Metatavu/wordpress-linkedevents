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
}

/**
 * Searchable multiselect component
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
      items: []
    };
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
    const { TextControl } = wp.components;

    return (
      <div>
        <div>
          <TextControl value={this.state.search} onChange={this.onSearchChange}></TextControl>
        </div>
        <div>
          {this.renderForm()}
          {this.renderItems()}
        </div>
      </div>
    );
  }

  /**
   * Renders selected items
   */
  private renderForm = () => {
    const { CheckboxControl, Spinner, Placeholder } = wp.components;


    return this.state.selected.map(selectedItem => {
      return (
        <div>{<CheckboxControl checked={true} label={selectedItem.text} key={selectedItem.id} onChange={() => this.onSelectedItemChange(selectedItem)} />}</div>
      );
    });
  };

  /**
   * Renders match items
   */
  private renderItems = () => {
    const { Button } = wp.components;

    return this.state.items.map(item => {
      return <div>{item}<Button ></Button></div>;
    });
  };

  /**
   * Event handler for selected item change
   *
   * @param removedItem item removed from selected list
   */
  private onSelectedItemChange = (removedItem: SearchableChecklistItem) => {
    const selected = this.state.selected.filter(selectedItem => {
      return selectedItem.id !== removedItem.id;
    });

    const match = [removedItem].concat(this.state.match);

    this.setState({
      match: match,
      selected: selected
    });

    this.triggerSelectedChange(selected);
  };

  /**
   * Event handler for matc item change
   *
   * @param removedItem item selected from matched list
   */
  private onMatchItemChange = (selectedItem: SearchableChecklistItem) => {
    const match = this.state.match.filter(match => {
      return match.id !== selectedItem.id;
    });

    const selected = this.state.selected.concat([selectedItem]);

    this.setState({
      match: match,
      selected: selected
    });

    this.triggerSelectedChange(selected);
  };

  /**
   * Event handler for search
   *
   * @param value value
   */
  private onSearchChange = async (value: string) => {
    if (value && value.length >= this.props.minCharacters) {
      this.setState({
        loading: true,
        search: value
      });

      const match = await this.props.onSearch(value);

      this.setState({
        loading: false,
        match: match
      });
    } else {
      this.setState({
        search: value,
        loading: false,
        match: []
      });
    }
  };

  /**
   * Triggers a selected change
   *
   * @param selected selected items
   */
  private triggerSelectedChange = (selected: SearchableChecklistItem[]) => {
    this.props.onChange(
      selected.map(selectedItem => {
        return selectedItem.id;
      })
    );
  };
}

export default SubmittableTextControl;
