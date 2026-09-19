<?php

$settings['simple_environment_indicator'] = '@LOCAL';

$settings['simple_environment_indicator_css'] = "
  span.simplei-indicator.top-bar__title {
    float: left;
    order: -1;
    margin-right: 10rem;
  }
";
$config['oauth2_client.oauth2_client.vatsim']['client_id'] = '1504';
$config['oauth2_client.oauth2_client.vatsim']['client_secret'] = '2BmU2VfTR17eN0jD0H3w9EJknVVqJlWUHYJlgCQf';
$config['oauth2_client.oauth2_client.vatsim']['authorization_uri'] = 'https://auth-dev.vatsim.net/oauth/authorize';
$config['oauth2_client.oauth2_client.vatsim']['token_uri'] = 'https://auth-dev.vatsim.net/oauth/token';
$config['oauth2_client.oauth2_client.vatsim']['resource_owner_uri'] = 'https://auth-dev.vatsim.net/api/user';
$config['oauth2_client.oauth2_client.vatsim']['redirect_uri'] = '/oauth2/callback/vatsim';
$config['oauth2_client.oauth2_client.vatsim']['scopes'] = ['full_name', 'email', 'vatsim_details'];
$config['oauth2_client.oauth2_client.vatsim']['scope_separator'] = ' ';
$config['oauth2_client.oauth2_client.vatsim']['grant_type'] = 'authorization_code';
$config['oauth2_client.oauth2_client.vatsim']['success_message'] = FALSE;
