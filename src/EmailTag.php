<?php
/**
 * Class Email Tag
 *
 * Represents a single email tag configuration for EDD
 *
 * @package     ArrayPress/EDD/Register/EmailTags
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

namespace ArrayPress\EDD\Register;

class EmailTag {

	/**
	 * Tag identifier
	 *
	 * @var string
	 */
	private string $tag;

	/**
	 * Tag description
	 *
	 * @var string
	 */
	private string $description;

	/**
	 * Tag label
	 *
	 * @var string
	 */
	private string $label;

	/**
	 * Allowed contexts
	 *
	 * @var array
	 */
	private array $contexts;

	/**
	 * Allowed recipients
	 *
	 * @var array
	 */
	private array $recipients;

	/**
	 * Callback function
	 *
	 * @var callable
	 */
	private $callback;

	/**
	 * Initialize the email tag
	 *
	 * @param string   $tag         Tag identifier
	 * @param string   $description Tag description
	 * @param string   $label       Tag label
	 * @param callable $callback    Callback function
	 * @param array    $contexts    Allowed contexts
	 * @param array    $recipients  Allowed recipients
	 */
	public function __construct(
		string $tag,
		string $description,
		string $label,
		callable $callback,
		array $contexts = [ 'order' ],
		array $recipients = []
	) {
		$this->tag         = $tag;
		$this->description = $description;
		$this->label       = $label;
		$this->callback    = $callback;
		$this->contexts    = $contexts;
		$this->recipients  = $recipients;
	}

	/**
	 * Get the tag identifier
	 *
	 * @return string
	 */
	public function get_tag(): string {
		return $this->tag;
	}

	/**
	 * Get the tag description
	 *
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * Get the tag label
	 *
	 * @return string
	 */
	public function get_label(): string {
		return $this->label;
	}

	/**
	 * Get allowed contexts
	 *
	 * @return array
	 */
	public function get_contexts(): array {
		return $this->contexts;
	}

	/**
	 * Get allowed recipients
	 *
	 * @return array
	 */
	public function get_recipients(): array {
		return $this->recipients;
	}

	/**
	 * Get the wrapped callback function
	 *
	 * This method wraps the original callback to handle context validation
	 * before executing the callback.
	 *
	 * @return callable
	 */
	public function get_callback(): callable {
		return function ( $email_object_id, $email_object = null, $email = null ) {
			// Only execute callback if context matches or no contexts are specified
			if ( ! empty( $this->contexts ) && $email && ! in_array( $email->context, $this->contexts, true ) ) {
				return '';
			}

			// Execute the callback with proper error handling
			try {
				$result = call_user_func( $this->callback, $email_object_id, $email_object, $email );

				return $result !== null ? $result : '';
			} catch ( \Throwable $e ) {
				// Log error if WP_DEBUG is enabled
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					error_log( sprintf(
						'Email tag callback error for tag "%s": %s',
						$this->tag,
						$e->getMessage()
					) );
				}

				return '';
			}
		};
	}

	/**
	 * Get the original unwrapped callback
	 *
	 * This can be useful for debugging or advanced use cases where
	 * you need access to the original callback.
	 *
	 * @return callable
	 */
	public function get_raw_callback(): callable {
		return $this->callback;
	}

	/**
	 * Convert the tag to a string
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->tag;
	}

}