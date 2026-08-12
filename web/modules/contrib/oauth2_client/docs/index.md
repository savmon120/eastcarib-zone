---
hide:
- toc
---
# Oauth2 Client

## Introduction

The [OAuth2 Client](https://www.drupal.org/project/oauth2_client) module allows
for the creation of OAuth2 clients as Drupal plugins, handling all back end
functionality for retrieval, refresh, and deletion of tokens.

## Quick Guide

Here is a brief overview of how to use this module to manage Oauth2
authorizations in your own module, with links to more detailed documentation
on each part of the solution. If this is the first contributed module that you
have worked with, there is existing documentation on drupal.org about how to
work with [composer in general](https://www.drupal.org/docs/develop/using-composer/manage-dependencies)
and how to
[install a module](https://www.drupal.org/node/2718229#adding-modules).

1. Determine your strategy for storing [confidential data](secrets.md).
2. [Create](creating-plugins.md) an `Oauth2Client` plugin in your module that extends
   `Oauth2ClientPluginBase`.
3. When your module is created and you browse to
   `/admin/config/system/oauth2-client` a configuration entity associated with
   your plugin is created. Edit and add your credentials.
4. Save and test
5. Use the provided `Oauth2ClientService` to [request your needed token](tokens.md).

# Extending

Every aspect of the client behavior can be [customized and extended](advanced.md).
