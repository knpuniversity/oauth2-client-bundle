# CHANGELOG

## 2.0.0

* [BC BREAK]: Changed signature of `OAuth2ClientInterface::getAccessToken()`
  to include a `array $options = []` argument - #230.

* Added `OAuth2Client::isStateless()` protected method to ease overriding
  the `redirect()` method for custom situations - #234.

* Dropped support for Symfony versions *older* than 4.4.
