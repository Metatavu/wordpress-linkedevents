import React from 'react';
import { wp } from 'wp';
import MenuIcon from '../menu-icon';
import "./drag-and-drop-select-list.scss";

declare var wp: wp;
const { __ } = wp.i18n;

/**
 * Interface component state
 */
interface State {
  items: string[];
  selected: string[];
  draggedItem: any;
}

/**
 * Interface component props
 */
interface Props {
  value: string[];
  forcedItems: string[];
  optionalItems: string[];
  onChange: (value: string[]) => void;
}

/**
 * Drag and drop multiselect component
 */
class DragAndDropSelectList extends React.Component<Props, State> {
  /*
   * Constructor
   * 
   * @param props props
   */
  constructor(props: Props) {
    super(props);
    this.state = {
      items: [],
      selected: [],
      draggedItem: null
    };
  }

  /**
   * Component did mount life-cycle event
   */
  public componentDidMount = async () => {
    const { forcedItems, optionalItems, value } = this.props;

    const selected: string[] = value.concat(forcedItems.filter(item => !value.includes(item)));
    const items: string[] = optionalItems.filter(item => !selected.includes(item));

    this.setState({
      items,
      selected
    });
  };

   /**
   * Component render method
   */
  public render() {
    return (
      <div className='App'>
        <main>
          <ul>
            { this.renderSelectedItems() }
            { this.renderItems() }
          </ul>
        </main>
      </div>
    );
  }

  /**
   * Renders selected items
   */
  private renderSelectedItems = () => {
    const { CheckboxControl } = wp.components;

    return (
      <div>
        {this.state.selected.map((selectedItem, index) => (
          <li key={selectedItem} onDragOver={() => this.onDragOver(index)}>
            <div className='drag-item' draggable onDragStart={e => this.onDragStart(e, index)} >
              <span className='menu-icon'>{MenuIcon}</span>
              <CheckboxControl
                checked={true}
                label={`${index + 1}. ${__(selectedItem, 'linkedevents')}`}
                key={selectedItem}
                onChange={() => this.onSelectedItemChange(selectedItem)}
              />
            </div>
          </li>
        ))}
      </div>
    );
  };

  /**
   * Renders match items
   */
  private renderItems = () => {
    const { CheckboxControl } = wp.components;

    return this.state.items.map(item => {
      return <div className='unselected-item'>{<CheckboxControl checked={false} label={__(item, 'linkedevents')} key={item} onChange={() => this.onMatchItemChange(item)} />}</div>;
    });
  };

    /**
   * Triggers a selected change
   *
   * @param selected selected items
   */
  private triggerSelectedChange = (selected: string[]) => {
    this.props.onChange(selected);
  };

    /**
   * Event handler for drag start 
   * 
   * @param e 
   * @param index items index on selected list
   */
  private onDragStart = (e: any, index: number) => {
    this.setState({
      draggedItem: this.state.selected[index]
    })
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.target.parentNode);
    e.dataTransfer.setDragImage(e.target.parentNode, 20, 20);
  };

  /**
   * Event handler for dragging over items
   */
  private onDragOver = (index: number) => {
    const draggedOverItem = this.state.selected[index];

    if (this.state.draggedItem === draggedOverItem) {
      return;
    }

    const selected = this.state.selected.filter(item => item !== this.state.draggedItem);
    selected.splice(index, 0, this.state.draggedItem);
    this.setState({ 
      selected: selected
    });
    this.triggerSelectedChange(selected);
  };

  /**
   * Event handler for selected item change
   *
   * @param removedItem item removed from selected list
   */
  private onSelectedItemChange = (removedItem: string) => {
    if (!this.props.forcedItems.includes(removedItem)) {
      const selected = this.state.selected.filter(selectedItem => {
        return selectedItem !== removedItem;
      });

      this.setState({
        items: [ ...this.state.items, removedItem ],
        selected: selected
      });

      this.triggerSelectedChange(selected);
    }
  };

  /**
   * Event handler for matc item change
   *
   * @param removedItem item selected from matched list
   */
  private onMatchItemChange = (selectedItem: string) => {
    const items = this.state.items.filter(item => {
      return item !== selectedItem;
    });

    this.setState({
      items,
      selected: [ ...this.state.selected, selectedItem ]
    });

    this.triggerSelectedChange([ ...this.state.selected, selectedItem ]);
  };
}

export default DragAndDropSelectList;
