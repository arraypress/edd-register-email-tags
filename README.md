# EDD Email Tags Library

A PHP library for easy registration and management of Easy Digital Downloads (EDD) email tags, providing a fluent builder interface and robust tag handling. Features type safety, error handling, and context-aware tag processing.

## Features

- 📧 **Email Tag Management**: Easily register and manage EDD email tags
- 🏗️ **Fluent Builder Interface**: Intuitive tag configuration with method chaining
- ⚡ **WordPress Integration**: Seamless integration with EDD's email system
- 🛡️ **Type Safety**: Full type hinting and strict types enforcement
- 🔄 **Context Awareness**: Support for context-specific tag processing
- 🎯 **Recipient Filtering**: Control tag visibility based on recipient types
- 🚦 **Error Handling**: Robust error handling with debug logging
- 🏭 **Factory Pattern**: Efficient tag creation and registration
- 🔌 **Plugin Support**: Easy integration with existing plugins
- ✨ **Clean API**: Simple and intuitive API design

## Requirements

- PHP 7.4 or later
- WordPress 5.0 or later
- Easy Digital Downloads plugin
- Composer (for installation)

## Installation

Install via Composer:

```bash
composer require arraypress/edd-email-tags
```

## Basic Usage

```php
use ArrayPress\EDD\Register\EmailTags;

// Initialize the factory with your plugin file
$tags = EmailTags::register( __FILE__ );

// Register a simple email tag
$tags->tag( 'customer_name' )
     ->description( 'The customer\'s full name' )
     ->callback( function( $payment_id ) {
         $payment = edd_get_payment( $payment_id );
         return $payment ? $payment->get_name() : '';
     } )
     ->register();
```

## Extended Examples

### Creating Tags with Custom Context

```php
$tags->tag( 'subscription_status' )
     ->description( 'The status of the subscription' )
     ->label( 'Subscription Status' )
     ->contexts( ['subscription', 'renewal'] )
     ->callback( function( $email_object_id, $email_object = null, $email = null ) {
         return $email_object->get_status_label();
     } )
     ->register();
```

### Adding Context-Aware Tags

```php
$tags->tag( 'order_currency' )
     ->description( 'The currency used for the order' )
     ->contexts( ['order'] )
     ->callback( function( $email_object_id, $email_object = null, $email = null ) {
         return $email_object->currency;
     } )
     ->register();
```

### Working with Multiple Tags

```php
// Register multiple tags at once
$tags->tag( 'customer_name' )
     ->description( 'The customer\'s full name' )
     ->callback( function( $email_object_id, $email_object = null, $email = null ) {
         return $email_object->get_name();
     } )
     ->register()
     
     ->tag( 'payment_status' )
     ->description( 'The payment status' )
     ->callback( function( $email_object_id, $email_object = null, $email = null ) {
         return $email_object->status_nicename;
     } )
     ->register();
```

## API Methods

### EmailTags Factory Methods

* `register( string $file )`: Create/get factory instance for a plugin
* `get_by( string $file )`: Get all registered tags for a plugin
* `tag( string $tag )`: Create a new tag builder
* `add_tag( EmailTag $tag )`: Add a tag to the factory
* `register_tags()`: Register all tags with EDD

### TagBuilder Methods

* `description( string $description )`: Set tag description
* `label( string $label )`: Set tag label
* `callback( callable $callback )`: Set tag callback
* `contexts( array $contexts )`: Set allowed contexts
* `recipients( array $recipients )`: Set allowed recipients
* `register()`: Build and register the tag

### EmailTag Methods

* `get_tag()`: Get tag identifier
* `get_description()`: Get tag description
* `get_label()`: Get tag label
* `get_contexts()`: Get allowed contexts
* `get_recipients()`: Get allowed recipients
* `get_callback()`: Get wrapped callback function
* `get_raw_callback()`: Get original unwrapped callback

## Use Cases

* **Order Information**: Display order-specific details in emails
* **Customer Data**: Include customer information in notifications
* **Subscription Details**: Show subscription status and information
* **Custom Notifications**: Create specialized email content
* **Admin Communications**: Include admin-specific information
* **Dynamic Content**: Generate context-aware email content
* **Conditional Display**: Show content based on recipient type
* **Payment Information**: Include payment-specific details
* **Product Details**: Display purchased item information
* **System Integration**: Connect with other plugin systems

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL-2.0-or-later License.

## Support

- [Documentation](https://github.com/arraypress/edd-email-tags)
- [Issue Tracker](https://github.com/arraypress/edd-email-tags/issues)