# KnpUOAuth2ClientBundle

This bundle integrates with [league/oauth2-client](http://oauth2-client.thephpleague.com/)
and allows you to easily create different "providers" as services in your container.

If you configure a provider called "main_facebook", then you'll have a new service
in your container called `knpu.oauth2.main_facebook`.

```yml
# config.yml
knpu_oauth2_client:
    providers:
        # the key "facebook_client" can be anything: it determines the service name
        # will create a service: "knpu.oauth2.facebook_client"
        facebook_client:
            # must be one of the valid types
            type: facebook
            client_id: foo
            client_secret: bar
            graph_api_version: 2.3
            # the route that you're redirected to after
            redirect_route: connect_facebook_check
            redirect_params: {}

        # will create a service: "knpu.oauth2.github_client"
        github_client:
            # must be one of the valid types
            type: github
            client_id: foo
            client_secret: bar
            redirect_route: connect_github_check
            redirect_params: {}

        # todo - add more
        # http://oauth2-client.thephpleague.com/providers/thirdparty/
```
