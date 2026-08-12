[![GitLab Testing Status Badge](https://git.drupalcode.org/project/oauth2_client/badges/4.0.x/pipeline.svg?ignore_skipped=true)](https://git.drupalcode.org/project/oauth2_client/-/pipelines)
# CONTENTS OF THIS FILE

 * Introduction
 * Requirements
 * Installation
 * Usage
 * Troubleshooting
 * Maintainers


## Introduction

The OAuth2 Client module allows for the creation of OAuth2 clients as Drupal
plugins, handling all back end functionality for retrieval, refresh, and
deletion of tokens

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/oauth2_client

 * To submit bug reports and feature suggestions, or to track changes:
   https://www.drupal.org/project/issues/oauth2_client


## Requirements

This module depends upon the OAuth 2.0 Client library from _The League of
Extraordinary Packages_. This library will be installed automatically when the
module is downloaded and installed with Composer.

 * Composer (https://getcomposer.org/)
 * OAuth 2.0 Client (https://oauth2-client.thephpleague.com/)


## Installation

This module must be installed using the following composer command:

`composer require drupal/oauth2_client:^4.1`

## Documentation

See [https://project.pages.drupalcode.org/oauth2_client](https://project.pages.drupalcode.org/oauth2_client/).

## Development

This module is setup to use [DDEV Drupal Contrib](https://github.com/drud/ddev-drupal-contrib).

```shell
git clone git@git.drupal.org:project/oauth2_client.git oauth2-client
cd oauth2-client
ddev start
ddev poser
ddev symlink-project
```
