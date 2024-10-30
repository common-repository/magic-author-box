<?php
// If this file is called directly, busted!
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Class manage usr profile fields
 */
class MABOX_User_Profile {

    function __construct() {

        // Custom Profile Image & general fields
        add_action('show_user_profile', array($this, 'add_general_profile_fields'), 9, 1);
        add_action('edit_user_profile', array($this, 'add_general_profile_fields'), 9, 1);

        // Custom Profile Image & general fields
        add_action('show_user_profile', array($this, 'add_profile_image'), 9, 1);
        add_action('edit_user_profile', array($this, 'add_profile_image'), 9, 1);

        // Social Links
        add_action('show_user_profile', array($this, 'add_social_area'));
        add_action('edit_user_profile', array($this, 'add_social_area'));

        add_action('personal_options_update', array($this, 'save_user_profile'));
        add_action('edit_user_profile_update', array($this, 'save_user_profile'));

        // Allow HTML in user description.
        remove_filter('pre_user_description', 'wp_filter_kses');
        add_filter('pre_user_description', 'wp_filter_post_kses');
    }

    public function add_social_area($profileuser) {

        //Check if allowed
        if( ! MABOX_Helper::check_allowed_user_roles( $profileuser->roles ) ) return;

        //Fer user id
        $user_id = $profileuser->data->ID;

        //Get social links
        $social_links = MABOX_Helper::get_user_social_links($user_id);
        $social_icons = MABOX_Helper::get_social_icons();

        //unset user email
	    unset($social_icons['user_email']);
        ?>
        <div class="mabox-user-profile-wrapper">
            <h2><?php esc_html_e('Author Box Social Media Links', 'magic-author-box'); ?></h2>
            <table class="form-table" id="mabox-social-table">
                <?php
                if (!empty($social_links)) {
                    foreach ($social_links as $social_platform => $social_link) {
                        ?>
                        <tr>
                            <th>
                                <span class="mabox-drag"></span>
                                <select name="mabox-social-icons[]">
                                    <?php foreach ($social_icons as $mabox_social_id => $mabox_social_name) { ?>
                                        <option value="<?php echo esc_attr($mabox_social_id); ?>" <?php selected($mabox_social_id, $social_platform); ?>><?php echo esc_html($mabox_social_name); ?></option>
                                    <?php } ?>
                                </select>
                            </th>
                            <td>
                                <input name="mabox-social-links[]"
                                       type="<?php echo ('whatsapp' == $social_platform || 'phone' == $social_platform) ? 'tel' : 'text'; ?>"
                                       class="regular-text"
                                       value="<?php echo ( 'whatsapp' == $social_platform  || 'telegram' == $social_platform || 'skype' == $social_platform || 'phone' == $social_platform ) ? esc_attr($social_link) : esc_url( $social_link ); ?>">
                                <span class="dashicons dashicons-trash"></span>
                            <td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <th>
                            <span class="mabox-drag"></span>
                            <select name="mabox-social-icons[]">
                                <?php foreach ($social_icons as $mabox_social_id => $mabox_social_name) { ?>
                                    <option value="<?php echo esc_attr($mabox_social_id); ?>"><?php echo esc_html($mabox_social_name); ?></option>
                                <?php } ?>
                            </select>
                        </th>
                        <td>
                            <input name="mabox-social-links[]" type="text" class="regular-text" value="">
                            <span class="dashicons dashicons-trash"></span>
                        <td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <div class="mabox-add-social-link">
                <a href="#" class="button button-primary"></span><?php esc_html_e('+ Add new social platform', 'magic-author-box'); ?></a>
            </div>
        </div>
        <?php
    }

    public function add_general_profile_fields($user) {

        //Check if allowed
        if( ! MABOX_Helper::check_allowed_user_roles( $user->roles ) ) return;

        //Get profile template and all templates
        $template_id    = get_user_meta($user->ID, 'mabox_profile_template', true);
        $template_args  = array(
            'post_type' => 'mabox_template',
            'posts_per_page' => -1,
            'order'     => 'DESC',
            'order_by'  => 'title'
        );

        //Added args to get only own + admin's templates
        $allowed_uids = get_users( array( 'role' => 'administrator', 'fields' => 'ID' ) );
        $allowed_uids[] = $user->ID;
        $template_args['author__in'] = $allowed_uids;

        //Get author box templates
        $templates  = get_posts( $template_args );
        ?>
        <div id="mabox-custom-profile-fields">
            <h3><?php esc_html_e('Author Box General Profile Fields', 'magic-author-box'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="mabox_profile_template"><?php esc_html_e('Choose Template', 'magic-author-box'); ?></label></th>
                    <td>
                        <?php
                        if( !empty( $templates ) ) {
                            echo '<select id="mabox_profile_template" name="mabox_profile_template">';
                            foreach( $templates as $template ) {
                                echo '<option value="'. $template->ID .'" '. selected($template->ID, $template_id, false) .'>'. esc_html($template->post_title) .'</option>';
                            }
                            echo '</select>';
                        } elseif ( in_array('administrator', $user->roles ) ) {
                            echo __( 'Please create first', 'magic-author-box' ) .' <a href="'. admin_url( '/post-new.php?post_type=mabox_template' ) .'">'. __( 'Template', 'magic-author-box' ) .'</a>';
                        } else {
                            echo '<span style="color:#dc3232;">'. __( 'Please ask your site administrator to create author box template first.', 'magic-author-box' ) .'</span>';
                        }?>
                        <p class="description"><?php _e( 'Choose any author box template for this user to display on frontend.' );?></p>
                    </td>
                </tr>
            </table>
        </div>

        <?php
    }

    public function add_profile_image($user) {

        //Check if allowed
        if( ! MABOX_Helper::check_allowed_user_roles( $user->roles ) ) return;

        //Check if upload file permisable
        if (!current_user_can('upload_files')) {
            return;
        }

        //Get default url
        $default_url = MABOX_PLUGIN_URL .'/admin/images/default.png';
        $image       = get_user_meta($user->ID, 'mabox-profile-image', true);
        ?>
        <div id="mabox-custom-profile-image">
            <h3><?php esc_html_e('Author Box Custom User Profile Image', 'magic-author-box'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="cupp_meta"><?php esc_html_e('Profile Image', 'magic-author-box'); ?></label></th>
                    <td>
                        <div id="mabox-current-image">
                            <?php wp_nonce_field('mabox-profile-image', 'mabox-profile-nonce'); ?>
                            <img data-default="<?php echo esc_url_raw($default_url); ?>"
                                 src="<?php echo '' != $image ? esc_url_raw($image) : esc_url_raw($default_url); ?>"><br>
                            <input type="text" name="mabox-custom-image" id="mabox-custom-image" class="regular-text"
                                   value="<?php echo esc_attr($image); ?>">
                        </div>
                        <div class="actions">
                            <a href="#" class="button-secondary"
                               id="mabox-remove-image"><?php esc_html_e('Remove Image', 'magic-author-box'); ?></a>
                            <a href="#" class="button-primary"
                               id="mabox-add-image"><?php esc_html_e('Upload Image', 'magic-author-box'); ?></a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }

    public function save_user_profile($user_id) {

        global $current_user;

        //Get this user roles if login user is admin
        $user_roles = array();
        if( in_array( 'administrator', $current_user->roles ) ) {
            $user_data = get_userdata( $user->ID );
            $user_roles = $user_data->roles;
        }

        //Check if allowed
        if( ! MABOX_Helper::check_allowed_user_roles( $user_roles ) ) return;

        //Check and same profile template option
        if (isset($_POST['mabox_profile_template']) && '' != $_POST['mabox_profile_template']) {
            update_user_meta($user_id, 'mabox_profile_template', absint($_POST['mabox_profile_template']));
        } else {
            delete_user_meta($user_id, 'mabox_profile_template');
        }

        //Check if social links submited
        if (isset($_POST['mabox-social-icons']) && isset($_POST['mabox-social-links'])) {
            $social_platforms = MABOX_Helper::get_social_icons();
            $social_links     = array();

            //loop for social links
            foreach ($_POST['mabox-social-links'] as $index => $social_link) {
                if ($social_link) {
                    $social_platform = isset($_POST['mabox-social-icons'][$index]) ? sanitize_key($_POST['mabox-social-icons'][$index]) : false;
                    if ($social_platform && isset($social_platforms[$social_platform])) {
                        if ('whatsapp' == $social_platform || 'phone' == $social_platform) {
                            $social_links[$social_platform] = esc_html($social_link);
                        } else {
                            $social_links[$social_platform] = esc_url_raw($social_link);
                        }
                    }
                }
            }

            //Get all social icons
			$social_platforms = MABOX_Helper::get_social_icons();
			$social_links     = array();
			foreach ( $_POST['mabox-social-links'] as $index => $social_link ) {
				if ( $social_link ) {
					$social_platform = isset( $_POST['mabox-social-icons'][ $index ] ) ? sanitize_key($_POST['mabox-social-icons'][ $index ]) : false;
					if ( $social_platform && isset( $social_platforms[ $social_platform ] ) ) {
						if ( 'whatsapp' == $social_platform  || 'telegram' == $social_platform || 'skype' == $social_platform || 'phone' == $social_platform) {
							$social_links[ $social_platform ] = esc_html($social_link);
						} else {
							$social_links[ $social_platform ] = esc_url_raw( $social_link );
						}
					}
				}
            }

            //Update social links
            update_user_meta($user_id, 'mabox_social_links', $social_links);

        } else {
            delete_user_meta($user_id, 'mabox_social_links');
        }

        //Validate if profile nounce
        if (!isset($_POST['mabox-profile-nonce']) || !wp_verify_nonce($_POST['mabox-profile-nonce'], 'mabox-profile-image')) {
            return;
        }

        //Check upload files permisable for this user
        if (!current_user_can('upload_files', $user_id)) {
            return;
        }

        //Update profile image
        if (isset($_POST['mabox-custom-image']) && '' != $_POST['mabox-custom-image']) {
            update_user_meta($user_id, 'mabox-profile-image', esc_url_raw($_POST['mabox-custom-image']));
        } else {
            delete_user_meta($user_id, 'mabox-profile-image');
        }

    }
}

new MABOX_User_Profile();