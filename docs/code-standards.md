# Code Standards

The .editorconfig defines code standards for spacing and indents.

### PHP

This project follows the same code standards as Laravel itself.

PHP files use [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) code standards.

A PHP code sniffer is automatically installed by Composer when you install this project. The rules are defined in phpcs.xml.

Before committing PHP code you can validate it against the code standards with:

`vendor/bin/phpcs`

To automatically fix validation issues, use:

`vendor/bin/phpcbf`

Pre-commit Git hooks may be added at a later date.

### Javascript

We use eslint for javascript linting. You can install globally using:

`npm i -g eslint`

We follow the Airbnb Javascript Style Guide:
https://github.com/airbnb/javascript

To lint code from the command line, run:
`eslint resources/*`

To automatically fix validation issues, use:
`eslint resources/* --fix`
