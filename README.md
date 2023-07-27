# ConvertKit for Statamic

**🚧 This addon is still a work-in-progress. Things may change drastically before we tag a `v2.0.0` release.**

This addon integrates with Statamic Forms, allowing you to add subscribers to your ConvertKit account after form submissions.

## Features

* Add subscribers via Statamic Forms
* Subscriber attributes can be mapped to form fields, or can be given custom values on a per-form basis.
* Tags are pulled from ConvertKit, and are applied on a per-form basis, so all subscribers created via a particular form will have the same tag.

## Installation

You can install this addon via composer:

``` bash
composer require steadfast-collective/statamic-convertkit
```

Once installed, add your ConvertKit API key to your `.env`:

```
CONVERTKIT_KEY=xxx
```

## Usage

Once installed, a new navigation item will show under "Tools". This page is where you can set the field mappings per-form.

There's two fields you must fill out:
- **Form** -  This is the form in ConvertKit new subscribers will be added to.
- **Email** - Subscriber email address

Other fields include:
- **First Name** - Subscriber first name
- **Tags** - Multi-checkbox select of existing tags within ConvertKit. New tags need to be added within ConvertKit.
- **Custom Fields** - Gives ability to map form data to custom fields defined within ConvertKit.

Not only can you map form data to the above fields, but you can also pass in a hardcoded custom value (this will apply to all subscribers who sign up using a specific form).

All configuration is stored as a `.yaml` file in `storage/statamic/addons/convertkit/convertkit_general.yaml`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email [dev@steadfastcollective.com](mailto:dev@steadfastcollective.com) instead of using the issue tracker.
