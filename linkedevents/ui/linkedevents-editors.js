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
      this.element.addClass('broken-image');
      this.element.removeClass('no-image');
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
  
  $(document).ready(function() {
    $(".linkedevents-autocomplete").linkedeventsAutocomplete();
    $(".linkedevents-multivalue-autocomplete").linkedeventsMultivalueAutocomplete();
    $('.linkedevents-image-selector').linkedeventsImageSelector();
    
    flatpickr('.linkedevents-datetimepicker', {
      "altInput": true,
      "dateFormat": "U",
      "enableTime": true,
      "time_24hr": true
    });
  });

})(jQuery);