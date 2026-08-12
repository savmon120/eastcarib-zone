# Confidential data

The Oauth2 specification requires a credential set of _client id_ and _client
secret_. The resource_owner grant also needs the authorized user's username and
password, which is used at the time of collection but not stored. This
confidential data is collected via the form for the configuration entity
associated with your client plugin. This configuration entity is automatically
created for you. There are two options for storing the credential set.

## Local Storage

This module stores the client id and secret in the database by default using the
State service.

## Key Storage

If it is installed on your site, you may delegate storage to the
[Key module](https://www.drupal.org/project/key) A `KeyType` plugin is provided for this integration.  The Key
module provides a several ways to store confidential data outside the Drupal
site.

The easiest to implement in file storage.  You will need a file location
on your web server that is outside the directory that serves your site, which
you can also lock down using file permissions.  If that path is different from
the path on your local development environment, you can replace the path
either in local settings or in conditional settings that load only on your
server.

```php title="Override in settings.php"
<?php

$config['key.key.key_id']['key_provider_settings']['file_location']
   = '/replacement/path';
```

There are [modules that extend Key](https://www.drupal.org/project/key/ecosystem)
if you would like to use storage beyond a secured file directory.

Users of the Key module are **strongly
advised** to note that the configuration option in the Key module is for
**local** development only. Confidential data stored in configuration will
export and can therefore easily end up in GitLab, GitHub, Bitbucket or some
other accessible version control system.
