<?php

namespace DevKabir\Admin;

use WP_List_Table;

/**
 * Class Submission_List
 *
 * @property string name
 * @package DevKabir\Admin
 */
class Submission_List extends WP_List_Table {

	/**
	 * Submission_List constructor.
	 *
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
		list( $data, $pagination ) = $this->generate_pagination( $data );
		$this->set_pagination_args( $pagination );
		$this->items = $data;
	}

	/**
	 * @return array filter data by user's query
	 */
	private function filter_data(): array {
		if ( ! empty( $_POST ) && array_key_exists( 's', $_POST ) ) {
			return Submissions::get( $_POST['s'] );
		}

		return Submissions::get();
	}

	/**
	 * Gets a list of columns.
	 *
	 * The format is:
	 * - `'internal-name' => 'Title'`
	 *
	 * @return array
	 * @since 3.1.0
	 *
	 */
	final public function get_columns(): array {
		return array(
			'cb'              => '<input type="checkbox">',
			'name'            => __( 'Name', WJA_NAME ),
			'address'         => __( 'Address', WJA_NAME ),
			'email'           => __( 'Email', WJA_NAME ),
			'phone'           => __( 'Mobile No', WJA_NAME ),
			'post'            => __( 'Post Name', WJA_NAME ),
			'attachment'      => __( 'CV', WJA_NAME ),
			'submission_date' => __( 'Submitted @', WJA_NAME ),
		);
	}

	/**
	 * @param array $data Applications
	 *
	 * @return array
	 */
	private function generate_pagination( array $data ): array {
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );
		$data         = array_slice( $data, ( ( $current_page - 1 ) * 10 ), 10 );
		$pagination   = array(
			'total_items' => $total_items,             // total number of items
			'per_page'    => 10,                       // items to show on a page
			'total_pages' => ceil( $total_items / 10 ), // use ceil to round up
		);

		return array( $data, $pagination );
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
	 * @param array $item
	 *
	 * @return string
	 */
	final public function column_name( array $item ): string {
		$actions = array(
			'delete' => sprintf(
				"<a href=\"#\" data-id=\"%d\" data-nonce='%s' class='delete-submission'>%s</a>",
				$item['id'],
				wp_create_nonce( 'delete-application' ),
				__( 'Delete', WJA_NAME )
			),
		);

		return sprintf( '%1$s %2$s', $item['first_name'] . ' ' . $item['last_name'], $this->row_actions( $actions ) );
	}

	/**
	 * @return array
	 */
	final protected function get_bulk_actions(): array {
		return array(
			'delete_all' => __( 'Delete', WJA_NAME ),
		);
	}

	/**
	 * Callback to allow sorting of  data.
	 *
	 * @param array $a First item
	 * @param array $b Second item
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
