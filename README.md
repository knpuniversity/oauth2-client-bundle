# KnpUOAuth2ClientBundle

This bundle integrates with [league/oauth2-client](http://oauth2-client.thephpleague.com/)
and allows you to easily create different "providers" are services in your container.

```yml
# config.yml
knpu_oauth2_client:
    providers:
        facebook:
            client_id: foo
            client_secret: bar
            graph_api_version: 2.3
            # the route that you're redirected to after
            redirect_route: connect_facebook_check
            redirect_params: {}
        github:
            client_id: foo
            client_secret: bar
            redirect_route: connect_github_check
            redirect_params: {}
        # todo - add more
        # http://oauth2-client.thephpleague.com/providers/thirdparty/
```
