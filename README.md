# WP REST SPA Endpoints

###### Provides additional endpoints for the WordPress REST API. Originally intended to be used with a Single Page Application, but can be used in any way you like. 

###### _The irony of calling a WP plugin "rest, spa" is not lost on me._

### Endpoints

```
GET    rest-spa/v1/posts
GET    rest-spa/v1/posts/featured
GET    rest-spa/v1/posts/recent
GET    rest-spa/v1/menu
```

---

### URL Query Parameters
[What are Query Params?](https://en.wikipedia.org/wiki/Query_string)

#### _URL Params for /posts/_

1. category - the category slug [OPTIONAL]
2. exclude - post ID(s) to exclude, CSV format [OPTIONAL]
    - e.g. ?exclude=1,2,3,4,5
3. page - Current page [OPTIONAL]
    - rest-spa supports simple pagination but **note** that your page number can exceed the post count and an empty
      result set will be returned.
4. count - number of posts to retrieve, overrides plugin settings [OPTIONAL]
---
#### URL Params for /posts/featured

1. category - the category slug [OPTIONAL]

---
#### URL Params for /posts/recent

1. category - the category slug [OPTIONAL]
2. count - number of posts to retrieve, overrides plugin settings [OPTIONAL]

---
#### URL Params for /menu

1. id - the menu ID to retrieve URLs for. [REQUIRED]
    - Menu ID can be retrieved by 
going to Appearance -> Menus -> Hover over "Delete Menu" and look for menu=? in the URL


### Plugin Settings

There is a settings page under the Admin Menu item "rest-spa".
The following options are available:

- Posts per page
- Recent posts count
- you can enter `-1` to retrieve ALL posts (not recommended)

---
###### "If it doesn't work, it wasn't me" - _trimination_
