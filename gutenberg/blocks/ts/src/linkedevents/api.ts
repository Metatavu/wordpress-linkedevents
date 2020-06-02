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
    this.apiUrl = apiUrl;
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
    const result = await (await fetch(`${this.apiUrl}/${url}`, {
      headers: {
        "Accept": "application/json"
      }
    })).json();

    return result;
  }

}