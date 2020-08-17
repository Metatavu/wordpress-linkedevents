/**
 * Interface describing JSONLDObject
 */
export interface JSONLDObject {
  '@id': string;
  '@context'?: string;
  '@type'?: string;
}

/**
 * Interface describing Keyword
 */
export interface Keyword extends JSONLDObject {
  id?: string;
  alt_labels?: Array<string>;
  createdTime?: string;
  last_modified_time?: string;
  aggregate?: boolean;
  deprecated?: boolean;
  n_events?: number;
  data_source?: string;
  publisher?: string;
  name?: {[key: string]: string};
}

/**
 * Interface describing KeywordSet
 */
export interface KeywordSet extends JSONLDObject {
  id: string;
  name:  {[key: string]: string};
  usage: number | string;
  created_time: string;
  last_modified_time: string;
  data_source: string;
  image: Image | null;
  organization: string;
  keywords: Array<Keyword>;
}

/**
 * Interface describing Image
 */
export interface Image extends JSONLDObject {
  id: number;
  license: string;
  name: string;
  created_time: string;
  last_modified_time: string;
  url: string;
  cropping: string;
  photographer_name: string;
  data_source: string;
  publisher: string;
}

/**
 * API client for LinkedEvents API
 */
export class LinkedEventsApi {
  
  private apiUrl: string;

  /**
   * Constructor
   * 
   * @param apiUrl LinkedEvents API URL
   */
  constructor(apiUrl: string) {
    this.apiUrl = apiUrl.slice(-1) === '/' ? apiUrl : apiUrl + '/';
  }

  /**
   * Searches places by free text
   * 
   * @param search search query
   * @returns found places
   */
  public listPlaces = async (search: string): Promise<any[]> => {
    const text = encodeURIComponent(search) ;
    const result = await this.fetchFromLinkedEvents(`place/?text=${text}`);

    if (!result) {
      return [];
    }

    return result.data;
  } 

  /**
   * Finds a place by id from the API
   * 
   * @param id place id
   * @return place or null if not found
   */
  public findPlace = async (id: string): Promise<any> => {
    const result = await this.fetchFromLinkedEvents(`place/${id}`);
    if (!result || !result.id) {
      return null;
    }
    
    return result;
  } 

  /**
   * Searches keywords by free text
   * 
   * @param search search query
   * @returns found keywords
   */
  public listKeywords = async (options?: { text?: string }): Promise<any[]> => {
    const queryParams = this.getQueryParams(options);
    const result = await this.fetchFromLinkedEvents(`keyword/?${queryParams}`);
    if (!result) {
      return [];
    }

    return result.data;
  } 

  /**
   * Finds a keyword by id from the API
   * 
   * @param id keyword id
   * @return keyword or null if not found
   */
  public findKeyword = async (id: string): Promise<any> => {
    const result = await this.fetchFromLinkedEvents(`keyword/${id}`);
    if (!result || !result.id) {
      return null;
    }

    return result;
  } 

  /**
   * Searches keyword_sets by free text
   * 
   * @param options search query
   * @returns Promise for found keywordsets
   */
  public listKeywordSets = async (options?: { include?: string }): Promise<KeywordSet[]> => {
    const queryParams = this.getQueryParams(options);
    const result = await this.fetchFromLinkedEvents(`keyword_set/?${queryParams}`);
    if (!result) {
      return [];
    }

    return result.data;
  } 
  

  /**
   * Translates object into query string
   * 
   * @param queryParams object
   * @return query string
   */
  private getQueryParams(queryParams?: { [key: string]: string }): string {
    if (!queryParams) {
      return "";
    }

    return Object.keys(queryParams).map((key) => {
      const value = encodeURIComponent(queryParams[key]);
      return `${key}=${value}`;
    }).join("&");
  }

  /**
   * Makes an query into the LinkedEvents API
   * 
   * @param url query URL without the server part
   * @returns result
   */
  private fetchFromLinkedEvents = async (url: string) => {
    const result = await (await fetch(`${this.apiUrl}${url}`, {
      headers: {
        "Accept": "application/json"
      }
    })).json();

    return result;
  }

}