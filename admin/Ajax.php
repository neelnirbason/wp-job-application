<?php
/**
 * Handles all ajax requests coming from frontend and backend
 *
 * @package    DevKabir\Admin
 * @since      1.0.0
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Admin;


use DevKabir\Application\Loader;
use WP_Error;

/**
 * Will be used for,
 * 1. Processing ajax request produced by the shortcode
 * 2. Processing ajax request produced by the application list table
 *
 * @subpackage DevKabir\Admin\Ajax
 */
class Ajax {

	/**
	 * Register ajax hooks
	 *
	 * @param \DevKabir\Application\Loader $loader Action and filter register.
	 */
	final public function run( Loader $loader ) {
		$loader->add_action( 'wp_ajax_nopriv_store_application', [ $this, 'submission' ] );
	}

	/**
	 * Process ajax request from frontend
	 */
	final public function submission(): void {
		// Check that the nonce is valid.
		if (
			! array_key_exists( 'cv_nonce', $_POST ) ||
			! wp_verify_nonce( $_POST['cv_nonce'], 'job_application_need_cv' )
		) {
			wp_send_json_error( __( 'Nonce is invalid', 'wp-job-application' ) );
		}


		$allowed_extensions = [ 'pdf', 'doc', 'docx' ];
		$file_type          = wp_check_filetype( $_FILES['cv']['name'] );
		$file_extension     = $file_type['ext'];
		// Check for valid file extension.
		if ( ! in_array( $file_extension, $allowed_extensions ) ) {
			wp_send_json_error( __( 'File extension must be ' . join( ', ', $allowed_extensions ), 'wp-job-application' ) );
		}


		// These files need to be included as dependencies when on the front end.
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$attachment_id = media_handle_upload( 'cv', 0 );
		$firstName     = sanitize_text_field( $_POST['first_name'] );
		$lastName      = sanitize_text_field( $_POST['last_name'] );
		$address       = sanitize_text_field( $_POST['address'] );
		$email         = sanitize_email( $_POST['email'] );
		$phone         = sanitize_text_field( $_POST['phone'] );
		$postName      = sanitize_text_field( $_POST['post_name'] );
		$submission    = [
			'first_name'    => $firstName,
			'last_name'     => $lastName,
			'address'       => $address,
			'email'         => $email,
			'phone'         => $phone,
			'post'          => $postName,
			'attachment_id' => $attachment_id
		];
		$result        = Application::store( $submission );
		if ( ! $result instanceof WP_Error ) {
			wp_send_json_success( __( 'Successfully submitted', 'wp-job-application' ) );
		} else {
			wp_send_json_error( $result->get_error_message() );
		}
	}
}