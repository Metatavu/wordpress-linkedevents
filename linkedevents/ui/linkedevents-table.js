/* global ajaxurl */
(function ($) {
  'use strict';
  
  $(document).on('click', '.linkedevents-delete-link', function (event) {  
    var link = $(event.target).closest('.linkedevents-delete-link');
    var id = $(event.target).attr('data-id');
    var action = $(event.target).attr('data-action');
    
    $('<div>')
      .attr('title', link.attr('data-dialog-title'))
      .text(link.attr('data-dialog-content'))
      .dialog({
        buttons : [{
          text: link.attr('data-dialog-confirm'),
          click: function() {
            $.post(ajaxurl + '?action=' + action + '&id=' + id, function() {
              window.location.reload(true);
            });
          }
        }, {
          text: link.attr('data-dialog-cancel'),
          click: function() {
            $( this ).dialog( "close" );
          }
        }]
      })
      .dialog("open");
  });
  
})(jQuery);