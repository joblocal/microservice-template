# Microservice Template

This project provides a generic structure for setting up Microservices with [Lumen](https://lumen.laravel.com/).
By default the project uses a Queuing-System to handle asynchronous jobs.

## Requirements

-   [Docker](https://docs.docker.com)
-   [Composer](https://getcomposer.org)

## Setup
To install all components needed for a local development environment you can use the provided `setup.sh` script.

```sh
. ./bin/setup.sh
```

### Verify Installation

You can verify a successful installation by running the test suite:

```sh
composer run test
```

### Configuration

Make sure to configure the following files according to your needs:

-   composer.json
-   docker-compose.yml
-   readme.md
-   .env.example
-   .env
-   php-fpm.conf (app and worker)

#### Environment Variables
This template uses environment variables for all configuration values, they are defined in the .environment file and
passed along to php-fpm via the php-fpm.conf ```ENV[...]```.

#### Sentry Error Logging
In order to enable error logging to sentry, first go to [sentry.io](https://sentry.io) and create a new project.
Copy the DSN and add it as  ```SENTRY_LARAVEL_DSN``` to your .environment file.

If you don't want to use Sentry for error logging, leave the env variable empty.

## Messaging Queue

You can start listening for Messages in the Queue by running:

```sh
php artisan queue:work
```

*make sure you run the command from within the docker app container*

## Testing & Linting

```sh
composer run test
composer run lint
```
You can also use the combined script by using `composer run confirm`.

## Technologies used

-   PHP 7.2
-   [Lumen Framework Documentation](http://lumen.laravel.com/docs)
-   [Docker](https://docs.docker.com)
-   [Sentry](https://docs.sentry.io)

## Note
Since this template was originally created with Lumen 5.3, there might be
some deviations to the default Lumen project. Hopefully we'll be able to
refactor and modernize the setup soon.

## Contributing
Please read through our [contributing guidelines](https://github.com/joblocal/microservice-template/blob/master/CONTRIBUTING.md). Included are directions for opening issues, coding standards, and feature requests.


## Authors
-   **Joblocal GmbH** - *Initial work* - [Joblocal](https://github.com/joblocal)

See also the list of [contributors](https://github.com/joblocal/microservice-template/contributors) who participated in this project.
