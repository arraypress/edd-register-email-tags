<?php
/**
 * Class Tag Builder
 *
 * Fluent builder for creating email tags
 *
 * @package     ArrayPress/EDD/Register/EmailTags
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

namespace ArrayPress\EDD\Register;

use RuntimeException;

class TagBuilder {

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
	private string $description = '';

	/**
	 * Tag label
	 *
	 * @var string
	 */
	private string $label = '';

	/**
	 * Callback function
	 *
	 * @var callable|null
	 */
	private $callback = null;

	/**
	 * Allowed contexts
	 *
	 * @var array
	 */
	private array $contexts = [ 'order' ];

	/**
	 * Allowed recipients
	 *
	 * @var array
	 */
	private array $recipients = [];

	/**
	 * Parent factory instance
	 *
	 * @var EmailTags
	 */
	private EmailTags $factory;

	/**
	 * Initialize the builder
	 *
	 * @param EmailTags $factory Parent factory instance
	 * @param string           $tag     Tag identifier
	 */
	public function __construct( EmailTags $factory, string $tag ) {
		$this->factory = $factory;
		$this->tag     = $tag;
	}

	/**
	 * Set the tag description
	 *
	 * @param string $description Tag description
	 *
	 * @return self
	 */
	public function description( string $description ): self {
		$this->description = $description;

		return $this;
	}

	/**
	 * Set the tag label
	 *
	 * @param string $label Tag label
	 *
	 * @return self
	 */
	public function label( string $label ): self {
		$this->label = $label;

		return $this;
	}

	/**
	 * Set the callback function
	 *
	 * @param callable $callback Callback function
	 *
	 * @return self
	 */
	public function callback( callable $callback ): self {
		$this->callback = $callback;

		return $this;
	}

	/**
	 * Set the allowed contexts
	 *
	 * @param array $contexts Allowed contexts
	 *
	 * @return self
	 */
	public function contexts( array $contexts ): self {
		$this->contexts = $contexts;

		return $this;
	}

	/**
	 * Set the allowed recipients
	 *
	 * @param array $recipients Allowed recipients
	 *
	 * @return self
	 */
	public function recipients( array $recipients ): self {
		$this->recipients = $recipients;

		return $this;
	}

	/**
	 * Register the tag with EDD
	 *
	 * @return EmailTags
	 * @throws RuntimeException If callback is not set
	 */
	public function register(): EmailTags {
		if ( ! $this->callback ) {
			throw new RuntimeException( 'Callback must be set for email tag: ' . $this->tag );
		}

		$tag = new EmailTag(
			$this->tag,
			$this->description,
			$this->label ?: ucfirst( str_replace( '_', ' ', $this->tag ) ),
			$this->callback,
			$this->contexts,
			$this->recipients
		);

		return $this->factory->add_tag( $tag );
	}

}
