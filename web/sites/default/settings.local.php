<?php

$settings['simple_environment_indicator'] = '@LOCAL';

$settings['simple_environment_indicator_css'] = "
  span.simplei-indicator.top-bar__title {
    float: left;
    order: -1;
    margin-right: 10rem;
  }
";

$clientId = '1504';
$clientSecret = '2BmU2VfTR17eN0jD0H3w9EJknVVqJlWUHYJlgCQf';
if ($clientId === false || $clientId === '') {
  throw new RuntimeException('Missing VATSIM_DEV_CLIENT_ID environment variable for the local VATSIM DEV OAuth app.');
}
if ($clientSecret === false || $clientSecret === '') {
  throw new RuntimeException('Missing VATSIM_DEV_CLIENT_SECRET environment variable for the local VATSIM DEV OAuth app.');
}

$settings['fir_sso.vatsim.client_id'] = $clientId;
$settings['fir_sso.vatsim.client_secret'] = $clientSecret;
$settings['fir_sso.vatsim.authorization_uri'] = getenv('VATSIM_DEV_AUTHORIZATION_URI') ?: 'https://auth-dev.vatsim.net/oauth/authorize';
$settings['fir_sso.vatsim.token_uri'] = getenv('VATSIM_DEV_TOKEN_URI') ?: 'https://auth-dev.vatsim.net/oauth/token';
$settings['fir_sso.vatsim.resource_owner_uri'] = getenv('VATSIM_DEV_RESOURCE_OWNER_URI') ?: 'https://auth-dev.vatsim.net/api/user';

$config['oauth2_client.oauth2_client.vatsim']['redirect_uri'] = '/oauth2/callback/vatsim';
$config['oauth2_client.oauth2_client.vatsim']['scopes'] = ['full_name', 'email', 'vatsim_details'];
$config['oauth2_client.oauth2_client.vatsim']['scope_separator'] = ' ';
$config['oauth2_client.oauth2_client.vatsim']['grant_type'] = 'authorization_code';
$config['oauth2_client.oauth2_client.vatsim']['success_message'] = FALSE;