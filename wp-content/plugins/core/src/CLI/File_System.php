<?php

namespace Tribe\Project\CLI;

trait File_System {

	protected function create_directory( $directory ) {
		clearstatcache();
		if ( file_exists( $directory ) ) {
			\WP_CLI::error( 'Sorry... ' . $directory . ' directory already exists' );
		}
		if ( ! mkdir( $directory ) && ! is_dir( $directory ) ) {
			\WP_CLI::error( 'Sorry...something went wrong when we tried to create ' . $directory );
		}
	}

	protected function write_file( $file, $contents, $overwrite = false ) {
		if ( file_exists( $file) && ! $overwrite ) {
			\WP_CLI::error( 'Sorry... ' . $file . ' already exists.' );
		}
		if ( ! $handle = fopen( $file, 'w' ) ) {
			\WP_CLI::error( 'Sorry...something went wrong when we tried to write to ' . $file );
		}

		fwrite( $handle, $contents );
		return $handle;
	}

	protected function insert_into_existing_file( $file, $new_line, $below_line ) {
		if ( ! $handle = fopen( $file, 'r+' ) ) {
			\WP_CLI::error( 'Sorry.. ' . $file . ' could not be opened.' );
		}

		$contents = '';
		while (! feof ( $handle ) ) {
			$line = fgets( $handle );
			$contents .= $line;
			if ( strpos( $line, $below_line ) !== false ) {
				$contents .= $new_line;
			}
		}

		if ( ! fclose( $handle ) ) {
			\WP_CLI::error( 'Sorry.. ' . $file . ' an error has occurred.' );
		}

		$this->write_file( $file, $contents, true );
	}

	protected function format_array_for_file( $array, $tab_leader = 1 ) {
		$text_array = var_export( $array, 1 );
		$lines = explode( PHP_EOL, $text_array );

		$modified_string = '';
		foreach ( $lines as $line ) {
			$modified_string .= str_repeat( "\t", $tab_leader ) . $line;
		}

		return $modified_string;
	}

}
