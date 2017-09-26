/* global ajaxurl */
(function ($) {
  'use strict';
  
  $.widget("custom.linkedeventsImageSelector", {
    
    _create : function() {
      this._mediaUploader = wp.media({
        title: this.element.attr('data-title'),
        button: {
          text: this.element.attr('data-button')
        },
        library: {
					type: 'image'
				},
        multiple: false
      });
      
      this.element.on('click', 'a', $.proxy(this._onLinkClick, this));
      this.element.on('change', 'input', $.proxy(this._onUrlChange, this));
      this.element.find('img').on('load', $.proxy(this._onImageLoad, this));
      this.element.find('img').on('error', $.proxy(this._onImageLoadError, this));
      
      this._mediaUploader.on('select', $.proxy(this._onMediaSelect, this));
      
      if (!$.trim(this.element.find('input').val())) {
        this.element.addClass('no-image');
      }
    },
    
    _onUrlChange: function () {
      var src = $.trim(this.element.find('input').val());
      if (!src) {
        this.element.removeClass('broken-image');
        this.element.addClass('no-image');
      } else {
        this.element.find('img').attr('src', src);
      }
    },
    
    _onLinkClick: function () {
      this._mediaUploader.open();
    },
    
    _onMediaSelect: function () {
      var json = this._mediaUploader.state().get("selection").first().toJSON();
      this.element.find('img').attr('src', json.url);
      this.element.find('input').val(json.url);
    },
    
    _onImageLoad: function () {
      this.element.removeClass('broken-image');
      this.element.removeClass('no-image');
    },
    
    _onImageLoadError: function () {
      if (!$.trim(this.element.find('input').val())) {
        this.element.removeClass('broken-image');
        this.element.addClass('no-image');
      } else {
        this.element.addClass('broken-image');
        this.element.removeClass('no-image');
      }
    }
    
  });
  
  $.widget("custom.linkedeventsMultivalueAutocomplete", {
    options: {
    },

    _create : function() {
      this._searchTarget = this.element.attr('data-search-target');
      
      this.element.autocomplete({
        select: $.proxy(this._onSelect, this),
        source: $.proxy(this._onSource, this),
        change: $.proxy(this._onChange, this)
      });
      
      this._tagCheckList = $('<div>')
        .addClass('tagchecklist')
        .insertAfter(this.element);

      this._tagCheckList.on('click', '.ntdelbutton', $.proxy(this._onValueRemoveClick, this));
      
      this._loadValues();
    },
    
    _loadValues: function () {
      var valuesAttr = this.element.attr('data-values');
      if (valuesAttr) {
        var values = JSON.parse(valuesAttr);
        for (var i = 0; i < values.length; i++) {
          this._addItem(values[i].value, values[i].label);
        }
        
        this._updateValues();
      }
    },
    
    _addItem: function (value, label) {
      $('<span>')
        .addClass('linkedevents-multivalue-autocomplete-item')
        .attr('data-value', value)
        .attr('data-label', label)
        .append($('<button>').addClass('ntdelbutton').append($('<span>').addClass('remove-tag-icon')))
        .append('&nbsp;')
        .append(label)
        .appendTo(this._tagCheckList);
    },
    
    _updateValues: function () {
      const values = this._tagCheckList.find('.linkedevents-multivalue-autocomplete-item').map(function (index, element) {
        return $(element).attr('data-value');
      }).get();
      
      var inputName = this.element.attr('data-name');
      $('input[name="' + inputName + '"]').val(values.join(','));
    },
    
    _onValueRemoveClick: function (event) {
      event.preventDefault();
      $(event.target)
        .closest('.linkedevents-multivalue-autocomplete-item')
        .remove();      

      this._updateValues();
    },
      
    _onSelect: function (event, ui) {
      event.preventDefault();
      var item = ui.item;
      this.element.val('');
      this._addItem(item.value, item.label);
      this._updateValues();
    },

    _onSource: function (input, callback) {
      $.get(ajaxurl + '?action=' + this._searchTarget + '&q=' + input.term, function(items) {
        callback($.map(items, function (item) {
          return { 
            value: item.value,
            label: item.label
          }; 
        }));
      }, "json");
    },
    
    _onChange: function (input, callback) {
      this.element.val('');
    }
  });
  
  $.widget("custom.linkedeventsAutocomplete", {
    options: {
    },

    _create : function() {
      this._searchTarget = this.element.attr('data-search-target');
      this._selected = false;
      
      this.element.on("focus", $.proxy(this._onFocus, this));
      
      this.element.autocomplete({
        select: $.proxy(this._onSelect, this),
        source: $.proxy(this._onSource, this),
        change: $.proxy(this._onChange, this)
      });
    },
    
    _getInput: function () {
      var inputName = this.element.attr('data-name');
      return $('input[name="' + inputName + '"]');
    },
    
    _onFocus: function () {
      this._originalValue = this._getInput().val();
      this._originalLabel = this.element.val();
      this._selected = false;
    },
      
    _onSelect: function (event, ui) {
      event.preventDefault();
      var item = ui.item;
      var id = item.value;
      
      this.element.val(item.label);
      this._getInput().val(id);
      this._selected = true;
    },

    _onSource: function (input, callback) {
      $.get(ajaxurl + '?action=' + this._searchTarget + '&q=' + input.term, function(items) {
        callback($.map(items, function (item) {
          return { 
            value: item.value,
            label: item.label
          }; 
        }));
      }, "json");
    },
    
    _onChange: function (input, callback) {
      if (!this._selected) {
        this._getInput().val(this._originalValue);
        this.element.val(this._originalLabel);
      }
      
      this._originalValue = null;
      this._originalLabel = null;
      this._selected = false;
    }
  });
  
  $.widget("custom.linkedeventsGeoInput", {
    
    options: {
      defaultLatitude: 61.9241,
      defaultLongitude: 25.7482
    },
    
    _create : function() {
      this._initMap();
    },
    
    _initMap: function () {
      var latitude = this._getLatitude();
      var longitude = this._geLongitude();
      
      this.element.find('.linkedevents-geoposition-map').locationpicker({
        location: {
          latitude: latitude || this.options.defaultLatitude,
          longitude: longitude || this.options.defaultLongitude
        },
        radius: 0,
        inputBinding: {
          latitudeInput: this._getLatitudeInput(),
          longitudeInput: this._getLongitudeInput(),
          locationNameInput: this._getSearchInput()
        },
        enableAutocomplete: true,
        onchanged: $.proxy(this._onPickerChanged, this)
      });
      
      this.element.find('.linkedevents-geoinput-search').hide();
      this.element.on('click', '.linkedevents-search', $.proxy(this._onSearchClick, this));
      $(window).on('click', $.proxy(this._onWindowClick, this));
    },
    
    _getLatitude: function () {
      return parseFloat(this._getLatitudeInput().val());
    },
    
    _geLongitude: function () {
      return parseFloat(this._getLongitudeInput().val());
    },
    
    _setLatitude: function (latitude) {
      this._getLatitudeInput().val(latitude);
    },
    
    _setLongitude: function (longitude) {
      this._getLongitudeInput().val(longitude);
    },
    
    _getInput: function (type) {
      var name = this.element.attr('data-input');
      return $('input[name="' + name + '-' + type + '"]');
    },
    
    _getSearchInput: function () {
      return this._getInput('search');
    },
    
    _getStreetAddressInput: function (language) {
      return this._getInput('street-address-' + language);
    },
    
    _getAddressRegionInput: function () {
      return this._getInput('address-region');
    },
    
    _getPostalCodeInput: function () {
      return this._getInput('postal-code');
    },
    
    _getPoBoxInput: function () {
      return this._getInput('po-box');
    },
    
    _getAddressLocalityInput: function (language) {
      return this._getInput('address-locality-' + language);
    },
    
    _getLatitudeInput: function () {
      return this._getInput('latitude');
    },
    
    _getLongitudeInput: function () {
      return this._getInput('longitude');
    },
    
    _onCurrentPosition: function (position) {
      this._initMap(position.coords.latitude, position.coords.longitude);
    },
    
    _onPickerChanged: function (currentLocation, radius, isMarkerDropped) {
      var map = this.element.find('.linkedevents-geoposition-map').locationpicker('map');
      var addressComponents = map.location.addressComponents;
      this._getStreetAddressInput('fi').val(addressComponents.addressLine1);
      this._getPostalCodeInput().val(addressComponents.postalCode);
      this._getAddressLocalityInput('fi').val(addressComponents.city);
      this._setLatitude(currentLocation.latitude);
      this._setLongitude(currentLocation.longitude);
      this.element.find('.linkedevents-geoinput-search').hide();
    },
    
    _onSearchClick: function (event) {
      event.preventDefault();
      this.element.find('.linkedevents-geoinput-search').show().focus();
    },
    
    _onWindowClick: function (event) {
      if (!$(event.target).closest('.linkedevents-geoinput-search, .linkedevents-search').length) {
        this.element.find('.linkedevents-geoinput-search').hide();
      }
    }
    
  });
  
  $(document).ready(function() {
    $(".linkedevents-autocomplete").linkedeventsAutocomplete();
    $(".linkedevents-multivalue-autocomplete").linkedeventsMultivalueAutocomplete();
    $('.linkedevents-image-selector').linkedeventsImageSelector();
    $('.linkedevents-geoinput').linkedeventsGeoInput();
    
    var locale = $('input[name="locale"]').val();
    if (locale !== 'fi') {
      locale = 'en';
    }
    
    flatpickr('.linkedevents-datetimepicker', {
      "locale": locale,
      "allowInput": true,
      "altInput": true,
      "enableTime": true,
      "time_24hr": true
    });
    
    flatpickr('.linkedevents-timepicker', {
      "locale": locale,
      "allowInput": true,
      "noCalendar": true,
      "enableTime" : true,
      "time_24hr": true
    });
    
    flatpickr('.linkedevents-datepicker', {
      "locale": locale,
      "allowInput": true,
      "altInput": true,
      "time_24hr": true
    });
  });

})(jQuery);