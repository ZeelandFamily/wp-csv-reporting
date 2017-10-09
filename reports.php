<?php
class ZF_Reports {
	/**
	 * Example Report showing list of users
	 */
	function user_list() {
		$id = sanitize_text_field( $_POST['id'] );
		$start_date = sanitize_text_field( $_POST['start_date'] );
		$end_date = sanitize_text_field( $_POST['end_date'] );
		if ( $start_date > $end_date ) {
			die( 'Ennen -päivämäärä ei voi olla pienempi kuin jälkeen -päivämäärä' );
		}
		$args = array(
			array(
				'key' => $id,
			),
		);
		if ( $start_date ) {
			$start_args = array(
				'key'     => $id,
				'value'   => strtotime( $start_date ),
				'compare' => '>=',
				'type'	  => 'NUMERIC',
			);
			array_push( $args, $start_args );
		}
		if ( $end_date ) {
			$end_args = array(
				'key'     => $id,
				'value'   => strtotime( $end_date ),
				'compare' => '<=',
				'type'	  => 'NUMERIC',
			);
			array_push( $args, $end_args );
		}
		$query = new WP_User_Query( array(
			'meta_query' => array( $args ),
		) );
		$filename = get_post_field( 'post_name', $id ) . date( 'd-m-Y' );
		$data = array();
		if ( ! empty( $query->results ) ) {
			foreach ( $query->results as $user ) {
				$userdata = array( 
					'Päättymispvm'  => date( 'd.m.Y', get_user_meta( $user->ID, $id, true ) ),
					'Nimi'  		=> $user->display_name,
					'Sähköposti' 	=> $user->user_email
				);
				array_push( $data, $userdata );
			}
			return array(
				'data' 		=> $data,
				'filename' 	=> $filename,
				);
		} else {
			return;
		}
	}
}
class ZF_Forms {
	function user_list_form() {
		$this->name = 'Oppilaslista';

		wp_enqueue_script( 'jquery-ui-datepicker' );

		$args = array(
			'post_type'              => array( 'course' ),
			'nopaging'               => true,
		);

		$course_query = new WP_Query( $args );

		if ( $course_query->have_posts() ) : ob_start(); ?>
				<p>Jätä päivämäärät tyhjäksi, jos haluat viedä kurssin kaikki oppilaat</p>
				<select name='id'>
					<?php while ( $course_query->have_posts() ) : $course_query->the_post(); ?>
						<option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
					<?php endwhile; ?>
				</select>
				<label for="start_date">Lisenssin päättymispäivä jälkeen:</label>
				<input type='text' class="datepicker" id="start_date" name="start_date" />
				<label for="end_date">Lisenssin päättymispäivä ennen:</label>
				<input type='text' class="datepicker" id="end_date" name="end_date" />
				<input type='hidden' name='report' value='user_list' />
				<script>
					jQuery( function() {
						jQuery( ".datepicker" ).datepicker({
							dateFormat: 'dd.mm.yy'
						});
					} );
				</script>
		<?php endif;

		$this->form = ob_get_clean();
		wp_reset_postdata();
	}
}
