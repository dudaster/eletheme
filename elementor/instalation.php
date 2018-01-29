<?php
namespace Elementor\TemplateLibrary;
use Elementor\DB;
use Elementor\Core\Settings\Page\Manager as PageSettingsManager;
use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Core\Settings\Page\Model;
use Elementor\Plugin;
use Elementor\Settings;
use Elementor\User;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class  install_dummy extends Source_Local {

	private function import_single_template( $file_name ) {
		$data = json_decode( file_get_contents( $file_name ), true );
		if ( empty( $data ) ) {
			return new \WP_Error( 'file_error', 'Invalid File' );
		}
		// TODO: since 1.5.0 to content container named `content` instead of `data`.
		if ( ! empty( $data['data'] ) ) {
			$content = $data['data'];
		} else {
			$content = $data['content'];
		}
		if ( ! is_array( $content ) ) {
			return new \WP_Error( 'file_error', 'Invalid File' );
		}
		$content = $this->process_export_import_content( $content, 'on_import' );
		$page_settings = [];
		if ( ! empty( $data['page_settings'] ) ) {
			$page = new Model( [
				'id' => 0,
				'settings' => $data['page_settings'],
			] );
			$page_settings_data = $this->process_element_export_import_content( $page, 'on_import' );
			if ( ! empty( $page_settings_data['settings'] ) ) {
				$page_settings = $page_settings_data['settings'];
			}
		}
		$template_id = $this->save_item( [
			'content' => $content,
			'title' => $data['title'],
			'type' => $data['type'],
			'page_settings' => $page_settings,
		] );
		if ( is_wp_error( $template_id ) ) {
			return $template_id;
		}
		return $this->get_item( $template_id );
	}

function start() {

		$page = get_page_by_title('[body]', OBJECT, 'elementor_library');//check if its allready imported
		if ($page) return;


		$dir=get_template_directory() .'/elementor/install_files/';

		$file="defaultstuff.zip";

		$filename=$dir.$file;
		$import_file = $filename;
		if ( empty( $import_file ) ) {
			return new \WP_Error( 'file_error', 'Please upload a file to import' );
		}
		$items = [];
		$zip = new \ZipArchive();
		/*
		 * Check if file is a json or a .zip archive
		 */
		if ( true === $zip->open( $import_file ) ) {
			$wp_upload_dir = wp_upload_dir();
			$temp_path = $wp_upload_dir['basedir'] . '/' . Source_Local::TEMP_FILES_DIR . '/' . uniqid();
			$zip->extractTo( $temp_path );
			$zip->close();
			$file_names = array_diff( scandir( $temp_path ), [ '.', '..' ] );
			foreach ( $file_names as $file_name ) {
				$full_file_name = $temp_path . '/' . $file_name;
				$items[] = $this->import_single_template( $full_file_name );
				unlink( $full_file_name );
			}
			rmdir( $temp_path );
		} else {
			$items[] = $this->import_single_template( $import_file );
		}
		return $items;
	}
}
add_action('after_switch_theme', function (){ 
	
	/*this stuff is required to import the image */
	require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

	$installstuff=new install_dummy(); $installstuff->start(); 

} ,9999);
