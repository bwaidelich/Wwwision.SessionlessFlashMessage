# Wwwision.SessionlessFlashMessage

Simple [Flow](https://flow.neos.io/) Package demonstrating how [Flash Messages](http://api.rubyonrails.org/classes/ActionDispatch/Flash.html)
could be implemented without having to rely on server-side sessions.

## Current implementation

Currently Flow (and any other Framework I checked) uses a very simple
approach to Flash Messages:

1. A string (or sometimes a more complex object including title, categorization
   etc) is stored into the session before a HTTP redirect is triggered.
2. Upon displaying of the target action, any Flash Message is read and
   removed from the session and rendered to the client

## Motivation to change this

1. Most importantly the current implementation defeats caching[^1]
2. Server-side Sessions are not easy to scale
3. Storing objects in the session is error prone because the object's
   implementation can change
4. The FlashMessage container is filled up with old messages if they are
   not rendered
5. An exception is thrown if Flash Messages are used in a context that
   doesn't support sessions (e.g. from the CLI)

## Implementation

This example replaces the default `Neos\Flow\Mvc\FlashMessageContainer` by
a custom `TransientFlashMessageContainer` that shares the same API and even
the implementation but doesn't start a Session.
It also overrides the `redirectToUri()` method of the controller in order
to flush that container and add any Flash Messages to a HTTP Cookie (that
expires with the Browser session).
Some JavaScript snippet checks for that cookie, renders any Flash Messages
it contains and removes the cookie afterwards.
That's all.

### Security Considerations

Using a cookie to store the Flash Message content as plaintext might pose
a risk, because that cookie could be altered by the client.
But that level of access allows to manipulate the whole website anyways,
so I wouldn't consider this a relevant issue.
If in doubt, the implementation could filter the Flash Message to only
render plaintext.

----------

[^1]: By default the Flash Message is displayed on redirect which is a
      GET (i.e. [safe](https://tools.ietf.org/html/rfc7231#section-4.2.1))
      request and should be cacheable according to the [HTTP specification](https://tools.ietf.org/html/rfc7231#section-4.2.3)
      That's the reason why we can't use Flash Messages in the Neos Frontend