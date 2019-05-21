declare module "wp" {

  export interface WPBlock {

  }

  export interface WPElement {
    createElement(type: string|Function, props?: Object, children?: string|WPElement|WPElement[]): WPElement;
  }

  export interface WPBlocks {
    registerBlockType(name: string, settings: object): WPBlock;
  }

  export interface WPEditor {
    RichText: any;
  }

  export interface WPComponents {
    Button: any;
    Modal: any;
    Autocomplete: any;
    Spinner: any;
    BaseControl: any;
    ServerSideRender: any;
    SelectControl: any;
    Placeholder: any;
    CheckboxControl: any;
    Notice: any;
    Panel: any;
    PanelBody: any;
    PanelRow: any;
  }

  export interface WPCompose {
    withState: any;
  }

  export interface WPData {
    subscribe(callback : () => void): () => void,
    select(storeName: string): any;
    dispatch(storeName: string): any;
    registerStore(storeName: string, props: any): any;
    withSelect(mapSelectToProps: (select: any, ownProps: any) => void): (component: any) => any;
  }

  export interface WPHooks {
    addAction(hookName: string, namespace: string, callback: () => void, priority?: number): void;
  }

  export interface WPI18n {
    __: any;
    sprintf: any;
  }
  
  export interface wp {
    data: WPData;
    hooks: WPHooks;
    element: WPElement;
    blocks: WPBlocks;
    editor: WPEditor;
    components: WPComponents; 
    compose: WPCompose;
    i18n: WPI18n,
    apiFetch: any;
  }

  
}