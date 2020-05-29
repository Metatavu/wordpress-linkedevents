import React from 'react';
import { wp } from 'wp';

declare var wp: wp;
const { __ } = wp.i18n;

export interface DragAndDropListItem {
  name: string;
  selected: boolean;
}

interface State {
  items: DragAndDropListItem[];
  selected: string[];
}

interface Props {
  value: string[];
  onChange: (value: string[]) => void;
}

class DragAndDropSelectList extends React.Component<Props, State> {
  constructor(props: Props) {
    super(props);
    this.state = {
      items: [
        { name: 'name', selected: true },
        { name: 'shortDescription', selected: false },
        { name: 'location', selected: false }
      ],
      selected: ['name']
    };
  }

    /**
   * Component did mount life-cycle event
   */
  public componentDidMount = async () => {
    const selected: any[] = [];

    for (let i = 0; i < this.props.value.length; i++) {
      selected.push({name: this.props.value[i], selected: true});
    }

    this.setState({
      selected: selected
    });
  }

  draggedItem: any;
  draggedIdx: any;

  onDragStart = (e: any, index: number) => {
    this.draggedItem = this.state.items[index];
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.target.parentNode);
    e.dataTransfer.setDragImage(e.target.parentNode, 20, 20);
  };

  onDragOver = (index: number) => {
    const draggedOverItem = this.state.items[index];

    if (this.draggedItem === draggedOverItem) {
      return;
    }
    let items = this.state.items.filter(item => item !== this.draggedItem);
    items.splice(index, 0, this.draggedItem);
    this.setState({ items });
  };

  onDragEnd = () => {
    this.draggedIdx = null;
  };

  handleChange(event: any) {
    const target = event.target;
    const name = target.name;
    let items;
    let selected = [];
    if (name !== 'name') {
      items = this.state.items.map(item => {
        if (item.name === name) {
          item.selected = !item.selected;
          selected.push(item.name);
        }
        return item;
      });
    }

    this.setState({
      items: items
    });
  }

  render() {
    const { CheckboxControl } = wp.components;
    return (
      <div className='App'>
        <main>
          <ul>
            {this.state.items.map((item, idx) => (
              <li key={item.name} onDragOver={() => this.onDragOver(idx)}>
                <div className='drag' draggable onDragStart={e => this.onDragStart(e, idx)} onDragEnd={this.onDragEnd}>
                  <span>
                    <input name={item.name} type='checkbox' checked={item.selected} onChange={event => this.handleChange(event)} />
                  </span>
                  <span className='content'>
                    {idx} - {item.name}
                  </span>
                </div>
              </li>
            ))}
          </ul>
        </main>
      </div>
    );
  }
}

export default DragAndDropSelectList;
