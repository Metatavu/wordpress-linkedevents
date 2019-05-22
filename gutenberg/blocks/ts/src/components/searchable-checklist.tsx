import React from 'react';
import { wp } from 'wp';

declare var wp: wp;
const { __ } = wp.i18n;

export interface SearchableChecklistItem {
  id: string,
  text: string
}

/**
 * Interface component props
 */
interface Props {
  minCharacters: number,
  value: string[],
  onGetItem: (id: string) => Promise<SearchableChecklistItem>,
  onSearch: (search: string) => Promise<SearchableChecklistItem[]>,
  onChange: (value: string[]) => void
}

/**
 * Interface component state
 */
interface State {
  search: string,
  loading: boolean,
  match: SearchableChecklistItem[],
  selected: SearchableChecklistItem[]
}

/**
 * Searchable multiselect component
 */
class SearchableChecklist extends React.Component<Props, State> {

  /*
   * Constructor
   * 
   * @param props props
   */
  constructor(props: Props) {
    super(props);
    this.state = {
      search: "",
      loading: false,
      match: [],
      selected: []
    };
  }

  /**
   * Component did mount life-cycle event
   */
  public componentDidMount = async () => {
    this.setState({
      loading: true
    });

    const selected: SearchableChecklistItem[] = [];

    for (let i = 0; i < this.props.value.length; i++) {
      selected.push(await this.props.onGetItem(this.props.value[i]));
    }

    this.setState({
      loading: false,
      selected: selected
    });
  }

  /**
   * Component render method
   */
  public render() {
    const { TextControl } = wp.components;

    return (
      <div>
        <div>
          <TextControl value={ this.state.search } onChange={ this.onSearchChange }></TextControl>        
        </div>
        <div>
          { this.renderSelectedItems() }
          { this.renderMatchItems() }
        </div>
      </div>
    );
  }

  /**
   * Renders selected items
   */
  private renderSelectedItems = () => {
    const { CheckboxControl, Spinner, Placeholder } = wp.components;

    if (this.state.loading) {
      const height = "50px";
      
      return (
        <Placeholder style={{ height: height, minHeight: height }}>
          <Spinner/>
        </Placeholder>
      )
    }

    return this.state.selected.map((selectedItem) => {
      return (
        <div>
          { 
            <CheckboxControl checked={ true } label={ selectedItem.text } key={ selectedItem.id } onChange={ () => this.onSelectedItemChange(selectedItem) }/>
          }
        </div>
      );
    });
  }

  /**
   * Renders match items
   */
  private renderMatchItems = () => {
    const { CheckboxControl } = wp.components;

    return this.state.match.map((match) => {
      return (
        <div>
          { 
            <CheckboxControl checked={ false } label={ match.text } key={ match.id } onChange={ () => this.onMatchItemChange(match) }/>
          }
        </div>
      );
    });
  }

  /**
   * Event handler for selected item change
   * 
   * @param removedItem item removed from selected list
   */
  private onSelectedItemChange = (removedItem: SearchableChecklistItem) => {
    const selected = this.state.selected.filter((selectedItem) => {
      return selectedItem.id !== removedItem.id;
    });

    const match = [ removedItem ].concat(this.state.match);

    this.setState({
      match: match,
      selected: selected
    });

    this.triggerSelectedChange(selected);
  }

  /**
   * Event handler for matc item change
   * 
   * @param removedItem item selected from matched list
   */
  private onMatchItemChange = (selectedItem: SearchableChecklistItem) => {
    const match = this.state.match.filter((match) => {
      return match.id !== selectedItem.id;
    });

    const selected = this.state.selected.concat([ selectedItem ]);

    this.setState({
      match: match,
      selected: selected
    });

    this.triggerSelectedChange(selected);
  }

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
  }

  /**
   * Triggers a selected change
   * 
   * @param selected selected items
   */
  private triggerSelectedChange = (selected: SearchableChecklistItem[]) => {
    this.props.onChange(selected.map((selectedItem) => {
      return selectedItem.id;
    }));
  }

}

export default SearchableChecklist;