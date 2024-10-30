(function( $ ) {

	'use strict';
	var MABox = {};

	var mediaControl = {

		// Initializes a new media manager or returns an existing frame.
		// @see wp.media.featuredImage.frame()
		selector: null,
		size: null,
		container: null,
		frame: function() {
			if ( this._frame ) {
				return this._frame;

			}

			this._frame = wp.media( {
				title: 'Media',
				button: {
					text: 'Update'
				},
				multiple: false
			} );

			this._frame.on( 'open', this.updateFrame ).state( 'library' ).on( 'select', this.select );

			return this._frame;

		},

		select: function() {
			var context = $( '#mabox-custom-profile-image' ),
				input = context.find( '#mabox-custom-image' ),
				image = context.find( 'img' ),
				attachment = mediaControl.frame().state().get( 'selection' ).first().toJSON();

			image.attr( 'src', attachment.url );
			input.val( attachment.url );

		},

		init: function() {
			var context = $( '#mabox-custom-profile-image' );
			context.on( 'click', '#mabox-add-image', function( e ) {
				e.preventDefault();
				mediaControl.frame().open();
			} );

			context.on( 'click', '#mabox-remove-image', function( e ) {
				var context = $( '#mabox-custom-profile-image' ),
					input = context.find( '#mabox-custom-image' ),
					image = context.find( 'img' );

				e.preventDefault();

				input.val( '' );
				image.attr( 'src', image.data( 'default' ) );
			} );

		}

	};

	$( document ).ready( function() {
		/*if ( $( '#description' ).length > 0 ) {
			
			// /console.log( wp.editor.get('description') );
			wp.editor.initialize( 'description', {
				tinymce: {
					wpautop: true
				},
				quicktags: true
			} );
		}

        // WYSIWYG editor for textarea with class mabox-editor.
        var mabox_editor = jQuery('.mabox-editor');
        if (mabox_editor.length == 1) {
            var mabox_editor_id = mabox_editor.attr('id');
            wp.editor.initialize(mabox_editor_id, {
                tinymce: {
                    wpautop: true,
                    browser_spellcheck: true,
                    mediaButtons: false,
                    wp_autoresize_on: true,
                    toolbar1: 'bold,italic,bullist,numlist,link,strikethrough',
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                            jQuery(mabox_editor).trigger('change');
                        });
                    }
                },
                quicktags: true
            });

        } else if (mabox_editor.length > 1) {
            mabox_editor.each(function () {
                var mabox_editor_id = jQuery(this).attr('id');
                wp.editor.initialize(mabox_editor_id, {
                    tinymce: {
                        wpautop: true,
                        browser_spellcheck: true,
                        mediaButtons: false,
                        wp_autoresize_on: true,
                        toolbar1: 'bold,italic,link,strikethrough',
                        setup: function (editor) {
                            editor.on('change', function () {
                                editor.save();
                                jQuery(this).trigger('change');
                            });
                        }
                    },
                    quicktags: true
                });
            });

        }*/

		// Add Social Links
		var context = $( '#mabox-settings' );
        context.find( '.maboxfield[type="checkbox"]' ).on( 'click', function() {
	        var elements = context.find( '.show_if_' + $( this ).attr( 'id' ) );
            if( $( this ).is(':checked') ) {
	            elements.show();
	        } else {
	            elements.hide();
	        }
        });

		// Add Social Links
		$( '.mabox-add-social-link a' ).click( function( e ) {

			e.preventDefault();

			if ( undefined === MABox.html ) {
				MABox.html = '<tr> <th><span class="mabox-drag"></span><select name="mabox-social-icons[]">';
				$.each( MABoxScriptsData.socialIcons, function( key, name ) {
					MABox.html = MABox.html + '<option value="' + key + '">' + name + '</option>';
				} );
				MABox.html = MABox.html + '</select></th><td><input name="mabox-social-links[]" type="text" class="regular-text"><span class="dashicons dashicons-trash"></span><td></tr>';
			}

			$( '#mabox-social-table' ).append( MABox.html );

		} );

		// Remove Social Link
		$( '#mabox-social-table' ).on( 'click', '.dashicons-trash', function() {
			var row = $( this ).parents( 'tr' );
			row.fadeOut( 'slow', function() {
				row.remove();
			} );
		} );

		mediaControl.init();

	} );

})( jQuery );
