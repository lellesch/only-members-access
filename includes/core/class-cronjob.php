<?php

namespace LetoOnlyMembersAccess\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}


class Cronjob {

	const PREFIX_NAME = ONLY_MEMBERS_ACCESS_PREFIX;
	private static $instanz;

	private function __construct() {

		add_action( self::PREFIX_NAME . 'cron_job_hook', array( $this, 'cron_job_hook' ) );
	}

	public static function get_instance(): Cronjob {
		if ( ! isset( self::$instanz ) ) {
			self::$instanz = new self();
		}

		return self::$instanz;
	}

	/**
	 * Activates the cron job.
	 *
	 * This method should be called to activate the cron job. It checks if the cron job has already been scheduled
	 * and if not, it schedules it to run twice daily using the wp_schedule_event function.
	 *
	 * @return void
	 */
	public function activate_cron(): void {
		if ( ! wp_next_scheduled( self::PREFIX_NAME . 'cron_job_hook' ) ) {
			wp_schedule_event( time(), 'twicedaily', self::PREFIX_NAME . 'cron_job_hook' );
		}
	}

	/**
	 * Performs tasks for a cron job.
	 *
	 * This method should be called by the cron job script to execute any necessary tasks.
	 *
	 * It is recommended to place the necessary code for the cron job inside this method.
	 *
	 * @return void
	 */
	public function cron_job_hook(): void {
		// Code für Cronjob abarbeiten.
	}
}
