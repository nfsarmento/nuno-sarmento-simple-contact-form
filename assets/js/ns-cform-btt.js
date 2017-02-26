(function() {
     /* Register the buttons */
     tinymce.create('tinymce.plugins.NsButtons', {
          init : function(ed, url) {
               /**
               * Inserts shortcode content
               */
               ed.addButton( 'ns_cform_button', {
                    title : 'Add Contact Form',
                    icon  : 'icon dashicons-before dashicons-feedback',
                    onclick : function() {
                         ed.selection.setContent('[ns_contact_form]');
                    }
               });

          },
          createControl : function(n, cm) {
               return null;
          },
     });
     /* Start the buttons */
     tinymce.PluginManager.add( 'ns_cform_button_script', tinymce.plugins.NsButtons );
})();
