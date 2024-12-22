<?php
/**
 * Class Email Tags
 *
 * Factory for creating and registering email tags
 *
 * @package     ArrayPress/EDD/Register/EmailTags
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

namespace ArrayPress\EDD\Register;

class EmailTags {

	/**
	 * Registry of all factories
	 *
	 * @var array
	 */
	private static array $registry = [];

	/**
	 * Plugin file path
	 *
	 * @var string
	 */
	private string $file;

	/**
	 * Registered tags
	 *
	 * @var array
	 */
	private array $tags = [];

	/**
	 * Get or create a factory instance for a plugin
	 *
	 * @param string $file Plugin file path
	 *
	 * @return self
	 */
	public static function register( string $file ): self {
		if ( ! isset( self::$registry[ $file ] ) ) {
			self::$registry[ $file ] = new self( $file );
		}

		return self::$registry[ $file ];
	}

	/**
	 * Get all registered tags for a plugin
	 *
	 * @param string $file Plugin file path
	 *
	 * @return array
	 */
	public static function get_by( string $file ): array {
		if ( isset( self::$registry[ $file ]->tags ) ) {
			return self::$registry[ $file ]->tags;
		}

		return [];
	}

	/**
	 * Initialize the factory
	 *
	 * @param string $file Plugin file path
	 */
	private function __construct( string $file ) {
		$this->file = $file;
		add_action( 'plugins_loaded', [ $this, 'initialize' ] );
	}

	/**
	 * Initialize the factory
	 *
	 * @return void
	 */
	public function initialize(): void {
		if ( ! function_exists( 'EDD' ) ) {
			return;
		}

		add_action( 'edd_add_email_tags', [ $this, 'register_tags' ] );
	}

	/**
	 * Create a new tag builder
	 *
	 * @param string $tag Tag identifier
	 *
	 * @return TagBuilder
	 */
	public function tag( string $tag ): TagBuilder {
		return new TagBuilder( $this, $tag );
	}

	/**
	 * Add a tag to the factory
	 *
	 * @param EmailTag $tag Email tag instance
	 *
	 * @return self
	 */
	public function add_tag( EmailTag $tag ): self {
		$this->tags[] = $tag;

		return $this;
	}

	/**
	 * Register all tags with EDD
	 *
	 * @return void
	 */
	public function register_tags(): void {
		foreach ( $this->tags as $tag ) {
			edd_add_email_tag(
				$tag->get_tag(),
				$tag->get_description(),
				$tag->get_callback(),
				$tag->get_label(),
				$tag->get_contexts(),
				$tag->get_recipients()
			);
		}
	}

}
