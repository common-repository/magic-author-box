<?php

if ( isset( $mabox_options['mabox_colored'] ) && '1' == $mabox_options['mabox_colored'] ) {
    $mabox_color = 'mabox-colored';
} else {
    $mabox_color = '';
}


if ( isset( $mabox_options['mabox_web_position'] ) && '0' != $mabox_options['mabox_web_position'] ) {
    $mabox_web_align = 'mabox-web-position';
} else {
    $mabox_web_align = '';
}


if ( isset( $mabox_options['mabox_web_target'] ) && '1' == $mabox_options['mabox_web_target'] ) {
    $mabox_web_target = '_blank';
} else {
    $mabox_web_target = '_self';
}


if ( isset( $mabox_options['mabox_web_rel'] ) && '1' == $mabox_options['mabox_web_rel'] ) {
    $mabox_web_rel = 'rel="nofollow"';
} else {
    $mabox_web_rel = '';
}

$mabox_author_link = sprintf( '<a href="%s" class="vcard author" rel="author" itemprop="url"><span class="fn" itemprop="name">%s</span></a>', esc_url( get_author_posts_url( $mabox_author_id ) ), esc_html( get_the_author_meta( 'display_name', $mabox_author_id ) ) );
$author_description = apply_filters( 'mabox_user_description', get_the_author_meta( 'description', $mabox_author_id ), $mabox_author_id );

if ( '' != $author_description || isset( $mabox_options['mabox_no_description'] ) && '0' == $mabox_options['mabox_no_description'] ) {
    // hide the author box if no description is provided
    $show_guest_only = ( get_post_meta( get_the_ID(), '_disable_mabox_author_here', true ) ? get_post_meta( get_the_ID(), '_disable_mabox_author_here', true ) : "false" );
    
    if ( $show_guest_only != "on" ) {
        echo  '<div class="mabox-wrap" itemtype="http://schema.org/Person" itemscope itemprop="author">' ;
        // start mabox-wrap div
        // author box gravatar
        echo  '<div class="mabox-gravatar">' ;
        $custom_profile_image = get_the_author_meta( 'mabox-profile-image', $mabox_author_id );
        
        if ( '' != $custom_profile_image ) {
            $mediaid = attachment_url_to_postid( $custom_profile_image );
            $alt = ( $mediaid ? get_post_meta( $mediaid, '_wp_attachment_image_alt', true ) : '' );
            $link = null;
            $nofollow = '';
            $link = get_author_posts_url( $mabox_author_id );
            if ( $link != null ) {
                echo  "<a {$nofollow} href='" . $link . "'>" ;
            }
            echo  '<img src="' . esc_url( $custom_profile_image ) . '" alt="' . esc_attr( $alt ) . '" itemprop="image">' ;
            if ( $link != null ) {
                echo  "</a>" ;
            }
        } else {
            echo  get_avatar(
                get_the_author_meta( 'user_email', $mabox_author_id ),
                '100',
                '',
                '',
                array(
                'extra_attr' => 'itemprop="image"',
            )
            ) ;
        }
        
        echo  '</div>' ;
        // author box name
        echo  '<div class="mabox-authorname">' ;
        echo  apply_filters(
            'mabox_author_html',
            $mabox_author_link,
            $mabox_options,
            $mabox_author_id
        );
        if ( is_user_logged_in() && get_current_user_id() == $mabox_author_id ) {
            echo  '<a class="mabox-profile-edit" target="_blank" href="' . get_edit_user_link() . '"> ' . esc_html__( 'Edit profile', 'magic-author-box' ) . '</a>' ;
        }
        echo  '</div>' ;
        // author box description
        echo  '<div class="mabox-desc">' ;
        echo  '<div itemprop="description">' ;
        $author_description = wptexturize( $author_description );
        $author_description = wpautop( $author_description );
        echo  wp_kses_post( $author_description ) ;
        if ( '' == $author_description && is_user_logged_in() && $mabox_author_id == get_current_user_id() ) {
            echo  '<a target="_blank" href="' . admin_url() . 'profile.php?#wp-description-wrap">' . esc_html__( 'Add Biographical Info', 'magic-author-box' ) . '</a>' ;
        }
        echo  '</div>' ;
        echo  '</div>' ;
        if ( is_single() || is_page() ) {
            
            if ( get_the_author_meta( 'user_url' ) != '' && '1' == $mabox_options['mabox_web'] ) {
                // author website on single
                echo  '<div class="mabox-web ' . esc_attr( $mabox_web_align ) . '">' ;
                echo  '<a href="' . esc_url( get_the_author_meta( 'user_url', $mabox_author_id ) ) . '" target="' . esc_attr( $mabox_web_target ) . '" ' . $mabox_web_rel . '>' . esc_html( MABOX_Helper::strip_prot( get_the_author_meta( 'user_url', $mabox_author_id ) ) ) . '</a>' ;
                echo  '</div>' ;
            }
        
        }
        if ( is_author() || is_archive() ) {
            
            if ( get_the_author_meta( 'user_url' ) != '' ) {
                // force show author website on author.php or archive.php
                echo  '<div class="mabox-web ' . esc_attr( $mabox_web_align ) . '">' ;
                echo  '<a href="' . esc_url( get_the_author_meta( 'user_url', $mabox_author_id ) ) . '" target="' . esc_attr( $mabox_web_target ) . '" ' . $mabox_web_rel . '>' . esc_html( MABOX_Helper::strip_prot( get_the_author_meta( 'user_url', $mabox_author_id ) ) ) . '</a>' ;
                echo  '</div>' ;
            }
        
        }
        // author box clearfix
        echo  '<div class="clearfix"></div>' ;
        // author box social icons
        $author = get_userdata( $mabox_author_id );
        $show_social_icons = apply_filters( 'mabox_hide_social_icons', true, $author );

        //Check if user capable to manange option
        if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
            echo  '<div class="mabox-edit-settings">' ;
            echo  '<a target="_blank" href="' . admin_url( 'edit.php?post_type=mabox_template&page=mabox-settings' ) .'">'. esc_html__( 'Settings', 'magic-author-box' ) . '<i class="dashicons dashicons-admin-settings"></i></a>' ;
            echo  '</div>' ;
        }

        $show_email = ( isset( $mabox_options['mabox_email'] ) && '0' == $mabox_options['mabox_email'] ? false : true );
        $social_links = MABOX_Helper::get_user_social_links( $mabox_author_id, $show_email );
        if ( empty($social_links) && is_user_logged_in() && $mabox_author_id == get_current_user_id() ) {
            echo  '<a target="_blank" href="' . admin_url() . 'profile.php?#mabox-social-table">' . esc_html__( 'Add Social Links', 'magic-author-box' ) . '</a>' ;
        }

        if ( isset( $mabox_options['mabox_hide_socials'] ) && '0' == $mabox_options['mabox_hide_socials'] && $show_social_icons && !empty($social_links) ) {
            // hide social icons div option
            echo  '<div class="mabox-socials ' . esc_attr( $mabox_color ) . '">' ;
            foreach ( $social_links as $social_platform => $social_link ) {
                if ( 'user_email' == $social_platform ) {
                    $social_link = 'mailto:' . antispambot( $social_link );
                }
                if ( 'whatsapp' == $social_platform ) {
                    $social_link = 'https://wa.me/' . $social_link;
                }
                if ( 'phone' == $social_platform ) {
                    $social_link = 'tel:' . $social_link;
                }
                if ( 'telegram' == $social_platform ) {
                    $social_link = 'https://t.me/' . $social_link;
                }
                if ( 'skype' == $social_platform ) {
                    $social_link = 'skype:' . $social_link . '?call';
                }
                if ( !empty($social_link) ) {
                    echo  MABOX_Helper::get_mabox_social_icon( $author_template_id, $social_link, $social_platform ) ;
                }
            }
            echo  '</div>' ;
        }
        
        // end of social icons
        echo  '</div>' ;
        // end of mabox-wrap div
    }

}