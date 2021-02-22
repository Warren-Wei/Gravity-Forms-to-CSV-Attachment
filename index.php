<?php
/*
Plugin Name: Gravity Forms to CSV Attachment
Description: Plugin to attach .csv file to notification email
Plugin URI: https://wordpress.org/
Author URI: https://wordpress.org/
Author: Mark Huynh, Warren Wei
License: Public Domain
Version: 1.0
*/



function add_attachment_csv_gravity( $notification, $form, $entry ) {
	if ( $notification["name"] == "App Complete" || $notification["name"] == "QQ Complete") {
		//Headers info
		$headers=array();
		$data=array();

		//Build form data
		foreach ($form['fields'] as $field) {
			if($field->type!='html' or $field->adminLabel!='' or isset( $field->adminLabel) or !empty( $field->adminLabel)) {
				$headers[]= $field->adminLabel;
				$data[]=rgar($entry, $field->id);
			}
		}

		// Create CSV File
		$upload = wp_upload_dir();
		$upload_path = $upload["basedir"];

		// First delete previous filea
		// @unlink($upload_path.'/formdata.csv');
		date_default_timezone_set('Australia/Melbourne');
		$datetime = date('Ymd_His', time());
		if ( $notification["name"] == "App Complete") {
			$filename = "/AppRawData_" . $datetime . ".csv";	
		} 
		if ( $notification["name"] == "QQ Complete") {
			$filename = "/QQRawData_" . $datetime . ".csv"; 
		}
		$fh = fopen($upload_path . $filename, 'a');
		fputcsv($fh, $headers);

		// Populate the data 
		fputcsv($fh, $data);

		// Close the file
		fclose($fh);

		$upload = wp_upload_dir();
		$upload_path = $upload["basedir"];
		$attachment = $upload_path . $filename;
		$notification["attachments"] = $attachment;
	}
	
    return $notification;
}
add_filter( 'gform_notification', 'add_attachment_csv_gravity', 10, 3 ); 