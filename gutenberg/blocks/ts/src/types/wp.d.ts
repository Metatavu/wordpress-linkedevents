declare module "wp" {

  import React from 'react';
  import { MomentInput } from 'moment';

  export interface WPBlock {

  }

  export interface WPElement {
    createElement(type: string|Function, props?: Object, children?: string|WPElement|WPElement[]): WPElement;
  }

  export interface WPBlocks {
    registerBlockType(name: string, settings: object): WPBlock;
  }

  export interface WPEditor {
    ServerSideRender: any,
    AutosaveMonitor: any,
    DocumentOutline: any,
    DocumentOutlineCheck: any,
    VisualEditorGlobalKeyboardShortcuts: any,
    EditorGlobalKeyboardShortcuts: any,
    TextEditorGlobalKeyboardShortcuts: any,
    EditorHistoryRedo: any,
    EditorHistoryUndo: any,
    EditorNotices: any,
    ErrorBoundary: any,
    PageAttributesCheck: any,
    PageAttributesOrder: any,
    PageAttributesParent: any,
    PageTemplate: any,
    PostAuthor: any,
    PostAuthorCheck: any,
    PostComments: any,
    PostExcerpt: any,
    PostExcerptCheck: any,
    PostFeaturedImage: any,
    PostFeaturedImageCheck: any,
    PostFormat: any,
    PostFormatCheck: any,
    PostLastRevision: any,
    PostLastRevisionCheck: any,
    PostLockedModal: any,
    PostPendingStatus: any,
    PostPendingStatusCheck: any,
    PostPingbacks: any,
    PostPreviewButton: any,
    PostPublishButton: any,
    PostPublishButtonLabel: any,
    PostPublishPanel: any,
    PostSavedState: any,
    PostSchedule: any,
    PostScheduleCheck: any,
    PostScheduleLabel: any,
    PostSticky: any,
    PostStickyCheck: any,
    PostSwitchToDraftButton: any,
    PostTaxonomies: any,
    PostTaxonomiesCheck: any,
    PostTextEditor: any,
    PostTitle: any,
    PostTrash: any,
    PostTrashCheck: any,
    PostTypeSupportCheck: any,
    PostVisibility: any,
    PostVisibilityLabel: any,
    PostVisibilityCheck: any,
    TableOfContents: any,
    UnsavedChangesWarning: any,
    WordCount: any,
    EditorProvider: any,
    blockAutocompleter: any,
    userAutocompleter: any,
    Autocomplete: any,
    AlignmentToolbar: any,
    BlockAlignmentToolbar: any,
    BlockControls: any,
    BlockEdit: any,
    BlockEditorKeyboardShortcuts: any,
    BlockFormatControls: any,
    BlockIcon: any,
    BlockInspector: any,
    BlockList: any,
    BlockMover: any,
    BlockNavigationDropdown: any,
    BlockSelectionClearer: any,
    BlockSettingsMenu: any,
    BlockTitle: any,
    BlockToolbar: any,
    ColorPalette: any,
    ContrastChecker: any,
    CopyHandler: any,
    createCustomColorsHOC: any,
    DefaultBlockAppender: any,
    FontSizePicker: any,
    getColorClassName: any,
    getColorObjectByAttributeValues: any,
    getColorObjectByColorValue: any,
    getFontSize: any,
    getFontSizeClass: any,
    Inserter: any,
    InnerBlocks: any,
    InspectorAdvancedControls: any,
    InspectorControls: any,
    PanelColorSettings: any,
    PlainText: any,
    RichText: any,
    RichTextShortcut: any,
    RichTextToolbarButton: any,
    RichTextInserterItem: any,
    UnstableRichTextInputEvent: any,
    MediaPlaceholder: any,
    MediaUpload: any,
    MediaUploadCheck: any,
    MultiBlocksSwitcher: any,
    MultiSelectScrollIntoView: any,
    NavigableToolbar: any,
    ObserveTyping: any,
    PreserveScrollInReorder: any,
    SkipToSelectedBlock: any,
    URLInput: any,
    URLInputButton: any,
    URLPopover: any,
    Warning: any,
    WritingFlow: any,
    withColorContext: any,
    withColors: any,
    withFontSizes: any,
    mediaUpload: any,
    cleanForSlug: any,
    transformStyles: any,
    getDefaultSettings: any
  }

  export interface WPTextControlProps {
    label?: string, 
    value?: string, 
    help?: string, 
    className?: string, 
    instanceId?: string, 
    onChange: (value: string) => void,
  }

  export interface WPDatePickerProps {
    currentDate: MomentInput,
    isInvalidDate?: (value: string) => boolean,
    onChange: (value: string) => void
  } 

  export interface WPSelectControlOption {
    label: string, 
    value: string
  }

  export interface WPSelectControlProps {
    label?: string, 
    value?: string, 
    help?: string, 
    className?: string, 
    instanceId?: string, 
    multiple?: boolean,
    onChange: (value: string | string[]) => void,
    options: WPSelectControlOption[]
  }

  export interface WPCheckboxControlProps {
    heading?: string,
		label?: string,
		help?: string,
		checked?: boolean
		onChange: (isChecked: boolean) => void
  }

  export interface WPTooltipProps {
    text: string
  }

  export interface WPComponents {
    TextControl: React.SFC<WPTextControlProps>,
    DatePicker: React.SFC<WPDatePickerProps>,
    SelectControl: React.SFC<WPSelectControlProps>,
    Tooltip: React.SFC<WPTooltipProps>,
    CheckboxControl: React.SFC<WPCheckboxControlProps>,

    Circle: any,
    G: any,
    Path: any,
    Polygon: any,
    Rect: any,
    SVG: any,
    Animate: any,
    Autocomplete: any,
    BaseControl: any,
    Button: any,
    ButtonGroup: any,
    ClipboardButton: any,
    ColorIndicator: any,
    ColorPalette: any,
    ColorPicker: any,
    Dashicon: any,
    DateTimePicker: any,
    TimePicker: any,
    Disabled: any,
    Draggable: any,
    DropZone: any,
    DropZoneProvider: any,
    Dropdown: any,
    DropdownMenu: any,
    ExternalLink: any,
    FocalPointPicker: any,
    FocusableIframe: any,
    FontSizePicker: any,
    FormFileUpload: any,
    FormToggle: any,
    FormTokenField: any,
    Icon: any,
    IconButton: any,
    KeyboardShortcuts: any,
    MenuGroup: any,
    MenuItem: any,
    MenuItemsChoice: any,
    Modal: any,
    ScrollLock: any,
    NavigableMenu: any,
    TabbableContainer: any,
    Notice: any,
    NoticeList: any,
    Panel: any,
    PanelBody: any,
    PanelHeader: any,
    PanelRow: any,
    Placeholder: any,
    Popover: any,
    QueryControls: any,
    RadioControl: any,
    RangeControl: any,
    ResizableBox: any,
    ResponsiveWrapper: any,
    SandBox: any,
    Spinner: any,
    ServerSideRender: any,
    TabPanel: any,
    TextareaControl: any,
    ToggleControl: any,
    Toolbar: any,
    ToolbarButton: any,
    TreeSelect: any,
    IsolatedEventContainer: any,
    createSlotFill: any,
    Slot: any,
    Fill: any,
    SlotFillProvider: any,
    navigateRegions: any,
    withConstrainedTabbing: any,
    withFallbackStyles: any,
    withFilters: any,
    withFocusOutside: any,
    withFocusReturn: any,
    FocusReturnProvider: any,
    withNotices: any,
    withSpokenMessages: any
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
  
  export interface WPBlockTypeEditParams {
    isSelected: boolean,
    attributes: any,
    setAttributes: (attributes: any) => void
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