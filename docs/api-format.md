# API Format

An API request should always return properly formatted JSON with the correct HTTP status code.

Common status codes used in this application:

* 200 - OK
* 201 - Created (Resource Created)
* 400 - Bad Request
* 401 - Unauthorized
* 404 - Not found (Resource, Resource Collection, or API endpoint not found)
* 405 - Method Not Allowed
* 422 - Unprocessable Entity (If parameters are missing or have errors)
* 499 - Token required

The following status codes are handled by the API automatically unless overridden:

* 404
* 405
* 500

### Basic Response

A basic response should include:

* status int|required
* message string|required
* details string|optional

Additional parameters are optional.

Example:

```
{
    "status": 200
    "message": "API status message."
}
```

### Error Response

* status int|required
* errors array|required

Example with validation error:

```
{
    "status": 422
    "errors": [
        "The token field is required.",
        "The email field is required.",
        "The password field is required."
    ]
}
```

### GET: Resource Response

* status int|required
* data array|required

Example:

```
{
    "status" : 200,
    "data":[{
        "name": "Devin Price",
        "slug": "devin-price",
        "created_at": "2018-06-04 16:37:24"
    }]
}
```

### POST: Resource Response

* status int|required
* message string|required

Example:

```
{
    "status": 201,
    "message": "Resource created."
}
```

### GET: Collection Response

* status int|required
* data array|required
* links object|required
    * first
    * last
    * prev
    * next
* meta object|required
    * current_page
    * from
    * last_page
    * path
    * per_page
    * to
    * total
