<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   dropboxfilechooser
 * @author    Codeboxr <info@codeboxr.com>
 * @license   GPL-2.0+
 * @link      http://codeboxr.com
 * @copyright 2014 Codeboxr
 */
?>
<h3><?php esc_attr_e( 'CBX Dropbox File Chooser', 'cbxdropboxfilechooser' ); ?></h3>

<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<!--h2><?php esc_attr_e( 'Plugin Setting', 'cbxdropboxfilechooser' ); ?></h2-->

	<div id="poststuff">
		<?php
		/*$output     = '';
			if(isset($_GET['settings-updated'])):
			$output     .= '<div class="messages status">' . __( 'Setting has been saved successfully', 'cbxdropboxfilechooser' ) . '</div>';
		endif;
		echo $output;*/
		?>


		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h3 class="hndle"><span><?php esc_attr_e( 'Plugin Setting', 'cbxdropboxfilechooser' ); ?></span>
						</h3>

						<div class="inside">
							<?php
							$sections = array(
								array(
									'id'    => 'cbxdropboxfilechooser_global_settings',
									'title' => __( 'Settings', 'responsivesmartpoll' )
								)
							);


							//var_dump($roles);
							$fields = array(
								'cbxdropboxfilechooser_global_settings' => array(
									array(
										'name'      => 'apikey',
										'label'     => __( 'Dropbox Api Key', 'cbxdropboxfilechooser' ),
										'desc'      => __( 'Please create app in dropbox and find the api key','cbxdropboxfilechooser' ),
										'type'      => 'text',
										'default'   => '',

									)
								)

							);

							$settings_api->set_sections( $sections );
							$settings_api->set_fields( $fields );

							//initialize them
							$settings_api->admin_init();
							$settings_api->show_navigation();
							$settings_api->show_forms();

							?>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<?php
			include_once( 'cb-sidebar.php' );
			?>

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->


