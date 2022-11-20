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
	 * @param Loader $loader Action and filter register.
	 */
	final public function run( Loader $loader ): void {
		$loader->add_action( 'wp_ajax_nopriv_store_application', array( $this, 'submission' ) );
		$loader->add_action( 'wp_ajax_remove_application', array( $this, 'delete' ) );
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

		$allowed_extensions = array( 'pdf', 'doc', 'docx' );
		$file_type          = wp_check_filetype( $_FILES['cv']['name'] );
		$file_extension     = $file_type['ext'];
		// Check for valid file extension.
		if ( ! in_array( $file_extension, $allowed_extensions, true ) ) {
			$extensions = implode( ', ', $allowed_extensions );
			wp_send_json_error( __( "File extension must be  $extensions", 'wp-job-application' ) );
		}

		// These files need to be included as dependencies when on the front end.
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$attachment_id = media_handle_upload( 'cv', 0 );
		$first_name    = sanitize_text_field( $_POST['first_name'] );
		$last_name     = sanitize_text_field( $_POST['last_name'] );
		$address       = sanitize_text_field( $_POST['address'] );
		$email         = sanitize_email( $_POST['email'] );
		$phone         = sanitize_text_field( $_POST['phone'] );
		$post_name     = sanitize_text_field( $_POST['post_name'] );
		$submission    = array(
			'first_name'    => $first_name,
			'last_name'     => $last_name,
			'address'       => $address,
			'email'         => $email,
			'phone'         => $phone,
			'post'          => $post_name,
			'attachment_id' => $attachment_id,
		);
		$result        = Application::store( $submission );
		if ( false !== $result ) {
			Application::notify( $submission );
			wp_send_json_success( __( 'Successfully submitted', 'wp-job-application' ) );
		} else {
			wp_send_json_error( __( 'Please check your information again', 'wp-job-application' ) );
		}
	}

	/**
	 * Processes ajax request from admin panel
	 */
	public function delete(): void {
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'delete-application' ) ) {
			wp_send_json_error( __( 'Go, get some sleep!', 'wp-job-application' ) );
		}
		$id = isset( $_REQUEST['id'] ) ? (int) $_REQUEST['id'] : 0;
		if ( false === Application::delete( $id ) ) {
			wp_send_json_error( __( 'Unable to remove', 'wp-job-application' ) );
		} else {
			wp_send_json_success( __( 'Application deleted successfully', 'wp-job-application' ) );
		}
	}
}
