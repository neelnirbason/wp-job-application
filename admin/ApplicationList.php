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
			[
				'plural'   => 'Applications',
				'singular' => 'Application',
			]
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
		$hidden                = [];
		$primary               = 'name';
		$sortable              = $this->get_sortable_columns();
		$headers               = [ $columns, $hidden, $sortable, $primary ];
		$this->_column_headers = $headers;
		usort( $data, [ &$this, 'usort_reorder' ] );
		/* pagination */
		[ $data, $pagination ] = $this->generate_pagination( $data );
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
		return [
			'cb'              => '<input type="checkbox">',
			'name'            => __( 'Name', 'wp-job-application' ),
			'address'         => __( 'Address', 'wp-job-application' ),
			'email'           => __( 'Email', 'wp-job-application' ),
			'phone'           => __( 'Mobile No', 'wp-job-application' ),
			'post'            => __( 'Post Name', 'wp-job-application' ),
			'attachment'      => __( 'CV', 'wp-job-application' ),
			'submission_date' => __( 'Submitted @', 'wp-job-application' ),
		];
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
		$pagination   = [
			'total_items' => $total_items,                      // total number of items.
			'per_page'    => 10,                                // items to show on a page.
			'total_pages' => ceil( $total_items / 10 ),         // use ceil to round up.
		];

		return [ $data, $pagination ];
	}

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since 3.1.0
	 */
	final public function no_items(): void {
		_e( 'No application submitted yet.' );
	}

	/**
	 * Add row meta in name column
	 *
	 * @param array $item Application.
	 *
	 * @return string
	 */
	final public function column_name( array $item ): string {
		$actions = [
			'delete' => sprintf(
				"<a href=\"#\" data-id=\"%d\" data-nonce='%s' class='delete-submission'>%s</a>",
				$item['id'],
				wp_create_nonce( 'delete-application' ),
				__( 'Delete', 'wp-job-application' )
			),
		];

		return sprintf( '%1$s %2$s', $item['first_name'] . ' ' . $item['last_name'], $this->row_actions( $actions ) );
	}

	/**
	 * Retrieves the list of bulk actions available for this table.
	 *
	 * @return array
	 */
	final protected function get_bulk_actions(): array {
		return [
			'delete_all' => __( 'Delete', 'wp-job-application' ),
		];
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

		return ( 'asc' === $order ) ? $result : - $result;
	}
}
