wp.MABOX = 'undefined' === typeof( wp.MABOX ) ? {} : wp.MABOX;
wp.MABOX.views = 'undefined' === typeof( wp.MABOX.views ) ? {} : wp.MABOX.views;
wp.MABOX.models = 'undefined' === typeof( wp.MABOX.models ) ? {} : wp.MABOX.models;
wp.MABOX.contructors = 'undefined' === typeof( wp.MABOX.contructors ) ? [] : wp.MABOX.contructors;

wp.MABOX.models.Settings = Backbone.Model.extend({
	initialize: function(){
        var model = this;

  		var view = new wp.MABOX.views.Settings({
  			model: this,
  			el: jQuery( '#mabox-container' )
  		});

  		this.set( 'view', view );
    },
    getAttribute: function( type ){
    	var value = this.get( type );

    	if ( 'undefined' == typeof value ) {
    		value = jQuery( '#' + type ).val();
    	}

    	return value;
    }
});

wp.MABOX.views.Settings = Backbone.View.extend({

	events: {
		// Settings specific events
        'keyup input':         'updateModel',
        'keyup textarea':      'updateModel',
        'change input':        'updateModel',
        'change textarea':     'updateModel',
        'blur textarea':       'updateModel',
        'change select':       'updateModel',
    },

    initialize: function( args ) {

    	// Check for Google Fonts
    	this.checkGoogleFonts();

    	this.listenTo( this.model, 'change:mabox_email', this.emailVisibility );

    	// Author website
    	this.listenTo( this.model, 'change:mabox_web', this.websiteVisibility );
    	this.listenTo( this.model, 'change:mabox_web_position', this.websitePosition );

    	// Social Icons
    	this.listenTo( this.model, 'change:mabox_hide_socials', this.socialsVisibility );
    	this.listenTo( this.model, 'change:mabox_colored', this.socialIconTypeVisibility );
    	this.listenTo( this.model, 'change:mabox_icons_style', this.socialIconTypeVisibility );
    	this.listenTo( this.model, 'change:mabox_social_hover', this.socialIconHover );
    	this.listenTo( this.model, 'change:mabox_box_long_shadow', this.socialIconShadow );
    	this.listenTo( this.model, 'change:mabox_box_thin_border', this.socialIconBorder );

    	// Avatar
    	this.listenTo( this.model, 'change:mabox_avatar_style', this.avatarStyle );
    	this.listenTo( this.model, 'change:mabox_avatar_hover', this.avatarHover );

    	// Padding
    	this.listenTo( this.model, 'change:mabox_box_padding_top_bottom', this.adjustPadding );
    	this.listenTo( this.model, 'change:mabox_box_padding_left_right', this.adjustPadding );

    	// Author Box Border
    	this.listenTo( this.model, 'change:mabox_box_border_width', this.adjustBorder );
    	this.listenTo( this.model, 'change:mabox_box_border', this.adjustBorder );

    	// Adjust Author name settings
    	this.listenTo( this.model, 'change:mabox_box_name_size', this.adjustAuthorName );
    	this.listenTo( this.model, 'change:mabox_box_name_font', this.adjustAuthorName );
    	this.listenTo( this.model, 'change:mabox_box_author_color', this.adjustAuthorName );

    	// Adjust Author website settings
    	this.listenTo( this.model, 'change:mabox_box_web_font', this.adjustAuthorWebsite );
    	this.listenTo( this.model, 'change:mabox_box_web_size', this.adjustAuthorWebsite );
    	this.listenTo( this.model, 'change:mabox_box_web_color', this.adjustAuthorWebsite );

    	// Adjust Author description settings
    	this.listenTo( this.model, 'change:mabox_box_desc_font', this.adjustAuthorDescription );
    	this.listenTo( this.model, 'change:mabox_box_desc_size', this.adjustAuthorDescription );
    	this.listenTo( this.model, 'change:mabox_box_author_p_color', this.adjustAuthorDescription );
    	this.listenTo( this.model, 'change:mabox_box_author_a_color', this.adjustAuthorDescription );
    	this.listenTo( this.model, 'change:mabox_desc_style', this.adjustAuthorDescription );

    	// Icon Size
    	this.listenTo( this.model, 'change:mabox_box_icon_size', this.adjustIconSize );

    	// Social Bar Background Color
    	this.listenTo( this.model, 'change:mabox_box_icons_back', this.changeSocialBarBackground );

    	// Author Box Background Color
    	this.listenTo( this.model, 'change:mabox_box_author_back', this.changeAuthorBoxBackground );

    	// Author Box Background Color
    	this.listenTo( this.model, 'change:mabox_box_icons_color', this.changeSocialIconsColor );

    },

    emailVisibility: function() {
    	var showEmail = wp.MABOX.Settings.get( 'mabox_email' );

    	if ( '1' == showEmail ) {
    		jQuery('.mabox-user_email').parent().show();
    	}else{
    		jQuery('.mabox-user_email').parent().hide();
    	}
    },

    socialsVisibility: function(){
    	var hideSocials = wp.MABOX.Settings.get( 'mabox_hide_socials' );

    	if ( '1' == hideSocials ) {
    		jQuery('.mabox-socials').hide();
    	}else{
    		jQuery('.mabox-socials').show();
    	}
    },

    websiteVisibility: function(){
    	var showWesite = wp.MABOX.Settings.get( 'mabox_web' );

    	if ( '1' == showWesite ) {
    		jQuery('.mabox-web').show();
    	}else{
    		jQuery('.mabox-web').hide();
    	}
    },

    websitePosition: function() {
    	var attribute = wp.MABOX.Settings.get( 'mabox_web_position' );
    	
    	if ( '1' == attribute ) {
    		jQuery('.mabox-web').addClass( 'mabox-web-position' );
    	}else{
    		jQuery('.mabox-web').removeClass( 'mabox-web-position' );
    	}
    },

    socialIconTypeVisibility: function() {
    	var iconType = wp.MABOX.Settings.getAttribute( 'mabox_colored' ),
    		iconStyle = wp.MABOX.Settings.getAttribute( 'mabox_icons_style' );

    	jQuery('.mabox-socials').removeClass( 'mabox-show-simple mabox-show-circle mabox-show-square' );
    	if ( '1' == iconType ) {
    		if ( '1' == iconStyle ) {
    			jQuery('.mabox-socials').addClass( 'mabox-show-circle' );
    		}else{
    			jQuery('.mabox-socials').addClass( 'mabox-show-square' );
    		}
    	}else{
    		jQuery('.mabox-socials').addClass( 'mabox-show-simple' );
    	}

    },

    socialIconHover: function() {
    	var attribute = wp.MABOX.Settings.get( 'mabox_social_hover' );
    	
    	if ( '1' == attribute ) {
    		jQuery('.mabox-socials').addClass( 'mabox-rotate-icons' );
    	}else{
    		jQuery('.mabox-socials').removeClass( 'mabox-rotate-icons' );
    	}
    },

    socialIconShadow: function() {
    	var attribute = wp.MABOX.Settings.get( 'mabox_box_long_shadow' );
    	
    	if ( '1' == attribute ) {
    		jQuery('.mabox-socials').removeClass( 'without-long-shadow' );
    	}else{
    		jQuery('.mabox-socials').addClass( 'without-long-shadow' );
    	}
    },

    socialIconBorder: function() {
    	var attribute = wp.MABOX.Settings.get( 'mabox_box_thin_border' );
    	
    	if ( '1' == attribute ) {
    		jQuery('.mabox-socials').addClass( 'mabox-icons-with-border' );
    	}else{
    		jQuery('.mabox-socials').removeClass( 'mabox-icons-with-border' );
    	}
    },

    avatarStyle: function() {
    	var attribute = wp.MABOX.Settings.get( 'mabox_avatar_style' );
    	
    	if ( '1' == attribute ) {
    		jQuery('.mabox-gravatar').addClass( 'mabox-round-image' );
    	}else{
    		jQuery('.mabox-gravatar').removeClass( 'mabox-round-image' );
    	}
    },

    avatarHover: function() {
    	var attribute = wp.MABOX.Settings.get( 'mabox_avatar_hover' );
    	
    	if ( '1' == attribute ) {
    		jQuery('.mabox-gravatar').addClass( 'mabox-rotate-img' );
    	}else{
    		jQuery('.mabox-gravatar').removeClass( 'mabox-rotate-img' );
    	}
    },

    adjustPadding: function() {
    	var paddingTopBottom = wp.MABOX.Settings.getAttribute( 'mabox_box_padding_top_bottom' ),
    		paddingLeftRight = wp.MABOX.Settings.getAttribute( 'mabox_box_padding_left_right' );

    	jQuery( '.mabox-wrap' ).css({ 'padding' : paddingTopBottom + ' ' + paddingLeftRight });

    },

    adjustBorder: function() {
    	var border = wp.MABOX.Settings.getAttribute( 'mabox_box_border_width' ),
    		borderColor = wp.MABOX.Settings.getAttribute( 'mabox_box_border' );

    	if ( '' == borderColor ) {
    		borderColor = 'inherit';
    	}

    	jQuery( '.mabox-wrap' ).css({ 'border-width' : border, 'border-color' : borderColor });
    	jQuery( '.mabox-wrap .mabox-socials' ).css({ 'border-width' : border, 'border-color' : borderColor });
    },

    adjustAuthorName: function() {
    	var font = wp.MABOX.Settings.getAttribute( 'mabox_box_name_font' ),
    		size = wp.MABOX.Settings.getAttribute( 'mabox_box_name_size' ),
    		color = wp.MABOX.Settings.getAttribute( 'mabox_box_author_color' ),
    		lineHeight = parseInt( size ) + 7;

    	if ( '' == color ) {
    		color = 'inherit';
    	}

    	if ( '' == font || 'None' == font ) {
    		font = 'inherit';
    	}else{
    		this.loadGoogleFonts( font );
    	}


    	jQuery( '.mabox-wrap .mabox-authorname a, .mabox-wrap .mabox-authorname span' ).css({ 'font-family' : font, 'color' : color, 'font-size': size, 'line-height' : lineHeight.toString() + 'px' });
    },

    adjustAuthorWebsite: function() {
    	var font = wp.MABOX.Settings.getAttribute( 'mabox_box_web_font' ),
    		size = wp.MABOX.Settings.getAttribute( 'mabox_box_web_size' ),
    		color = wp.MABOX.Settings.getAttribute( 'mabox_box_web_color' ),
    		lineHeight = parseInt( size ) + 7;

    	if ( '' == color ) {
    		color = 'inherit';
    	}

    	if ( '' == font || 'None' == font ) {
    		font = 'inherit';
    	}else{
    		this.loadGoogleFonts( font );
    	}


    	jQuery( '.mabox-wrap .mabox-web a' ).css({ 'font-family' : font, 'color' : color, 'font-size': size, 'line-height' : lineHeight.toString() + 'px' });
    },

    adjustAuthorDescription: function() {
    	var font = wp.MABOX.Settings.getAttribute( 'mabox_box_desc_font' ),
    		size = wp.MABOX.Settings.getAttribute( 'mabox_box_desc_size' ),
    		color = wp.MABOX.Settings.getAttribute( 'mabox_box_author_p_color' ),
    		link_color = wp.MABOX.Settings.getAttribute( 'mabox_box_author_a_color' ),
    		style = wp.MABOX.Settings.getAttribute( 'mabox_desc_style' ),
    		lineHeight = parseInt( size ) + 7;

    	if ( '' == color ) {
    		color = 'inherit';
    	}

    	if ( '' == link_color ) {
    		link_color = 'inherit';
    	}

    	if ( '' == font || 'None' == font ) {
    		font = 'inherit';
    	}else{
    		this.loadGoogleFonts( font );
    	}

    	if ( '0' == style ) {
    		style = 'normal';
    	}else{
    		style = 'italic';
    	}


    	jQuery( '.mabox-wrap .mabox-desc p, .mabox-wrap .mabox-desc' ).css({ 'font-family' : font, 'color' : color, 'font-size': size, 'line-height' : lineHeight.toString() + 'px', 'font-style' : style });
    	jQuery( '.mabox-wrap .mabox-desc a' ).css({ 'font-family' : font, 'color' : link_color, 'font-size': size, 'line-height' : lineHeight.toString() + 'px', 'font-style' : style });
    },

    adjustIconSize: function() {
    	var size = this.model.get( 'mabox_box_icon_size' ),
    		size2x = parseInt( size ) * 2;

    	jQuery( '.mabox-wrap .mabox-socials a.mabox-icon-grey svg' ).css({ 'width' : size, 'height' : size });
    	jQuery( '.mabox-wrap .mabox-socials a.mabox-icon-color svg' ).css({ 'width' : size2x.toString() + 'px', 'height' : size2x.toString() + 'px' });
    },

    changeSocialBarBackground: function() {
    	var color = this.model.get( 'mabox_box_icons_back' );

    	if ( '' == color ) {
    		color = 'inherit';
    	}

    	jQuery( '.mabox-wrap .mabox-socials' ).css({ 'background-color' : color });

    },

    changeAuthorBoxBackground: function() {
    	var color = this.model.get( 'mabox_box_author_back' );

    	if ( '' == color ) {
    		color = 'inherit';
    	}

    	jQuery( '.mabox-wrap' ).css({ 'background-color' : color });

    },

    changeSocialIconsColor: function() {
    	var color = this.model.get( 'mabox_box_icons_color' );

    	if ( '' == color ) {
    		color = 'inherit';
    	}

    	jQuery( '.mabox-wrap .mabox-socials a.mabox-icon-grey svg' ).css({ 'color' : color, 'fill' : color });

    },

    updateModel: function( event ) {
    	var value, setting;


    	// Check if the target has a data-field. If not, it's not a model value we want to store
        if ( undefined === event.target.id ) {
            return;
        }

        // Update the model's value, depending on the input type
        if ( event.target.type == 'checkbox' ) {
            value = ( event.target.checked ? '1' : '0' );
        } else {
            value = event.target.value;
        }

        // Update the model
        this.model.set( event.target.id, value );

    },

    checkGoogleFonts: function() {
    	var authorFont = this.model.getAttribute( 'mabox_box_name_font' ),
    		webFont = this.model.getAttribute( 'mabox_box_web_font' ),
    		descriptionFont = this.model.getAttribute( 'mabox_box_desc_font' );

    	if (  '' != authorFont && 'None' != authorFont  ) {
    		this.loadGoogleFonts( authorFont );
    	}

    	if (  '' != webFont && 'None' != webFont  ) {
    		this.loadGoogleFonts( webFont );
    	}

    	if (  '' != descriptionFont && 'None' != descriptionFont  ) {
    		this.loadGoogleFonts( descriptionFont );
    	}

    },

    loadGoogleFonts: function( font ) {
    	if ( ! wp.MABOX.Fonts.includes( font ) ) {
    		wp.MABOX.Fonts.push( font );
    		WebFont.load({
			    google: {
			      families: [ font ]
			    }
			});
    	}
    },
   
});

jQuery( document ).ready(function(){

	wp.MABOX.Fonts = [];
	wp.MABOX.Settings = new wp.MABOX.models.Settings();

});