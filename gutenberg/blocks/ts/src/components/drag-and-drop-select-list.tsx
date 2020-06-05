import React from 'react';
import { wp } from 'wp';

declare var wp: wp;
const { __ } = wp.i18n;

interface State {
  items: string[];
  selected: string[];
}

interface Props {
  value: string[];
  forcedItems: string[];
  optionalItems: string[];
  onChange: (value: string[]) => void;
}

class DragAndDropSelectList extends React.Component<Props, State> {
  constructor(props: Props) {
    super(props);
    this.state = {
      items: [],
      selected: []
    };
  }

  /**
   * Component did mount life-cycle event
   */
  public componentDidMount = async () => {
    const selected: any[] = [];
    const items: string[] = this.props.optionalItems;

    for (let i = 0; i < this.props.value.length; i++) {
      selected.push(this.props.value[i]);
    }

    this.props.forcedItems.forEach(item => {
      if (!selected.includes(item)) {
        selected.push(item);
      }
    });

    this.setState({
      items,
      selected
    });
  };

  draggedItem: any;
  draggedIdx: any;

  onDragStart = (e: any, index: number) => {
    this.draggedItem = this.state.selected[index];
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.target.parentNode);
    e.dataTransfer.setDragImage(e.target.parentNode, 20, 20);
  };

  onDragOver = (index: number) => {
    const draggedOverItem = this.state.selected[index];

    if (this.draggedItem === draggedOverItem) {
      return;
    }
    let selected = this.state.selected.filter(item => item !== this.draggedItem);
    selected.splice(index, 0, this.draggedItem);
    this.setState({ selected });
    this.triggerSelectedChange(selected);
  };

  onDragEnd = () => {
    this.draggedIdx = null;
  };

  render() {
    return (
      <div className='App'>
        <main>
          <ul>
            {this.renderSelectedItems()}
            {this.renderItems()}
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
        {this.state.selected.map((selectedItem, idx) => (
          <li key={selectedItem} onDragOver={() => this.onDragOver(idx)}>
            <div className='drag' draggable onDragStart={e => this.onDragStart(e, idx)} onDragEnd={this.onDragEnd}>
              <CheckboxControl
                checked={true}
                label={`${idx + 1}. ${__(selectedItem, 'linkedevents')}`}
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
      return <div>{<CheckboxControl checked={false} label={__(item, 'linkedevents')} key={item} onChange={() => this.onMatchItemChange(item)} />}</div>;
    });
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

      const items = [removedItem].concat(this.state.items);

      this.setState({
        items,
        selected
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

    const selected = this.state.selected.concat([selectedItem]);

    this.setState({
      items,
      selected
    });

    this.triggerSelectedChange(selected);
  };

  /**
   * Triggers a selected change
   *
   * @param selected selected items
   */
  private triggerSelectedChange = (selected: string[]) => {
    this.props.onChange(selected);
  };
}

export default DragAndDropSelectList;
