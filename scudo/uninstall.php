<?php
/**
 * Scudo — Uninstall
 *
 * Rimuove tutte le opzioni, le tabelle e i file del plugin quando viene eliminato.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Rimuovi opzioni
delete_option( 'scudo_options' );
delete_option( 'scudo_policy_version' );
delete_option( 'scudo_db_version' );
delete_option( 'scudo_detected_cookies' );
delete_option( 'scudo_custom_cookies' );
delete_option( 'scudo_fonts_map' );
delete_option( 'scudo_privacy_data' );

// Rimuovi tabelle
global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}scudo_consent_log" );
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}scudo_rights_requests" );

// Rimuovi font scaricati
$scudo_upload_dir = wp_upload_dir();
$scudo_fonts_dir  = $scudo_upload_dir['basedir'] . '/scudo-fonts';
if ( is_dir( $scudo_fonts_dir ) ) {
    $scudo_files = glob( $scudo_fonts_dir . '/*' );
    if ( $scudo_files ) {
        foreach ( $scudo_files as $scudo_file ) {
            if ( is_file( $scudo_file ) ) {
                wp_delete_file( $scudo_file );
            }
        }
    }
    global $wp_filesystem;
    if ( empty( $wp_filesystem ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();
    }
    $wp_filesystem->rmdir( $scudo_fonts_dir );
}
