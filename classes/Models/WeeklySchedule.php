<?php
/**
 * Weekly Schedule related functionalities
 *
 * @package crewhrm
 */

namespace CrewHRM\Models;

use CrewHRM\Helpers\_String;

class WeeklySchedule {

	/**
	 * Update schedule for a user or for global settings
	 *
	 * @param int $user_id
	 * @param array $schedules
	 * @return void
	 */
	public static function updateSchedule( $user_id, $schedules ) {
		// Delete removed slots first
		self::deleteRemovedSlots( $user_id, $schedules );

		// Loop through per day
		global $wpdb;
		foreach ( $schedules as $day => $schedule ) {

			// Loop through slots in a single day
			foreach ( $schedule['slots'] as $schedule_id => $slot ) {
				$row = array(
					'week_day'    => $day,
					'employee_id' => $user_id,
					'time_starts' => $slot['start'],
					'time_ends'   => $slot['end'],
					'enable'      => $schedule['enable']
				);

				// Update existing one by the schedule ID
				if ( is_numeric( $schedule_id ) ) {
					$wpdb->update(
						$wpdb->crewhrm_weekly_schedules,
						$row,
						array( 'schedule_id' => $schedule_id )
					);
				} else {
					// Create new entry
					$wpdb->insert(
						$wpdb->crewhrm_weekly_schedules,
						$row
					);
				}
			}
		}
	}

	/**
	 * Delete removed slots
	 *
	 * @param int $user_id
	 * @param array $schedules
	 * @return void
	 */
	private static function deleteRemovedSlots( $user_id, $schedules ) {
		
		global $wpdb;
		
		// Delete removed slots
		$remaining_ids = array();
		foreach ( $schedules as $day ) {
			$remaining_ids = array_filter(
				array_keys( $day['slots'] ), 
				function ( $id ) {
					return is_numeric( $id ); // Non numeric means newly added, and ids are random string assigned by react.
				}
			);
		}

		// If user ID not passed, it means it is global settings
		$where_clause = $user_id === null ? " employee_id IS NULL" : $wpdb->prepare( " employee_id=%d", $user_id );

		// Delete all slots except remaings
		if ( ! empty( $remaining_ids ) ) {
			$ids_places = _String::getPlaceHolders( $remaining_ids );
			$where_clause .= $wpdb->prepare( " AND schedule_id NOT IN ({$ids_places})", ...$remaining_ids );
		}
		
		$wpdb->query(
			"DELETE FROM {$wpdb->crewhrm_weekly_schedules} WHERE {$where_clause}"
		);
	}

	/**
	 * Get weekly schedule
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function getSchedule( $user_id ) {
		
		global $wpdb;

		$where_clause = $user_id === null ? " employee_id IS NULL" : $wpdb->prepare( " employee_id=%d", $user_id );

		$slots = $wpdb->get_results(
			"SELECT * FROM {$wpdb->crewhrm_weekly_schedules} WHERE {$where_clause}",
			ARRAY_A
		);

		$schedules = array(
			'monday'    => array( 'enable' => false, 'slots' => array() ),
			'tuesday'   => array( 'enable' => false, 'slots' => array() ),
			'wednesday' => array( 'enable' => false, 'slots' => array() ),
			'thursday'  => array( 'enable' => false, 'slots' => array() ),
			'friday'    => array( 'enable' => false, 'slots' => array() ),
			'saturday'  => array( 'enable' => false, 'slots' => array() ),
			'sunday'    => array( 'enable' => false, 'slots' => array() ),
		);

		error_log( var_export( $slots, true ) );

		foreach ( $slots as $slot ) {
			$schedules[ $slot['week_day'] ]['enable'] = (bool) $slot['enable'];
			$schedules[ $slot['week_day'] ]['slots'][] = array(
				'start' => $slot['time_starts'],
				'end'   => $slot['time_ends']
			);
		}

		return $schedules;
	}
}
