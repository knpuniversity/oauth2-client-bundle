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

## This bundle or HWIOAuthBundle?

In addition to this bundle, another OAuth bundle exists for Symfony: [hwi/oauth-bundle](https://github.com/hwi/HWIOAuthBundle).
You might be wondering "why are there two popular OAuth bundles?".

Great question! Generally speaking, `hwi/oauth-bundle` gives you more features out-of-the-box,
including social authentication and registration (called "connect"). But, it's also a bit harder
to install. The `knpuniversity/oauth2-client-bundle`, takes more work to setup, but gives you
more low-level control.

Not sure which to use? If you need OAuth (social) authentication & registration, try
[hwi/oauth-bundle](https://github.com/hwi/HWIOAuthBundle). If you don't like it, come back!

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

## Configuring a Client

You'll need to configure *one* client for *each* OAuth2 server
(GitHub, Facebook, etc) that you want to talk to.

### Step 1) Download the client library

Choose the one you want from this list and install it
via Composer:

<a name="client-downloader-table"></a>

| OAuth2 Provider                                                       | Install                                             |
| --------------------------------------------------------------------- | ------------------------------------------------------ |
| [Amazon](https://github.com/luchianenco/oauth2-amazon)                | composer require luchianenco/oauth2-amazon          |
| [Auth0](https://github.com/RiskioFr/oauth2-auth0)                     | composer require riskio/oauth2-auth0                |
| [Azure](https://github.com/thenetworg/oauth2-azure)                   | composer require thenetworg/oauth2-azure            |
| [Bitbucket](https://github.com/stevenmaguire/oauth2-bitbucket)        | composer require stevenmaguire/oauth2-bitbucket     |
| [Box](https://github.com/stevenmaguire/oauth2-box)                    | composer require stevenmaguire/oauth2-box           |
| [Buffer](https://github.com/tgallice/oauth2-buffer)                   | composer require tgallice/oauth2-buffer             |
| [CanvasLMS](https://github.com/smtech/oauth2-canvaslms)               | composer require smtech/oauth2-canvaslms            |
| [Clever](https://github.com/schoolrunner/oauth2-clever)               | composer require schoolrunner/oauth2-clever         |
| [DevianArt](https://github.com/SeinopSys/oauth2-deviantart)           | composer require seinopsys/oauth2-deviantart        |
| [DigitalOcean](https://github.com/chrishemmings/oauth2-digitalocean)  | composer require chrishemmings/oauth2-digitalocean  |
| [Discord](https://github.com/wohali/oauth2-discord-new)               | composer require wohali/oauth2-discord-new          |
| [Dribbble](https://github.com/crewlabs/oauth2-dribbble)               | composer require crewlabs/oauth2-dribbble           |
| [Dropbox](https://github.com/stevenmaguire/oauth2-dropbox)            | composer require stevenmaguire/oauth2-dropbox       |
| [Drupal](https://github.com/chrishemmings/oauth2-drupal)              | composer require chrishemmings/oauth2-drupal        |
| [Eve Online](https://github.com/evelabs/oauth2-eveonline)             | composer require evelabs/oauth2-eveonline           |
| [Elance](https://github.com/stevenmaguire/oauth2-elance)              | composer require stevenmaguire/oauth2-elance        |
| [Eventbrite](https://github.com/stevenmaguire/oauth2-eventbrite)      | composer require stevenmaguire/oauth2-eventbrite    |
| [Facebook](https://github.com/thephpleague/oauth2-facebook)           | composer require league/oauth2-facebook             |
| [Fitbit](https://github.com/djchen/oauth2-fitbit)                     | composer require djchen/oauth2-fitbit               |
| [Foursquare](https://github.com/stevenmaguire/oauth2-foursquare)      | composer require stevenmaguire/oauth2-foursquare    |
| [HeadHunter](https://github.com/AlexMasterov/oauth2-headhunter)       | composer require alexmasterov/oauth2-headhunter     |
| [Heroku](https://github.com/stevenmaguire/oauth2-heroku)              | composer require stevenmaguire/oauth2-heroku        |
| [Instagram](https://github.com/thephpleague/oauth2-instagram)         | composer require league/oauth2-instagram            |
| [GitHub](https://github.com/thephpleague/oauth2-github)               | composer require league/oauth2-github               |
| [GitLab](https://github.com/omines/oauth2-gitlab)                     | composer require omines/oauth2-gitlab               |
| [Google](https://github.com/thephpleague/oauth2-google)               | composer require league/oauth2-google               |
| [Keycloak](https://github.com/stevenmaguire/oauth2-keycloak)          | composer require stevenmaguire/oauth2-keycloak      |
| [LinkedIn](https://github.com/thephpleague/oauth2-linkedin)           | composer require league/oauth2-linkedin             |
| [MailRu](https://github.com/rakeev/oauth2-mailru)                     | composer require aego/oauth2-mailru                 |
| [Microsoft](https://github.com/stevenmaguire/oauth2-microsoft)        | composer require stevenmaguire/oauth2-microsoft     |
| [Mollie](https://github.com/mollie/oauth2-mollie-php)                 | composer require mollie/oauth2-mollie-php           |
| [Odnoklassniki](https://github.com/rakeev/oauth2-odnoklassniki)       | composer require aego/oauth2-odnoklassniki          |
| [Paypal](https://github.com/stevenmaguire/oauth2-paypal)              | composer require stevenmaguire/oauth2-paypal        |
| [PSN](https://github.com/larabros/oauth2-psn)                         | composer require larabros/oauth2-psn                |
| [Salesforce](https://github.com/stevenmaguire/oauth2-salesforce)      | composer require stevenmaguire/oauth2-salesforce    |
| [Slack](https://github.com/adam-paterson/oauth2-slack)                | composer require adam-paterson/oauth2-slack         |
| [Stripe](https://github.com/adam-paterson/oauth2-stripe)              | composer require adam-paterson/oauth2-stripe        |
| [Strava](https://github.com/Edwin-Luijten/oauth2-strava)              | composer require edwin-luijten/oauth2-strava        |
| [Uber](https://github.com/stevenmaguire/oauth2-uber)                  | composer require stevenmaguire/oauth2-uber          |
| [Unsplash](https://github.com/hughbertd/oauth2-unsplash)              | composer require hughbertd/oauth2-unsplash          |
| [Vimeo](https://github.com/saf33r/oauth2-vimeo)                       | composer require saf33r/oauth2-vimeo                |
| [VKontakte](https://github.com/j4k/oauth2-vkontakte)                  | composer require j4k/oauth2-vkontakte               |
| [Yahoo](https://github.com/hayageek/oauth2-yahoo)                     | composer require hayageek/oauth2-yahoo              |
| [Yandex](https://github.com/rakeev/oauth2-yandex)                     | composer require aego/oauth2-yandex                 |
| [Zendesk](https://github.com/stevenmaguire/oauth2-zendesk)            | composer require stevenmaguire/oauth2-zendesk       |
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
            graph_api_version: v2.12
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

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

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

### Authenticating any OAuth user

If you don't need to fetch/persist any information about the user, you can use the
`OAuthUserProvider` service to quickly authenticate them in your application.

First define the user provider in your `security.yml` file:

```yml
security:
    providers:
        oauth:
            id: knpu.oauth2.user_provider
```

Then in your Guard authenticator, use the user provider to easily fetch the user:

```php
public function getUser($credentials, UserProviderInterface $userProvider)
{
    return $userProvider->loadUserByUsername($this->getClient()->fetchUserFromToken($credentials)->getId());
}
```

The logged-in user will be an instance of `KnpU\OAuth2ClientBundle\Security\User\OAuthUser` and will
have the roles `ROLE_USER` and `ROLE_OAUTH_USER`.

## Configuration

Below is the configuration for *all* of the supported OAuth2 providers.
**Don't see the one you need?** Use the `generic` provider to configure
any provider.

```yml
# app/config/config.yml
knpu_oauth2_client:
    # can be set to the service id of a service that implements Guzzle\ClientInterface
    http_client: null

    clients:
        # will create service: "knpu.oauth2.client.amazon"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\AmazonClient
        # composer require luchianenco/oauth2-amazon
        amazon:
            # must be "amazon" - it activates that type!
            type: amazon
            # add and configure client_id and client_secret in parameters.yml
            client_id: %amazon_client_id%
            client_secret: %amazon_client_secret%
            # a route name you'll create
            redirect_route: connect_amazon_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

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
            # Your Auth0 domain/account, e.g. "mycompany" if your domain is "mycompany.auth0.com"
            account: ''
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

        # will create service: "knpu.oauth2.client.box"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\BoxClient
        # composer require stevenmaguire/oauth2-box
        box:
            # must be "box" - it activates that type!
            type: box
            # add and configure client_id and client_secret in parameters.yml
            client_id: %box_client_id%
            client_secret: %box_client_secret%
            # a route name you'll create
            redirect_route: connect_box_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.buffer"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\BufferClient
        # composer require tgallice/oauth2-buffer
        buffer:
            # must be "buffer" - it activates that type!
            type: buffer
            # add and configure client_id and client_secret in parameters.yml
            client_id: %buffer_client_id%
            client_secret: %buffer_client_secret%
            # a route name you'll create
            redirect_route: connect_buffer_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.canvas_lms"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\CanvasLMSClient
        # composer require smtech/oauth2-canvaslms
        canvas_lms:
            # must be "canvas_lms" - it activates that type!
            type: canvas_lms
            # add and configure client_id and client_secret in parameters.yml
            client_id: %canvas_lms_client_id%
            client_secret: %canvas_lms_client_secret%
            # a route name you'll create
            redirect_route: connect_canvas_lms_check
            redirect_params: {}
            # URL of Canvas Instance (e.g. https://canvas.instructure.com)
            canvas_instance_url: ''
            # This can be used to help the user identify which instance of an application this token is for. For example, a mobile device application could provide the name of the device.
            # purpose: ''
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.clever"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\CleverClient
        # composer require schoolrunner/oauth2-clever
        clever:
            # must be "clever" - it activates that type!
            type: clever
            # add and configure client_id and client_secret in parameters.yml
            client_id: %clever_client_id%
            client_secret: %clever_client_secret%
            # a route name you'll create
            redirect_route: connect_clever_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.devian_art"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\DevianArtClient
        # composer require seinopsys/oauth2-deviantart
        devian_art:
            # must be "devian_art" - it activates that type!
            type: devian_art
            # add and configure client_id and client_secret in parameters.yml
            client_id: %devian_art_client_id%
            client_secret: %devian_art_client_secret%
            # a route name you'll create
            redirect_route: connect_devian_art_check
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

        # will create service: "knpu.oauth2.client.discord"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\DiscordClient
        # composer require wohali/oauth2-discord-new
        discord:
            # must be "discord" - it activates that type!
            type: discord
            # add and configure client_id and client_secret in parameters.yml
            client_id: %discord_client_id%
            client_secret: %discord_client_secret%
            # a route name you'll create
            redirect_route: connect_discord_check
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

        # will create service: "knpu.oauth2.client.drupal"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\DrupalClient
        # composer require chrishemmings/oauth2-drupal
        drupal:
            # must be "drupal" - it activates that type!
            type: drupal
            # add and configure client_id and client_secret in parameters.yml
            client_id: %drupal_client_id%
            client_secret: %drupal_client_secret%
            # a route name you'll create
            redirect_route: connect_drupal_check
            redirect_params: {}

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

        # will create service: "knpu.oauth2.client.elance"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\ElanceClient
        # composer require stevenmaguire/oauth2-elance
        elance:
            # must be "elance" - it activates that type!
            type: elance
            # add and configure client_id and client_secret in parameters.yml
            client_id: %elance_client_id%
            client_secret: %elance_client_secret%
            # a route name you'll create
            redirect_route: connect_elance_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.eventbrite"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\EventbriteClient
        # composer require stevenmaguire/oauth2-eventbrite
        eventbrite:
            # must be "eventbrite" - it activates that type!
            type: eventbrite
            # add and configure client_id and client_secret in parameters.yml
            client_id: %eventbrite_client_id%
            client_secret: %eventbrite_client_secret%
            # a route name you'll create
            redirect_route: connect_eventbrite_check
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
            graph_api_version: v2.12
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.fitbit"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\FitbitClient
        # composer require djchen/oauth2-fitbit
        fitbit:
            # must be "fitbit" - it activates that type!
            type: fitbit
            # add and configure client_id and client_secret in parameters.yml
            client_id: %fitbit_client_id%
            client_secret: %fitbit_client_secret%
            # a route name you'll create
            redirect_route: connect_fitbit_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.four_square"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\FoursquareClient
        # composer require stevenmaguire/oauth2-foursquare
        four_square:
            # must be "four_square" - it activates that type!
            type: four_square
            # add and configure client_id and client_secret in parameters.yml
            client_id: %four_square_client_id%
            client_secret: %four_square_client_secret%
            # a route name you'll create
            redirect_route: connect_four_square_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.headhunter"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\HeadHunterClient
        # composer require alexmasterov/oauth2-headhunter
        headhunter:
            # must be "headhunter" - it activates that type!
            type: headhunter
            # add and configure client_id and client_secret in parameters.yml
            client_id: %headhunter_client_id%
            client_secret: %headhunter_client_secret%
            # a route name you'll create
            redirect_route: connect_headhunter_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.heroku"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\HerokuClient
        # composer require stevenmaguire/oauth2-heroku
        heroku:
            # must be "heroku" - it activates that type!
            type: heroku
            # add and configure client_id and client_secret in parameters.yml
            client_id: %heroku_client_id%
            client_secret: %heroku_client_secret%
            # a route name you'll create
            redirect_route: connect_heroku_check
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
            # Optional value if you don't want or need to enable Google+ API access.
            # use_oidc_mode: false
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.keycloak"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\KeycloakClient
        # composer require stevenmaguire/oauth2-keycloak
        keycloak:
            # must be "keycloak" - it activates that type!
            type: keycloak
            # add and configure client_id and client_secret in parameters.yml
            client_id: %keycloak_client_id%
            client_secret: %keycloak_client_secret%
            # a route name you'll create
            redirect_route: connect_keycloak_check
            redirect_params: {}
            # Keycloak server URL
            auth_server_url: ''
            # Keycloak realm
            realm: ''
            # Optional: Encryption algorith, i.e. RS256
            # encryption_algorithm: ''
            # Optional: Encryption key path, i.e. ../key.pem
            # encryption_key_path: ''
            # Optional: Encryption key, i.e. contents of key or certificate
            # encryption_key: ''
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

        # will create service: "knpu.oauth2.client.mail_ru"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\MailRuClient
        # composer require aego/oauth2-mailru
        mail_ru:
            # must be "mail_ru" - it activates that type!
            type: mail_ru
            # add and configure client_id and client_secret in parameters.yml
            client_id: %mail_ru_client_id%
            client_secret: %mail_ru_client_secret%
            # a route name you'll create
            redirect_route: connect_mail_ru_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.microsoft"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\MicrosoftClient
        # composer require stevenmaguire/oauth2-microsoft
        microsoft:
            # must be "microsoft" - it activates that type!
            type: microsoft
            # add and configure client_id and client_secret in parameters.yml
            client_id: %microsoft_client_id%
            client_secret: %microsoft_client_secret%
            # a route name you'll create
            redirect_route: connect_microsoft_check
            redirect_params: {}
            # Optional value for URL Authorize
            # url_authorize: ''
            # Optional value for URL Access Token
            # url_access_token: ''
            # Optional value for URL Resource Owner Details
            # url_resource_owner_details: ''
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.mollie"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\MollieClient
        # composer require mollie/oauth2-mollie-php
        mollie:
            # must be "mollie" - it activates that type!
            type: mollie
            # add and configure client_id and client_secret in parameters.yml
            client_id: %mollie_client_id%
            client_secret: %mollie_client_secret%
            # a route name you'll create
            redirect_route: connect_mollie_check
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

        # will create service: "knpu.oauth2.client.paypal"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\PaypalClient
        # composer require stevenmaguire/oauth2-paypal
        paypal:
            # must be "paypal" - it activates that type!
            type: paypal
            # add and configure client_id and client_secret in parameters.yml
            client_id: %paypal_client_id%
            client_secret: %paypal_client_secret%
            # a route name you'll create
            redirect_route: connect_paypal_check
            redirect_params: {}
            # When true, client uses Paypal Sandbox URLs.
            # is_sandbox: false
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.psn"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\PsnClient
        # composer require larabros/oauth2-psn
        psn:
            # must be "psn" - it activates that type!
            type: psn
            # add and configure client_id and client_secret in parameters.yml
            client_id: %psn_client_id%
            client_secret: %psn_client_secret%
            # a route name you'll create
            redirect_route: connect_psn_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.salesforce"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\SalesforceClient
        # composer require stevenmaguire/oauth2-salesforce
        salesforce:
            # must be "salesforce" - it activates that type!
            type: salesforce
            # add and configure client_id and client_secret in parameters.yml
            client_id: %salesforce_client_id%
            client_secret: %salesforce_client_secret%
            # a route name you'll create
            redirect_route: connect_salesforce_check
            redirect_params: {}
            # Custom Salesforce domain. Default domain is https://login.salesforce.com
            # domain: ''
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

        # will create service: "knpu.oauth2.client.stripe"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\StripeClient
        # composer require adam-paterson/oauth2-stripe
        stripe:
            # must be "stripe" - it activates that type!
            type: stripe
            # add and configure client_id and client_secret in parameters.yml
            client_id: %stripe_client_id%
            client_secret: %stripe_client_secret%
            # a route name you'll create
            redirect_route: connect_stripe_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.strava"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\StravaClient
        # composer require edwin-luijten/oauth2-strava
        strava:
            # must be "strava" - it activates that type!
            type: strava
            # add and configure client_id and client_secret in parameters.yml
            client_id: %strava_client_id%
            client_secret: %strava_client_secret%
            # a route name you'll create
            redirect_route: connect_strava_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.uber"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\UberClient
        # composer require stevenmaguire/oauth2-uber
        uber:
            # must be "uber" - it activates that type!
            type: uber
            # add and configure client_id and client_secret in parameters.yml
            client_id: %uber_client_id%
            client_secret: %uber_client_secret%
            # a route name you'll create
            redirect_route: connect_uber_check
            redirect_params: {}

            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.unsplash"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\UnsplashClient
        # composer require hughbertd/oauth2-unsplash
        unsplash:
            # must be "unsplash" - it activates that type!
            type: unsplash
            # add and configure client_id and client_secret in parameters.yml
            client_id: %unsplash_client_id%
            client_secret: %unsplash_client_secret%
            # a route name you'll create
            redirect_route: connect_unsplash_check
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

        # will create service: "knpu.oauth2.client.zendesk"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\ZendeskClient
        # composer require stevenmaguire/oauth2-zendesk
        zendesk:
            # must be "zendesk" - it activates that type!
            type: zendesk
            # add and configure client_id and client_secret in parameters.yml
            client_id: %zendesk_client_id%
            client_secret: %zendesk_client_secret%
            # a route name you'll create
            redirect_route: connect_zendesk_check
            redirect_params: {}
            # Your Zendesk subdomain
            subdomain: ''
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
