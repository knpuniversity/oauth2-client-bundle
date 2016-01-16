# KnpUOAuth2ClientBundle

This bundle integrates with [league/oauth2-client](http://oauth2-client.thephpleague.com/)
and allows you to easily configure an "OAuth 2 Provider" service that can help you
"Connect to Facebook", or really connect to and talk with an OAuth2 API.

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

Awesome! Now, you'll want to configure a provider.

## Configuring a Provider (e.g. Facebook)

The league/oauth2-client library that this bundle uses can connect
to many "OAuth2 Providers", like Facebook, GitHub and more. For a
full list, see [Provider Client Libraries](https://github.com/thephpleague/oauth2-client/blob/master/README.PROVIDERS.md).

Suppose you want to connect with Facebook. Here are the steps:

### Step 1) Download the client library

Each "provider" (e.g. Facebook, GitHub) requires a separate
library to connect with it (and these have their own documentation).

Find the library you need from the
[Provider Client Libraries](https://github.com/thephpleague/oauth2-client/blob/master/README.PROVIDERS.md)
and then follow its instructions to download it.

For the Facebook client library, this means:

```bash
composer require league/oauth2-facebook
```

### Step 2) Configure the provider

Awesome! Now, you'll configure your provider. For Facebook,
this will look something like this.

```yml
# app/config/config.yml
knpu_oauth2_client:
    providers:
        # the key "facebook_client" can be anything: it determines the service name
        # will create a service: "knpu.oauth2.facebook_client"
        facebook_client:
            type: facebook
            client_id: YOUR_FACEBOOK_APP_ID
            client_secret: YOUR_FACEBOOK_APP_SECRET
            # the route that you're redirected to after
            # see the controller example below
            redirect_route: connect_facebook_check
            redirect_params: {}
            graph_api_version: 2.3
```

**For a full configuration reference and all the "types" supported,
see [Configuration](#Configuration).**

The `type` is `facebook` because we're connecting to Facebook. You
can see all the supported `type` values below in the [Configuration](#Configuration)
section.

Most of the configuration will be the same for Github, Facebook or
any other type. But some configuration may vary (like `graph_api_version`,
which is specific to Facebook).

### Step 3) Use the service

Since we used the key `facebook_client` above, we now have a service
called `knpu.oauth2.facebook_client` that can be used in a controller:

```php
// ...

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        /** @var $provider \League\OAuth2\Client\Provider\Facebook */
        $provider = $this->container
            ->get('knpu.oauth2.facebook_client');

        $url = $provider->getAuthorizationUrl(array(
            // use whatever "scopes" you need
            'scopes' => array('email'),
        ));

        // will redirect to Facebook!
        return new RedirectResponse($url);
    }

    /**
     * After going to Facebook, you're redirect back here
     * because this is the "redirect_route" you configured
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectAction(Request $request)
    {
        /** @var $provider \League\OAuth2\Client\Provider\Facebook */
        $provider = $this->container
            ->get('knpu.oauth2.facebook_client');

        try {
            /** @var \League\OAuth2\Client\Token\AccessToken $accessToken */
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $request->get('code')
            ]);

            // getResourceOwner returns a ResourceOwnerInterface, but often
            // individual providers (e.g. Facebook) return a more-specific
            // object with more useful methods
            /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
            $user = $provider->getResourceOwner($accessToken);

            // do something with all this new power!
            return new Response('Hi there '.$user->getFirstName());
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage());die;
        }
    }
}
```

## Configuration

```yml
# app/config/config.yml
knpu_oauth2_client:
    providers:
        # will create service: "knpu.oauth2.facebook_client"
        # composer require league/oauth2-facebook
        facebook_client:
            # must be "facebook" - it activates that type!
            type: facebook
            client_id: Your_Real_Client_Id
            client_secret: Your_Real_Client_Secret
            # a route name you'll create
            redirect_route: connect_facebook_check
            redirect_params: {}
            graph_api_version: v2.5

        # will create service: "knpu.oauth2.github_client"
        # composer require league/oauth2-github
        github_client:
            # must be "github" - it activates that type!
            type: github
            client_id: Your_Real_Client_Id
            client_secret: Your_Real_Client_Secret
            # a route name you'll create
            redirect_route: connect_github_check
            redirect_params: {}
            
```

## Contributing

Of course, open source if fueled by everyone's ability to give just a little
bit of their time for the greater good. If you'd like to see a feature, you
can request it - but creating a pull request is an even better way to get
things done.

Either way, please feel comfortable submitting issues or pull requests:
all contributions and questions are warmly appreciated :).
