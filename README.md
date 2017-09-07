# OAuth / Social Integration for Symfony: KnpUOAuth2ClientBundle

Easily integrate with an OAuth2 server (e.g. Facebook, GitHub) for:

* "Social" authentication / login
* "Connect with Facebook" type of functionality
* Fetching access keys via OAuth2 to be used with an API
* Doing OAuth2 authentication with [Guard](https://knpuniversity.com/screencast/guard)

This bundle integrates with [league/oauth2-client](http://oauth2-client.thephpleague.com/).

[![Build Status](https://travis-ci.org/knpuniversity/oauth2-client-bundle.svg)](http://travis-ci.org/knpuniversity/oauth2-client-bundle)

## Requirements

* PHP 5.5.9 or higher

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
    $bundles = [
        // ...
        new KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle(),
        // ...
    ];
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

| OAuth2 Provider                                                       | Install                                             |
| --------------------------------------------------------------------- | ------------------------------------------------------ |
| [Auth0](https://github.com/RiskioFr/oauth2-auth0)                     | composer require riskio/oauth2-auth0                |
| [Azure](https://github.com/thenetworg/oauth2-azure)                   | composer require thenetworg/oauth2-azure            |
| [DigitalOcean](https://github.com/chrishemmings/oauth2-digitalocean)  | composer require chrishemmings/oauth2-digitalocean  |
| [Dribbble](https://github.com/crewlabs/oauth2-dribbble)               | composer require crewlabs/oauth2-dribbble           |
| [Dropbox](https://github.com/stevenmaguire/oauth2-dropbox)            | composer require stevenmaguire/oauth2-dropbox       |
| [Facebook](https://github.com/thephpleague/oauth2-facebook)           | composer require league/oauth2-facebook             |
| [GitHub](https://github.com/thephpleague/oauth2-github)               | composer require league/oauth2-github               |
| [GitLab](https://github.com/omines/oauth2-gitlab)                     | composer require omines/oauth2-gitlab               |
| [LinkedIn](https://github.com/thephpleague/oauth2-linkedin)           | composer require league/oauth2-linkedin             |
| [Google](https://github.com/thephpleague/oauth2-google)               | composer require league/oauth2-google               |
| [Eve Online](https://github.com/evelabs/oauth2-eveonline)             | composer require evelabs/oauth2-eveonline           |
| [Instagram](https://github.com/thephpleague/oauth2-instagram)         | composer require league/oauth2-instagram            |
| [VKontakte](https://github.com/j4k/oauth2-vkontakte)                  | composer require j4k/oauth2-vkontakte               |
| [Bitbucket](https://github.com/stevenmaguire/oauth2-bitbucket)        | composer require stevenmaguire/oauth2-bitbucket     |
| [Odnoklassniki](https://github.com/rakeev/oauth2-odnoklassniki)       | composer require aego/oauth2-odnoklassniki          |
| [Slack](https://github.com/adam-paterson/oauth2-slack)                | composer require adam-paterson/oauth2-slack         |
| [Yandex](https://github.com/rakeev/oauth2-yandex)                     | composer require aego/oauth2-yandex                 |
| [Vimeo](https://github.com/saf33r/oauth2-vimeo)                       | composer require saf33r/oauth2-vimeo                |
| [Yahoo](https://github.com/hayageek/oauth2-yahoo)                     | composer require hayageek/oauth2-yahoo              |
| generic                                                               | configure any unsupported provider                  |

<span name="end-client-downloader-table"></span>

### Step 2) Configure the provider

Awesome! Now, you'll configure your provider. For Facebook,
this will look something like this.

```yml
# app/config/config.yml
knpu_oauth2_client:
    clients:
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
            # route parameters to pass to your route, if needed
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
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config.yml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
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

## Authenticating with Guard

At this point, you now have a nice service that allows you to
redirect your user to an OAuth server (e.g. Facebook) and fetch
their access token and user information.

But often, you will want to actually authenticate that user: log
them into your system. In that case, instead of putting all of
the logic in `connectCheckAction` as shown above, you'll leave that
blank and create a [Guard authenticator](knpuniversity.com/screencast/guard),
which will hold similar logic.

A `SocialAuthenticator` base class exists to help with a few things:

```php
namespace AppBundle\Security;

use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

class MyFacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManager $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/connect/facebook-check') {
            // don't auth
            return;
        }

        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $email = $facebookUser->getEmail();

        // 1) have they logged in with Facebook before? Easy!
        $existingUser = $this->em->getRepository('AppBundle:User')
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        // 2) do we have a matching user by email?
        $user = $this->em->getRepository('AppBundle:User')
                    ->findOneBy(['email' => $email]);

        // 3) Maybe you just want to "register" them by creating
        // a User object
        $user->setFacebookId($facebookUser->getId());
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
    
    /**
     * @return FacebookClient
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config.yml
            ->getClient('facebook_main');
    }

    // ...
}
```

Next, register your authenticator as a service:

```yml
# app/config/services.yml
services:
    my_facebook_authenticator:
        class: AppBundle\Security\MyFacebookAuthenticator
        autowire: true
        # use autowiring, OR you can specify the argument manually
        # arguments:
        #     - '@oauth2.registry'
        #     - '@doctrine.orm.entity_manager'
        #     - '@router'
```

Finally, setup this service in `security.yml` under the `guard` section. For more
details: see http://symfony.com/doc/current/cookbook/security/guard-authentication.html#step-2-configure-the-authenticator.

**CAUTION** You *can* also inject the individual client (e.g. `FacebookClient`)
into your authenticator instead of the `ClientRegistry`. However, this may cause
ciricular reference issues and degrades performance (because autheticators are instantiated
on every request, even though you *rarely* need the `FacebookClient` to be created).
The `ClientRegistry` lazily creates the client objects.

## Configuration

Below is the configuration for *all* of the supported OAuth2 providers.
**Don't see the one you need?** Use the `generic` provider to configure
any provider.

```yml
# app/config/config.yml
knpu_oauth2_client:
    clients:
        # will create service: "knpu.oauth2.client.auth0"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\Auth0Client
        # composer require riskio/oauth2-auth0
        auth0:
            # must be "auth0" - it activates that type!
            type: auth0
            # add and configure client_id and client_secret in parameters.yml
            client_id: %auth0_client_id%
            client_secret: %auth0_client_secret%
            # a route name you'll create
            redirect_route: connect_auth0_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.azure"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\AzureClient
        # composer require thenetworg/oauth2-azure
        azure:
            # must be "azure" - it activates that type!
            type: azure
            # add and configure client_id and client_secret in parameters.yml
            client_id: %azure_client_id%
            client_secret: %azure_client_secret%
            # a route name you'll create
            redirect_route: connect_azure_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.digital_ocean"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\DigitalOceanClient
        # composer require chrishemmings/oauth2-digitalocean
        digital_ocean:
            # must be "digital_ocean" - it activates that type!
            type: digital_ocean
            # add and configure client_id and client_secret in parameters.yml
            client_id: %digital_ocean_client_id%
            client_secret: %digital_ocean_client_secret%
            # a route name you'll create
            redirect_route: connect_digital_ocean_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.dribbble"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\DribbbleClient
        # composer require crewlabs/oauth2-dribbble
        dribbble:
            # must be "dribbble" - it activates that type!
            type: dribbble
            # add and configure client_id and client_secret in parameters.yml
            client_id: %dribbble_client_id%
            client_secret: %dribbble_client_secret%
            # a route name you'll create
            redirect_route: connect_dribbble_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.dropbox"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\DropboxClient
        # composer require stevenmaguire/oauth2-dropbox
        dropbox:
            # must be "dropbox" - it activates that type!
            type: dropbox
            # add and configure client_id and client_secret in parameters.yml
            client_id: %dropbox_client_id%
            client_secret: %dropbox_client_secret%
            # a route name you'll create
            redirect_route: connect_dropbox_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.facebook"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient
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
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\GithubClient
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

        # will create service: "knpu.oauth2.client.gitlab"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\GitlabClient
        # composer require omines/oauth2-gitlab
        gitlab:
            # must be "gitlab" - it activates that type!
            type: gitlab
            # add and configure client_id and client_secret in parameters.yml
            client_id: %gitlab_client_id%
            client_secret: %gitlab_client_secret%
            # a route name you'll create
            redirect_route: connect_gitlab_check
            redirect_params: {}
            # Base installation URL, modify this for self-hosted instances
            # domain: https://gitlab.com
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.linkedin"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\LinkedInClient
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

        # will create service: "knpu.oauth2.client.google"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient
        # composer require league/oauth2-google
        google:
            # must be "google" - it activates that type!
            type: google
            # add and configure client_id and client_secret in parameters.yml
            client_id: %google_client_id%
            client_secret: %google_client_secret%
            # a route name you'll create
            redirect_route: connect_google_check
            redirect_params: {}
            # Optional value for sending access_type parameter. More detail: https://developers.google.com/identity/protocols/OpenIDConnect#authenticationuriparameters
            # access_type: ''
            # Optional value for sending hd parameter. More detail: https://developers.google.com/identity/protocols/OpenIDConnect#hd-param
            # hosted_domain: ''
            # Optional value for additional fields to be requested from the user profile. If set, these values will be included with the defaults. More details: https://developers.google.com/+/web/api/rest/latest/people
            # user_fields: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.eve_online"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\EveOnlineClient
        # composer require evelabs/oauth2-eveonline
        eve_online:
            # must be "eve_online" - it activates that type!
            type: eve_online
            # add and configure client_id and client_secret in parameters.yml
            client_id: %eve_online_client_id%
            client_secret: %eve_online_client_secret%
            # a route name you'll create
            redirect_route: connect_eve_online_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.instagram"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\InstagramClient
        # composer require league/oauth2-instagram
        instagram:
            # must be "instagram" - it activates that type!
            type: instagram
            # add and configure client_id and client_secret in parameters.yml
            client_id: %instagram_client_id%
            client_secret: %instagram_client_secret%
            # a route name you'll create
            redirect_route: connect_instagram_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.vkontakte"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\VKontakteClient
        # composer require j4k/oauth2-vkontakte
        vkontakte:
            # must be "vkontakte" - it activates that type!
            type: vkontakte
            # add and configure client_id and client_secret in parameters.yml
            client_id: %vkontakte_client_id%
            client_secret: %vkontakte_client_secret%
            # a route name you'll create
            redirect_route: connect_vkontakte_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.bitbucket"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\BitbucketClient
        # composer require stevenmaguire/oauth2-bitbucket
        bitbucket:
            # must be "bitbucket" - it activates that type!
            type: bitbucket
            # add and configure client_id and client_secret in parameters.yml
            client_id: %bitbucket_client_id%
            client_secret: %bitbucket_client_secret%
            # a route name you'll create
            redirect_route: connect_bitbucket_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.odnoklassniki"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\OdnoklassnikiClient
        # composer require aego/oauth2-odnoklassniki
        odnoklassniki:
            # must be "odnoklassniki" - it activates that type!
            type: odnoklassniki
            # add and configure client_id and client_secret in parameters.yml
            client_id: %odnoklassniki_client_id%
            client_secret: %odnoklassniki_client_secret%
            # a route name you'll create
            redirect_route: connect_odnoklassniki_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.slack"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\SlackClient
        # composer require adam-paterson/oauth2-slack
        slack:
            # must be "slack" - it activates that type!
            type: slack
            # add and configure client_id and client_secret in parameters.yml
            client_id: %slack_client_id%
            client_secret: %slack_client_secret%
            # a route name you'll create
            redirect_route: connect_slack_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.yandex"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\YandexClient
        # composer require aego/oauth2-yandex
        yandex:
            # must be "yandex" - it activates that type!
            type: yandex
            # add and configure client_id and client_secret in parameters.yml
            client_id: %yandex_client_id%
            client_secret: %yandex_client_secret%
            # a route name you'll create
            redirect_route: connect_yandex_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.vimeo"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\VimeoClient
        # composer require saf33r/oauth2-vimeo
        vimeo:
            # must be "vimeo" - it activates that type!
            type: vimeo
            # add and configure client_id and client_secret in parameters.yml
            client_id: %vimeo_client_id%
            client_secret: %vimeo_client_secret%
            # a route name you'll create
            redirect_route: connect_vimeo_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.yahoo"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\YahooClient
        # composer require hayageek/oauth2-yahoo
        yahoo:
            # must be "yahoo" - it activates that type!
            type: yahoo
            # add and configure client_id and client_secret in parameters.yml
            client_id: %yahoo_client_id%
            client_secret: %yahoo_client_secret%
            # a route name you'll create
            redirect_route: connect_yahoo_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true
```

## Configuring a Generic Provider

Is the OAuth server you want to connect with not here? No worries!
You can configure a custom "provider" using the `generic` type.

### 1) Find / Create your Provider Library

First, see if your OAuth server already has a "provider library"
that you can use: See [Provider Client Libraries](http://oauth2-client.thephpleague.com/providers/league/).

If you found one there, awesome! Install it. If not, you'll need
to create your own Provider class. See the
[Provider Guide](https://github.com/thephpleague/oauth2-client/blob/master/README.PROVIDER-GUIDE.md)
about this.

Either way, after this step, you *should* have a provider "class"
(e.g. a class that extends `AbstractProvider`) that's ready to use!

### 2) Configuration

Now, just configure your provider like any other provider, but
using the `generic` type:

```yml
# app/config/config.yml
knpu_oauth2_client:
    clients:
        # will create service: "knpu.oauth2.client.foo_bar_oauth"
        # an instance of: KnpU\OAuth2ClientBundle\Client\OAuth2Client
        foo_bar_oauth:
            type: generic
            provider_class: Some\Class\FooBarProvider

            # optional: a class that extends OAuth2Client
            # client_class: Some\Custom\Client

            # optional: if your provider has custom constructor options
            # provider_options: {}

            # now, all the normal options!
            client_id: %foo_bar_client_id%
            client_secret: %foo_bar_client_secret%
            redirect_route: connect_facebook_check
            redirect_params: {}
```

That's it! Now you'll have a `knpu.oauth2.client.foo_bar_oauth` service
you can use.

## Contributing

Of course, open source is fueled by everyone's ability to give just a little
bit of their time for the greater good. If you'd like to see a feature, you
can request it - but creating a pull request is an even better way to get
things done.

Either way, please feel comfortable submitting issues or pull requests:
all contributions and questions are warmly appreciated :).
