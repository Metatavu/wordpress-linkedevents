import { LinkedEventsOptions } from "../types";

declare var linkedEventsOptions: LinkedEventsOptions;

/**
 * Utilities for LinkedEvents
 */
export default class LinkedEventsUtils {

  /**
   * Returns most appropriate localized value
   * 
   * @param localized localized item
   * @returns most appropriate localized value
   */
  public static getLocalizedValue(localized: { [key: string]: string }): string | null {
    const result = localized[LinkedEventsUtils.getCurrentLanguage()];
    if (result) {
      return result;
    }

    const languages: string[] = Object.keys(localized);
    if (!languages || !languages.length) {
      return null;
    }

    return localized[languages[0]];
  }

  /**
   * Returns current language
   * 
   * @returns current language
   */
  public static getCurrentLanguage(): string {
    return linkedEventsOptions.language;
  }

}