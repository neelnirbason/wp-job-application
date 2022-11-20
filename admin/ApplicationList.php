<?php
/**
 * WrodPress default last table for applications
 *
 * @package    DevKabir\Admin
 * @since      1.0.0
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Admin;

use WP_List_Table;


/**
 * Collect all application from database and show as table
 *
 * @subpackage DevKabir\Admin\ApplicationList
 * @since      1.0.0
 */
class ApplicationList extends WP_List_Table {


	/**
	 * Application_List constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'plural'   => 'Applications',
				'singular' => 'Application',
			)
		);
	}

	/**
	 * Prepares the list of applications for displaying.
	 *
	 * @uses  WP_List_Table::set_pagination_args()
	 *
	 * @since 1.0.0
	 */
	final public function prepare_items(): void {
		$data                  = $this->filter_data();
		$columns               = $this->get_columns();
		$hidden                = array();
		$primary               = 'name';
		$sortable              = $this->get_sortable_columns();
		$headers               = array( $columns, $hidden, $sortable, $primary );
		$this->_column_headers = $headers;
		usort( $data, array( &$this, 'usort_reorder' ) );
		/* pagination */
		$paginations = $this->generate_pagination( $data );
		$data        = $paginations[0];
		$pagination  = $paginations[1];
		$this->set_pagination_args( $pagination );
		$this->items = $data;
	}

	/**
	 * Filter data by user input
	 *
	 * @return array filter data by user's query
	 */
	private function filter_data(): array {
		if ( ! empty( $_POST ) && array_key_exists( 's', $_POST ) ) {

			return Application::get( $_POST['s'] );
		}

		return Application::get();
	}

	/**
	 * Gets a list of columns.
	 *
	 * The format is:
	 * - `'internal-name' => 'Title'`
	 *
	 * @return array
	 * @since 3.1.0
	 */
	final public function get_columns(): array {
		return array(
			'name'            => __( 'Name', 'wp-job-application' ),
			'email'           => __( 'Email', 'wp-job-application' ),
			'phone'           => __( 'Mobile No', 'wp-job-application' ),
			'post'            => __( 'Post Name', 'wp-job-application' ),
			'address'         => __( 'Address', 'wp-job-application' ),
			'attachment'      => __( 'CV', 'wp-job-application' ),
			'submission_date' => __( 'Submitted at', 'wp-job-application' ),
		);
	}

	/**
	 * Define which column will be sortable.
	 *
	 * @return array[] column list.
	 */
	protected function get_sortable_columns(): array {
		return array(
			'submission_date' => array( 'submission_date', true ),
		);
	}

	/**
	 * Generates pagination data
	 *
	 * @param array $data Applications.
	 *
	 * @return array
	 */
	private function generate_pagination( array $data ): array {
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );
		$data         = array_slice( $data, ( ( $current_page - 1 ) * 10 ), 10 );
		$pagination   = array(
			'total_items' => $total_items,                      // total number of items.
			'per_page'    => 10,                                // items to show on a page.
			'total_pages' => ceil( $total_items / 10 ),         // use ceil to round up.
		);

		return array( $data, $pagination );
	}

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since 3.1.0
	 */
	final public function no_items(): void {
		_e( 'No application found.' );
	}

	/**
	 * Add row meta in name column
	 *
	 * @param array $item Application.
	 *
	 * @return string
	 */
	final public function column_name( array $item ): string {
		$actions = array(
			'delete' => sprintf(
				"<a href=\"#\" data-id=\"%d\" data-nonce='%s' class='delete-submission'>%s</a>",
				$item['id'],
				wp_create_nonce( 'delete-application' ),
				__( 'Delete', 'wp-job-application' )
			),
		);

		return sprintf( '%1$s %2$s', $item['first_name'] . ' ' . $item['last_name'], $this->row_actions( $actions ) );
	}


	/**
	 * Render row data
	 *
	 * @param array  $item single row data.
	 * @param string $column_name current column name.
	 *
	 * @return string
	 */
	final protected function column_default( $item, $column_name ): string {
		switch ( $column_name ) {
			case 'attachment':
				return '<a href=' . wp_get_attachment_url( $item['attachment_id'] ) . ' target="_blank">View</a>';
			case 'submission_date':
				return date( 'M d, Y h:i a', strtotime( $item['submission_date'] ) );
			default:
				return $item[ $column_name ] ?? '-';
		}
	}

	/**
	 * Callback to allow sorting of  data.
	 *
	 * @param array $a First item.
	 * @param array $b Second item.
	 *
	 * @return int
	 */
	private function usort_reorder( array $a, array $b ): int {
		// If no sort, default to title.
		$orderby = ! empty( $_REQUEST['orderby'] ) ? wp_unslash( $_REQUEST['orderby'] ) : 'submission_date'; // WPCS: Input var ok.

		// If no order, default to asc.
		$order = ! empty( $_REQUEST['order'] ) ? wp_unslash( $_REQUEST['order'] ) : 'asc';                   // WPCS: Input var ok.

		// Determine sort order.
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

		return ( 'asc' === $order ) ? $result : -$result;
	}
}
