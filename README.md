# ConvertKit For Statamic

**This addon is currently a work-in-progress.**

> "ConvertKit For Statamic" is a Statamic addon that integrates ConvertKit with Statamic Forms.

## Features
Add Subscribers via Statamic forms.

- Subscriber attributes can be mapped to form fields, or can be given custom values on a per-form basis.
- Tags are pulled from ConvertKit, and are applied on a per-form basis, so all subscribers created via a particular form will have the same tag.

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require steadfast-collective/convertkit
```

Add your ConvertKit API key to `.env`
```
CONVERTKIT_KEY=your-key-here
```

## How to Use

A new menu entry will be added under "Tools", and you can set the field mapping per-form via this settings page.

There's two fields you must fill out:
- "Form" -  This is the form in ConvertKit new subscribers will be added to.
- "Email" - Subscriber email address

Other fields include:
- "First Name" - Subscriber first name
- "Tags" - Multi-checkbox select of existing tags within ConvertKit. New tags need to be added within ConvertKit.
- "Custom Fields" - Gives ability to map form data to custom fields defined within ConvertKit.

Not only can you map form data to the above fields, but you can also pass in a hardcoded custom value (this will apply to all subscribers who sign up using a specific form).

All configuration is stored as a `.yaml` file in `storage/statamic/addons/convertkit/convertkit_general.yaml`.
