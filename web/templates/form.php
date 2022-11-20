<div id="application-shortcode-wrap">
	<div class="application-shortcode">
		<div class="info-wrap">
			<h3 class="title"><?php _e( 'Applicant Information', 'wp-job-application' ); ?></h3>
			<p class="subtitle"><?php _e( 'Use a permanent address where you can receive mail.', 'wp-job-application' ); ?> </p>
		</div>
		<div class="form-wrap">
			<form method="post" enctype="multipart/form-data" id="applicant-form">
				<div class="input-wrap">
					<div class="input-group-half">
						<label for="first-name"
							   class="input-label"><?php _e( 'First name', 'wp-job-application' ); ?> </label>
						<input type="text" name="first_name" id="first-name" class="input-text">
					</div>
					<div class="input-group-half">
						<label for="last-name"
							   class="input-label"><?php _e( 'Last name', 'wp-job-application' ); ?></label>
						<input type="text" name="last_name" id="last-name" class="input-text">
					</div>
					<div class="input-group-full">
						<label for="address"
							   class="input-label"><?php _e( 'Present Address', 'wp-job-application' ); ?></label>
						<div class="mt-1">
							<textarea id="address" name="address" rows="3" class="input-text"></textarea>
						</div>
					</div>
					<div class="input-group-full">
						<label for="email"
							   class="input-label"><?php _e( 'Email address', 'wp-job-application' ); ?></label>
						<input type="email" name="email" id="email" class="input-text">
					</div>

					<div class="input-group-full">
						<label for="phone"
							   class="input-label"><?php _e( 'Mobile Number', 'wp-job-application' ); ?></label>
						<input type="text" name="phone" id="phone" class="input-text">
					</div>
					<div class="input-group-full">
						<label for="post-name"
							   class="input-label"><?php _e( 'Post Name', 'wp-job-application' ); ?></label>
						<input type="text" name="post_name" id="post-name" class="input-text">
					</div>
					<div class="input-group-full">

						<label class="input-label">
							<span>CV</span>
							<span class="sr-only"><?php _e( 'Choose File', 'wp-job-application' ); ?></span>
							<input type="file" name="cv" class="input-file"/>
						</label>


					</div>
				</div>
				<div class="submit">
					<input type="hidden" name="action" value="store_application">
					<?php echo wp_nonce_field( 'job_application_need_cv', 'cv_nonce', true, false ); ?>
					<button type="submit" class="submit-button"><?php _e( 'Apply', 'wp-job-application' ); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>



