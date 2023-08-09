# Land Route Finder

Land Route Finder is a microservice that calculates the land route between two countries using their country codes.<br>
It's built using Symfony and provides a RESTful API to interact with the service.

## API Usage

### Calculate Route
Calculate the land route between two countries:

* URL: /routing/{origin}/{destination}
* Method: POST
* URL Params: origin=[string], destination=[string]
* Success Response: 200 OK, with JSON containing the route.
* Error Response: 400 Bad Request if the origin or destination is invalid, or 404 Not Found if no route is found.
