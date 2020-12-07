# OAuth / Social Integration for Symfony: KnpUOAuth2ClientBundle

Easily integrate with an OAuth2 server (e.g. Facebook, GitHub) for:

* "Social" authentication / login
* "Connect with Facebook" type of functionality
* Fetching access keys via OAuth2 to be used with an API
* Doing OAuth2 authentication with [Guard](https://knpuniversity.com/screencast/guard)

This bundle integrates with [league/oauth2-client](http://oauth2-client.thephpleague.com/).

[![Build Status](https://travis-ci.org/knpuniversity/oauth2-client-bundle.svg)](http://travis-ci.org/knpuniversity/oauth2-client-bundle)

## Requirements

* PHP 7.1.3 or higher
* Symfony 4.4 or higher (use version 1 of the bundle for earlier support)

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

Install the bundle library via [Composer](https://getcomposer.org/) by
running the following command:

```bash
composer require knpuniversity/oauth2-client-bundle
```

If you're using Symfony Flex, the bundle will be automatically enabled. For
older apps, enable it in your `AppKernel` class.

Awesome! Now, you'll want to configure a client.

## Configuring a Client

You'll need to configure *one* client for *each* OAuth2 server
(GitHub, Facebook, etc) that you want to talk to.

### Step 1) Download the client library

Choose the one you want from this list and install it
via Composer:

<a name="client-downloader-table"></a>

| OAuth2 Provider                                                        | Install                                             |
| ---------------------------------------------------------------------- | ------------------------------------------------------- |
| [Amazon](https://github.com/luchianenco/oauth2-amazon)                 | composer require luchianenco/oauth2-amazon          |
| [AppID](https://github.com/Jampire/oauth2-appid)                       | composer require jampire/oauth2-appid               |
| [Apple](https://github.com/patrickbussmann/oauth2-apple)               | composer require patrickbussmann/oauth2-apple       |
| [Auth0](https://github.com/RiskioFr/oauth2-auth0)                      | composer require riskio/oauth2-auth0                |
| [Azure](https://github.com/thenetworg/oauth2-azure)                    | composer require thenetworg/oauth2-azure            |
| [Bitbucket](https://github.com/stevenmaguire/oauth2-bitbucket)         | composer require stevenmaguire/oauth2-bitbucket     |
| [Box](https://github.com/stevenmaguire/oauth2-box)                     | composer require stevenmaguire/oauth2-box           |
| [Buddy](https://github.com/buddy-works/oauth2-client)                  | composer require buddy-works/oauth2-client          |
| [Buffer](https://github.com/tgallice/oauth2-buffer)                    | composer require tgallice/oauth2-buffer             |
| [CanvasLMS](https://github.com/smtech/oauth2-canvaslms)                | composer require smtech/oauth2-canvaslms            |
| [Clever](https://github.com/schoolrunner/oauth2-clever)                | composer require schoolrunner/oauth2-clever         |
| [DevianArt](https://github.com/SeinopSys/oauth2-deviantart)            | composer require seinopsys/oauth2-deviantart        |
| [DigitalOcean](https://github.com/chrishemmings/oauth2-digitalocean)   | composer require chrishemmings/oauth2-digitalocean  |
| [Discord](https://github.com/wohali/oauth2-discord-new)                | composer require wohali/oauth2-discord-new          |
| [Dribbble](https://github.com/crewlabs/oauth2-dribbble)                | composer require crewlabs/oauth2-dribbble           |
| [Dropbox](https://github.com/stevenmaguire/oauth2-dropbox)             | composer require stevenmaguire/oauth2-dropbox       |
| [Drupal](https://github.com/chrishemmings/oauth2-drupal)               | composer require chrishemmings/oauth2-drupal        |
| [Elance](https://github.com/stevenmaguire/oauth2-elance)               | composer require stevenmaguire/oauth2-elance        |
| [Eve Online](https://github.com/evelabs/oauth2-eveonline)              | composer require evelabs/oauth2-eveonline           |
| [Eventbrite](https://github.com/stevenmaguire/oauth2-eventbrite)       | composer require stevenmaguire/oauth2-eventbrite    |
| [Facebook](https://github.com/thephpleague/oauth2-facebook)            | composer require league/oauth2-facebook             |
| [Fitbit](https://github.com/djchen/oauth2-fitbit)                      | composer require djchen/oauth2-fitbit               |
| [Foursquare](https://github.com/stevenmaguire/oauth2-foursquare)       | composer require stevenmaguire/oauth2-foursquare    |
| [Geocaching](https://github.com/surfoo/oauth2-geocaching)              | composer require surfoo/oauth2-geocaching           |
| [GitHub](https://github.com/thephpleague/oauth2-github)                | composer require league/oauth2-github               |
| [GitLab](https://github.com/omines/oauth2-gitlab)                      | composer require omines/oauth2-gitlab               |
| [Google](https://github.com/thephpleague/oauth2-google)                | composer require league/oauth2-google               |
| [HeadHunter](https://github.com/AlexMasterov/oauth2-headhunter)        | composer require alexmasterov/oauth2-headhunter     |
| [Heroku](https://github.com/stevenmaguire/oauth2-heroku)               | composer require stevenmaguire/oauth2-heroku        |
| [Instagram](https://github.com/thephpleague/oauth2-instagram)          | composer require league/oauth2-instagram            |
| [Jira](https://github.com/mrjoops/oauth2-jira)                         | composer require mrjoops/oauth2-jira                |
| [Keycloak](https://github.com/stevenmaguire/oauth2-keycloak)           | composer require stevenmaguire/oauth2-keycloak      |
| [LinkedIn](https://github.com/thephpleague/oauth2-linkedin)            | composer require league/oauth2-linkedin             |
| [MailRu](https://github.com/rakeev/oauth2-mailru)                      | composer require aego/oauth2-mailru                 |
| [Microsoft](https://github.com/stevenmaguire/oauth2-microsoft)         | composer require stevenmaguire/oauth2-microsoft     |
| [Mollie](https://github.com/mollie/oauth2-mollie-php)                  | composer require mollie/oauth2-mollie-php           |
| [Odnoklassniki](https://github.com/rakeev/oauth2-odnoklassniki)        | composer require aego/oauth2-odnoklassniki          |
| [Okta](https://github.com/foxworth42/oauth2-okta)                      | composer require foxworth42/oauth2-okta             |
| [Paypal](https://github.com/stevenmaguire/oauth2-paypal)               | composer require stevenmaguire/oauth2-paypal        |
| [PSN](https://github.com/larabros/oauth2-psn)                          | composer require larabros/oauth2-psn                |
| [Salesforce](https://github.com/stevenmaguire/oauth2-salesforce)       | composer require stevenmaguire/oauth2-salesforce    |
| [Slack](https://github.com/adam-paterson/oauth2-slack)                 | composer require adam-paterson/oauth2-slack         |
| [Spotify](https://github.com/ker0x/oauth2-spotify)                     | composer require kerox/oauth2-spotify               |
| [SymfonyConnect](https://github.com/qdequippe/oauth2-symfony-connect)  | composer require qdequippe/oauth2-symfony-connect   |
| [Strava](https://github.com/Edwin-Luijten/oauth2-strava)               | composer require edwin-luijten/oauth2-strava        |
| [Stripe](https://github.com/adam-paterson/oauth2-stripe)               | composer require adam-paterson/oauth2-stripe        |
| [Twitch](https://github.com/tpavlek/oauth2-twitch)                     | composer require depotwarehouse/oauth2-twitch       |
| [Uber](https://github.com/stevenmaguire/oauth2-uber)                   | composer require stevenmaguire/oauth2-uber          |
| [Unsplash](https://github.com/hughbertd/oauth2-unsplash)               | composer require hughbertd/oauth2-unsplash          |
| [Vimeo](https://github.com/saf33r/oauth2-vimeo)                        | composer require saf33r/oauth2-vimeo                |
| [VKontakte](https://github.com/j4k/oauth2-vkontakte)                   | composer require j4k/oauth2-vkontakte               |
| [Wave](https://github.com/qdequippe/oauth2-wave)                       | composer require qdequippe/oauth2-wave              |
| [Yahoo](https://github.com/hayageek/oauth2-yahoo)                      | composer require hayageek/oauth2-yahoo              |
| [Yandex](https://github.com/rakeev/oauth2-yandex)                      | composer require aego/oauth2-yandex                 |
| [Zendesk](https://github.com/stevenmaguire/oauth2-zendesk)             | composer require stevenmaguire/oauth2-zendesk       |
| generic                                                                | configure any unsupported provider                  |

<span name="end-client-downloader-table"></span>

### Step 2) Configure the provider

Awesome! Now, you'll configure your provider. For Facebook,
this will look something like this.

```yml
# config/packages/knpu_oauth2_client.yaml
knpu_oauth2_client:
    clients:
        # the key "facebook_main" can be anything, it
        # will create a service: "knpu.oauth2.client.facebook_main"
        facebook_main:
            # this will be one of the supported types
            type: facebook
            client_id: '%env(OAUTH_FACEBOOK_ID)%'
            client_secret: '%env(OAUTH_FACEBOOK_SECRET)%'
            # the route that you're redirected to after
            # see the controller example below
            redirect_route: connect_facebook_check
            redirect_params: {}
            graph_api_version: v2.12
```

Notice the two `'%env(var)%'`calls? Add these anywhere in your `.env` and `.env.dist` files.
These are the credentials for the OAuth provider. For Facebook, you'll get these by registering
your app on [developers.facebook.com](https://developers.facebook.com/apps/).

```bash
# .env
# ...

OAUTH_FACEBOOK_ID=fb_id
OAUTH_FACEBOOK_SECRET=fb_secret
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
namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook", name="connect_facebook_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('facebook_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
	    	'public_profile', 'email' // the scopes you want to access
            ]);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
        $client = $clientRegistry->getClient('facebook_main');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
            $user = $client->fetchUser();

            // do something with all this new power!
	    // e.g. $name = $user->getFirstName();
            var_dump($user); die;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }
    }
}
```

Now, just go (or link to) `/connect/facebook` and watch the flow!

After completing the OAuth2 flow, the `$client` object can be used
to fetch the user, the access token, or other things:

```php
// get the user directly
$user = $client->fetchUser();

// OR: get the access token and then user
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
blank and create a [Guard authenticator](https://symfonycasts.com/screencast/symfony-security),
which will hold similar logic.

A `SocialAuthenticator` base class exists to help with a few things:

```php
namespace App\Security;

use App\Entity\User; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MyFacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
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
        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        // 2) do we have a matching user by email?
        $user = $this->em->getRepository(User::class)
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
            // "facebook_main" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('facebook_main');
	}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('app_homepage');

        return new RedirectResponse($targetUrl);
    
        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    // ...
}
```

Next, register your authenticator in `security.yaml` under the `guard` section:

```diff
# app/config/packages/security.yaml
security:
    # ...
    firewalls:
    	# ...
        main:
	    # ...
+            guard:
+                authenticators:
+                    - App\Security\MyFacebookAuthenticator
```

For more details: see http://symfony.com/doc/current/cookbook/security/guard-authentication.html#step-2-configure-the-authenticator.

**CAUTION** You *can* also inject the individual client (e.g. `FacebookClient`)
into your authenticator instead of the `ClientRegistry`. However, this may cause
circular reference issues and degrades performance (because authenticators are instantiated
on every request, even though you *rarely* need the `FacebookClient` to be created).
The `ClientRegistry` lazily creates the client objects.

### Authenticating any OAuth user

If you don't need to fetch/persist any information about the user, you can use the
`OAuthUserProvider` service to quickly authenticate them in your application (if you're
using Doctrine, use the normal [entity user provider](http://symfony.com/doc/current/security/entity_provider.html)).

First define the user provider in your `security.yaml` file:

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

## Storing and refreshing tokens

You have a couple of options to store access tokens for use at a later time:

1. Store the `AccessToken` object (eg. serializing into the session), this allows you to check expiry before refreshing
    ```php
    // Fetch and store the AccessToken
    $accessToken = $client->getAccessToken();
    $session->set('access_token', $accessToken);

    // Load the access token from the session, and refresh if required
    $accessToken = $session->get('access_token');

    if ($accessToken->hasExpired()) {
        $accessToken = $client->refreshAccessToken($accessToken->getRefreshToken);

        // Update the stored access token for next time
        $session->set('access_token', $accessToken);
    }
    ```

2. Store the refresh token string (eg. in the dabatase `user.refresh_token`), this means you must always refresh. 
    You can also store the access token and expiration and then avoid the refresh until the access token is actually expired.
    ```php
    // Fetch the AccessToken and store the refresh token
    $accessToken = $client->getAccessToken();
    $user->setRefreshToken($accessToken->getRefreshToken());
    $entityManager->flush();

    // Get a new AccessToken from the refresh token, and store the new refresh token for next time
    $accessToken = $client->refreshAccessToken($user->getRefreshToken);
    $user->setRefreshToken($accessToken->getRefreshToken());
    $entityManager->flush();
```

Depending on your OAuth2 provider, you may need to pass some parameters when initially creating and/or refreshing the token:

```php
// Some providers may require special parameters when creating the token in order to allow refreshing
$accessToken = $client->getAccessToken(['scopes' => 'offline_access']);

// They may also require special parameters when refreshing the token
$accessToken = $client->refreshAccessToken($accessToken->getRefreshtoken(), ['scopes' => 'offline_access']);
```

## Configuration

Below is the configuration for *all* of the supported OAuth2 providers.
**Don't see the one you need?** Use the `generic` provider to configure
any provider.

```yml
# config/packages/knpu_oauth2_client.yaml
knpu_oauth2_client:
    # can be set to the service id of a service that implements Guzzle\ClientInterface
    # http_client: null

    # options to configure the default http client
    # http_client_options:
    #     timeout: 0
    #     proxy: null
    #     Use only with proxy option set
    #     verify: false

    clients:
        # will create service: "knpu.oauth2.client.amazon"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\AmazonClient
        # composer require luchianenco/oauth2-amazon
        amazon:
            # must be "amazon" - it activates that type!
            type: amazon
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_AMAZON_CLIENT_ID)%'
            client_secret: '%env(OAUTH_AMAZON_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_amazon_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.appid"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\AppIdClient
        # composer require jampire/oauth2-appid
        appid:
            # must be "appid" - it activates that type!
            type: appid
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_APPID_CLIENT_ID)%'
            client_secret: '%env(OAUTH_APPID_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_appid_check
            redirect_params: {}
            # IBM App ID base URL. For example, "https://us-south.appid.cloud.ibm.com/oauth/v4". More details at https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started
            base_auth_uri: '%env(OAUTH_APPID_BASE_AUTH_URI)%'
            # IBM App ID service tenant ID. For example, "1234-5678-abcd-efgh". More details at https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started
            tenant_id: '%env(OAUTH_APPID_TENANT_ID)%'
            # Identity Provider code. Defaults to "saml". More details at https://cloud.ibm.com/docs/services/appid?topic=appid-getting-started
            # idp: '%env(OAUTH_APPID_IDP)%'
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.apple"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\AppleClient
        # composer require patrickbussmann/oauth2-apple
        apple:
            # must be "apple" - it activates that type!
            type: apple
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_APPLE_CLIENT_ID)%'
            # a route name you'll create
            redirect_route: connect_apple_check
            redirect_params: {}
            team_id: null
            key_file_id: null
            key_file_path: null
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.auth0"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\Auth0Client
        # composer require riskio/oauth2-auth0
        auth0:
            # must be "auth0" - it activates that type!
            type: auth0
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_AUTH0_CLIENT_ID)%'
            client_secret: '%env(OAUTH_AUTH0_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_auth0_check
            redirect_params: {}
            # Your custom/definite Auth0 domain, e.g. "login.mycompany.com". Set this if you use Auth0's Custom Domain feature. The "account" and "region" parameters will be ignored in this case.
            # custom_domain: null
            # Your Auth0 domain/account, e.g. "mycompany" if your domain is "mycompany.auth0.com"
            # account: null
            # Your Auth0 region, e.g. "eu" if your tenant is in the EU.
            # region: null
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.azure"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\AzureClient
        # composer require thenetworg/oauth2-azure
        azure:
            # must be "azure" - it activates that type!
            type: azure
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_AZURE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_AZURE_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_azure_check
            redirect_params: {}
            # Domain to build login URL
            # url_login: 'https://login.microsoftonline.com/'
            # Oauth path to authorize against
            # path_authorize: '/oauth2/authorize'
            # Oauth path to retrieve a token
            # path_token: '/oauth2/token'
            # Oauth scope send with the request
            # scope: {}
            # The tenant to use, default is `common`
            # tenant: 'common'
            # Domain to build request URL
            # url_api: 'https://graph.windows.net/'
            # Oauth resource field
            # resource: null
            # The API version to run against
            # api_version: '1.6'
            # Send resource field with auth-request
            # auth_with_resource: true
            # The endpoint version to run against
            # default_end_point_version: '1.0'
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.bitbucket"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\BitbucketClient
        # composer require stevenmaguire/oauth2-bitbucket
        bitbucket:
            # must be "bitbucket" - it activates that type!
            type: bitbucket
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_BITBUCKET_CLIENT_ID)%'
            client_secret: '%env(OAUTH_BITBUCKET_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_BOX_CLIENT_ID)%'
            client_secret: '%env(OAUTH_BOX_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_box_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.buddy"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\BuddyClient
        # composer require buddy-works/oauth2-client
        buddy:
            # must be "buddy" - it activates that type!
            type: buddy
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_BUDDY_CLIENT_ID)%'
            client_secret: '%env(OAUTH_BUDDY_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_buddy_check
            redirect_params: {}
            # Base API URL, modify this for self-hosted instances
            # base_api_url: https://api.buddy.works
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.buffer"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\BufferClient
        # composer require tgallice/oauth2-buffer
        buffer:
            # must be "buffer" - it activates that type!
            type: buffer
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_BUFFER_CLIENT_ID)%'
            client_secret: '%env(OAUTH_BUFFER_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_CANVAS_LMS_CLIENT_ID)%'
            client_secret: '%env(OAUTH_CANVAS_LMS_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_canvas_lms_check
            redirect_params: {}
            # URL of Canvas Instance (e.g. https://canvas.instructure.com)
            canvas_instance_url: null
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_CLEVER_CLIENT_ID)%'
            client_secret: '%env(OAUTH_CLEVER_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_DEVIAN_ART_CLIENT_ID)%'
            client_secret: '%env(OAUTH_DEVIAN_ART_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_DIGITAL_OCEAN_CLIENT_ID)%'
            client_secret: '%env(OAUTH_DIGITAL_OCEAN_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_DISCORD_CLIENT_ID)%'
            client_secret: '%env(OAUTH_DISCORD_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_DRIBBBLE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_DRIBBBLE_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_DROPBOX_CLIENT_ID)%'
            client_secret: '%env(OAUTH_DROPBOX_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_DRUPAL_CLIENT_ID)%'
            client_secret: '%env(OAUTH_DRUPAL_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_drupal_check
            redirect_params: {}
            # Drupal oAuth2 server URL
            base_url: '%env(OAUTH_DRUPAL_BASE_URL)%'
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.elance"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\ElanceClient
        # composer require stevenmaguire/oauth2-elance
        elance:
            # must be "elance" - it activates that type!
            type: elance
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_ELANCE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_ELANCE_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_elance_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.eve_online"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\EveOnlineClient
        # composer require evelabs/oauth2-eveonline
        eve_online:
            # must be "eve_online" - it activates that type!
            type: eve_online
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_EVE_ONLINE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_EVE_ONLINE_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_eve_online_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.eventbrite"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\EventbriteClient
        # composer require stevenmaguire/oauth2-eventbrite
        eventbrite:
            # must be "eventbrite" - it activates that type!
            type: eventbrite
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_EVENTBRITE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_EVENTBRITE_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_FACEBOOK_CLIENT_ID)%'
            client_secret: '%env(OAUTH_FACEBOOK_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_FITBIT_CLIENT_ID)%'
            client_secret: '%env(OAUTH_FITBIT_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_FOUR_SQUARE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_FOUR_SQUARE_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_four_square_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.geocaching"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\GeocachingClient
        # composer require surfoo/oauth2-geocaching
        geocaching:
            # must be "geocaching" - it activates that type!
            type: geocaching
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_GEOCACHING_CLIENT_ID)%'
            client_secret: '%env(OAUTH_GEOCACHING_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_geocaching_check
            redirect_params: {}
            # dev, staging or production
            environment: production
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.github"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\GithubClient
        # composer require league/oauth2-github
        github:
            # must be "github" - it activates that type!
            type: github
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_GITHUB_CLIENT_ID)%'
            client_secret: '%env(OAUTH_GITHUB_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_GITLAB_CLIENT_ID)%'
            client_secret: '%env(OAUTH_GITLAB_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_GOOGLE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_GOOGLE_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_google_check
            redirect_params: {}
            # Optional value for sending access_type parameter. More detail: https://developers.google.com/identity/protocols/OpenIDConnect#authenticationuriparameters
            # access_type: null
            # Optional value for sending hd parameter. More detail: https://developers.google.com/identity/protocols/OpenIDConnect#hd-param
            # hosted_domain: null
            # Optional value for additional fields to be requested from the user profile. If set, these values will be included with the defaults. More details: https://developers.google.com/+/web/api/rest/latest/people
            # user_fields: {}
            # Optional value if you don't want or need to enable Google+ API access.
            # use_oidc_mode: false
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.headhunter"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\HeadHunterClient
        # composer require alexmasterov/oauth2-headhunter
        headhunter:
            # must be "headhunter" - it activates that type!
            type: headhunter
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_HEADHUNTER_CLIENT_ID)%'
            client_secret: '%env(OAUTH_HEADHUNTER_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_HEROKU_CLIENT_ID)%'
            client_secret: '%env(OAUTH_HEROKU_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_INSTAGRAM_CLIENT_ID)%'
            client_secret: '%env(OAUTH_INSTAGRAM_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_instagram_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.jira"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\JiraClient
        # composer require mrjoops/oauth2-jira
        jira:
            # must be "jira" - it activates that type!
            type: jira
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_JIRA_CLIENT_ID)%'
            client_secret: '%env(OAUTH_JIRA_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_jira_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.keycloak"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\KeycloakClient
        # composer require stevenmaguire/oauth2-keycloak
        keycloak:
            # must be "keycloak" - it activates that type!
            type: keycloak
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_KEYCLOAK_CLIENT_ID)%'
            client_secret: '%env(OAUTH_KEYCLOAK_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_keycloak_check
            redirect_params: {}
            # Keycloak server URL
            auth_server_url: null
            # Keycloak realm
            realm: null
            # Optional: Encryption algorith, i.e. RS256
            # encryption_algorithm: null
            # Optional: Encryption key path, i.e. ../key.pem
            # encryption_key_path: null
            # Optional: Encryption key, i.e. contents of key or certificate
            # encryption_key: null
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.linkedin"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\LinkedInClient
        # composer require league/oauth2-linkedin
        linkedin:
            # must be "linkedin" - it activates that type!
            type: linkedin
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_LINKEDIN_CLIENT_ID)%'
            client_secret: '%env(OAUTH_LINKEDIN_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_linkedin_check
            redirect_params: {}
            # Optional value to specify Linkedin's API version to use. As the time of writing, v1 is still used by default by league/oauth2-linkedin.
            # api_version: null
            # Optional value to specify fields to be requested from the profile. Since Linkedin's API upgrade from v1 to v2, fields and authorizations policy have been enforced. See https://docs.microsoft.com/en-us/linkedin/consumer/integrations/self-serve/sign-in-with-linkedin for more details.
            # fields: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.mail_ru"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\MailRuClient
        # composer require aego/oauth2-mailru
        mail_ru:
            # must be "mail_ru" - it activates that type!
            type: mail_ru
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_MAIL_RU_CLIENT_ID)%'
            client_secret: '%env(OAUTH_MAIL_RU_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_MICROSOFT_CLIENT_ID)%'
            client_secret: '%env(OAUTH_MICROSOFT_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_microsoft_check
            redirect_params: {}
            # Optional value for URL Authorize
            # url_authorize: null
            # Optional value for URL Access Token
            # url_access_token: null
            # Optional value for URL Resource Owner Details
            # url_resource_owner_details: null
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.mollie"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\MollieClient
        # composer require mollie/oauth2-mollie-php
        mollie:
            # must be "mollie" - it activates that type!
            type: mollie
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_MOLLIE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_MOLLIE_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_ODNOKLASSNIKI_CLIENT_ID)%'
            client_secret: '%env(OAUTH_ODNOKLASSNIKI_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_odnoklassniki_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.okta"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\OktaClient
        # composer require foxworth42/oauth2-okta
        okta:
            # must be "okta" - it activates that type!
            type: okta
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_OKTA_CLIENT_ID)%'
            client_secret: '%env(OAUTH_OKTA_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_okta_check
            redirect_params: {}
            # Issuer URI from Okta
            issuer: https://mycompany.okta.com/oauth2/default
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.paypal"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\PaypalClient
        # composer require stevenmaguire/oauth2-paypal
        paypal:
            # must be "paypal" - it activates that type!
            type: paypal
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_PAYPAL_CLIENT_ID)%'
            client_secret: '%env(OAUTH_PAYPAL_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_PSN_CLIENT_ID)%'
            client_secret: '%env(OAUTH_PSN_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_SALESFORCE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_SALESFORCE_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_SLACK_CLIENT_ID)%'
            client_secret: '%env(OAUTH_SLACK_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_slack_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.spotify"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\SpotifyClient
        # composer require kerox/oauth2-spotify
        spotify:
            # must be "spotify" - it activates that type!
            type: spotify
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_SPOTIFY_CLIENT_ID)%'
            client_secret: '%env(OAUTH_SPOTIFY_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_spotify_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.symfony_connect"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\SymfonyConnectClient
        # composer require qdequippe/oauth2-symfony-connect
        symfony_connect:
            # must be "symfony_connect" - it activates that type!
            type: symfony_connect
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_SYMFONY_CONNECT_CLIENT_ID)%'
            client_secret: '%env(OAUTH_SYMFONY_CONNECT_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_symfony_connect_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.strava"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\StravaClient
        # composer require edwin-luijten/oauth2-strava
        strava:
            # must be "strava" - it activates that type!
            type: strava
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_STRAVA_CLIENT_ID)%'
            client_secret: '%env(OAUTH_STRAVA_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_strava_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.stripe"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\StripeClient
        # composer require adam-paterson/oauth2-stripe
        stripe:
            # must be "stripe" - it activates that type!
            type: stripe
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_STRIPE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_STRIPE_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_stripe_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.twitch"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\TwitchClient
        # composer require depotwarehouse/oauth2-twitch
        twitch:
            # must be "twitch" - it activates that type!
            type: twitch
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_TWITCH_CLIENT_ID)%'
            client_secret: '%env(OAUTH_TWITCH_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_twitch_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.uber"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\UberClient
        # composer require stevenmaguire/oauth2-uber
        uber:
            # must be "uber" - it activates that type!
            type: uber
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_UBER_CLIENT_ID)%'
            client_secret: '%env(OAUTH_UBER_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_UNSPLASH_CLIENT_ID)%'
            client_secret: '%env(OAUTH_UNSPLASH_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_VIMEO_CLIENT_ID)%'
            client_secret: '%env(OAUTH_VIMEO_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_VKONTAKTE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_VKONTAKTE_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_vkontakte_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.wave"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\WaveClient
        # composer require qdequippe/oauth2-wave
        wave:
            # must be "wave" - it activates that type!
            type: wave
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_WAVE_CLIENT_ID)%'
            client_secret: '%env(OAUTH_WAVE_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_wave_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true

        # will create service: "knpu.oauth2.client.yahoo"
        # an instance of: KnpU\OAuth2ClientBundle\Client\Provider\YahooClient
        # composer require hayageek/oauth2-yahoo
        yahoo:
            # must be "yahoo" - it activates that type!
            type: yahoo
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_YAHOO_CLIENT_ID)%'
            client_secret: '%env(OAUTH_YAHOO_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_YANDEX_CLIENT_ID)%'
            client_secret: '%env(OAUTH_YANDEX_CLIENT_SECRET)%'
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
            # add and set these environment variables in your .env files
            client_id: '%env(OAUTH_ZENDESK_CLIENT_ID)%'
            client_secret: '%env(OAUTH_ZENDESK_CLIENT_SECRET)%'
            # a route name you'll create
            redirect_route: connect_zendesk_check
            redirect_params: {}
            # Your Zendesk subdomain
            subdomain: null
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
# config/packages/knpu_oauth2_client.yaml
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
            client_id: '%env(foo_bar_client_id)%'
            client_secret: '%env(foo_bar_client_secret)%'
            redirect_route: connect_facebook_check
            redirect_params: {}
            # whether to check OAuth2 "state": defaults to true
            # use_state: true
```

That's it! Now you'll have a `knpu.oauth2.client.foo_bar_oauth` service
you can use.

## Extending/Decorating Client Classes

Maybe you need some extra services inside your client class? No problem! You can
decorate existing client class with your own implementation. All you need is
new class that implement OAuth2ClientInterface:

```php
namespace App\Client;

use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\AzureClient;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class CacheableAzureClient implements OAuth2ClientInterface
{
    private $client;
    private $cache;

    public function __construct(AzureClient $client, AdapterInterface $cache)
    {
        // ...
    }

    // override all public functions and call the method on the internal $this->client object
    // but add caching wherever you need it
}
```

and configure it:

```yml
# config/services.yaml
services:
    App\Client\CacheableAzureClient:
        decorates: knpu.oauth2.client.azure
```

## Contributing

Of course, open source is fueled by everyone's ability to give just a little
bit of their time for the greater good. If you'd like to see a feature, you
can request it - but creating a pull request is an even better way to get
things done.

Either way, please feel comfortable submitting issues or pull requests:
all contributions and questions are warmly appreciated :).
