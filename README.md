# OAuth / Social Integration for Symfony: KnpUOAuth2ClientBundle

Easily integrate with an OAuth2 server (e.g. Facebook, GitHub) for:

* "Social" authentication / login
* "Connect with Facebook" type of functionality
* Fetching access keys via OAuth2 to be used with an API

This bundle integrates with [league/oauth2-client](http://oauth2-client.thephpleague.com/).

## Installation

Install the library via [Composer](https://getcomposer.org/) by
running the following command:

```bash
composer require knpuniversity/oauth2-client-bundle
```

Next, enable the bundle in your `app/AppKernel.php` file:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle(),
        // ...
    );
}
```

Awesome! Now, you'll want to configure a client.

## Configuring a Client (e.g. for Facebook)

You'll need to configure *one* client for *each* OAuth2 server
(GitHub, Facebook, etc) that you want to talk to.

### Step 1) Download the client library

Choose the one you want from this list and install it
via Composer:

<a name="client-downloader-table"></a>

| OAuth2 Provider                                              | Install                                  |
| ------------------------------------------------------------ | ---------------------------------------- |
| [Facebook](https://github.com/thephpleague/oauth2-facebook)  | composer require league/oauth2-facebook  |
| [GitHub](https://github.com/thephpleague/oauth2-github)      | composer require league/oauth2-github    |
| [LinkedIn](https://github.com/thephpleague/oauth2-linkedin)  | composer require league/oauth2-linkedin  |

<span name="end-client-downloader-table"></span>

### Step 2) Configure the provider

Awesome! Now, you'll configure your provider. For Facebook,
this will look something like this.

```yml
# app/config/config.yml
knpu_oauth2_client:
    providers:
        # the key "facebook_main" can be anything, it
        # will create a service: "knpu.oauth2.client.facebook_main"
        facebook_main:
            # this will be one of the supported types
            type: facebook
            client_id: YOUR_FACEBOOK_APP_ID
            client_secret: YOUR_FACEBOOK_APP_SECRET
            # the route that you're redirected to after
            # see the controller example below
            redirect_route: connect_facebook_check
            redirect_params: {}
            graph_api_version: v2.3
```

**See the full configuration for *all* the supported "types"
in the [Configuration](#configuration) section.**

The `type` is `facebook` because we're connecting to Facebook. You
can see all the supported `type` values below in the [Configuration](#configuration)
section.

### Step 3) Use the Client Service

Each client you configured now has its own service that can be
used to communicate with the OAuth2 server.

To start the OAuth process, you'll need to create a route and
controller that redirects to Facebook. Because we used the
key `facebook_main` above, you can simply:

```php
// ...

use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FacebookController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook")
     */
    public function connectAction()
    {
        // will redirect to Facebook!
        return $this->get('oauth2.registry')
            ->getClient('facebook_main') // key used in config.yml
            ->redirect();
    }

    /**
     * After going to Facebook, you're redirect back here
     * because this is the "redirect_route" you configured
     * in config.yml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectAction(Request $request)
    {
        /** @var \KnpU\OAuth2ClientBundle\Client\OAuth2Client $client */
        $client = $this->get('oauth2.registry')
            ->getClient('facebook_main');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
            $user = $client->fetchUser();

            // do something with all this new power!
            $user->getFirstName();
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage());die;
        }
    }
}
```

Now, just go (or link to) `/connect/facebook` and watch the flow!

There are multiple ways to access the objects you need to get your
work done:

```php
// KnpU\OAuth2ClientBundle\Client\ClientRegistry
$registry = $this->get('oauth2.registry');

// two ways to access the client
$client = $registry->getClient('facebook_main');
$client = $this->get('knpu.oauth2.client.facebook_main');

$scopes = ['public_profile', 'email'];
$client->redirect($scopes);

// get access token and then user
$accessToken = $client->getAccessToken();
$user = $client->fetchUserFromToken($accessToken);

// access the underlying "provider" from league/oauth2-client
$provider = $client->getOAuth2Provider();
// if you're using Facebook, then this works:
$longLivedToken = $provider->getLongLivedAccessToken($accessToken);
```

## Configuration

```yml
# app/config/config.yml
knpu_oauth2_client:
    clients:
        # will create service: "knpu.oauth2.client.facebook"
        # composer require league/oauth2-facebook
        facebook:
            # must be "facebook" - it activates that type!
            type: facebook
            # add and configure client_id and client_secret in parameters.yml
            client_id: %facebook_client_id%
            client_secret: %facebook_client_secret%
            # a route name you'll create
            redirect_route: connect_facebook_check
            redirect_params: {}
            graph_api_version: v2.5
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.github"
        # composer require league/oauth2-github
        github:
            # must be "github" - it activates that type!
            type: github
            # add and configure client_id and client_secret in parameters.yml
            client_id: %github_client_id%
            client_secret: %github_client_secret%
            # a route name you'll create
            redirect_route: connect_github_check
            redirect_params: {}
            
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.linkedin"
        # composer require league/oauth2-linkedin
        linkedin:
            # must be "linkedin" - it activates that type!
            type: linkedin
            # add and configure client_id and client_secret in parameters.yml
            client_id: %linkedin_client_id%
            client_secret: %linkedin_client_secret%
            # a route name you'll create
            redirect_route: connect_linkedin_check
            redirect_params: {}
            
            # whether to check OAuth2 "state": defaults to true
            # use_state: true
```

## Contributing

Of course, open source is fueled by everyone's ability to give just a little
bit of their time for the greater good. If you'd like to see a feature, you
can request it - but creating a pull request is an even better way to get
things done.

Either way, please feel comfortable submitting issues or pull requests:
all contributions and questions are warmly appreciated :).
