## Requirements

- docker
- docker-compose
- make

## Setup

Stop all containers which run on port 80 and run:

```bash
make up-and-build
```

To run tests

```bash
make tests-all-with-build symfonycnf=".env.test"
```

For more available commands check

```bash
make
```

## Routing and API endpoints

```
localhost:80
```

### Products

Price of product is in cents.

#### List products

```
GET localhost:80/api/products
```

#### Delete product

```
DELETE localhost:80/api/products/{id}
```

#### Update product

```
PUT localhost:80/api/products/{id}
```

```http request
{"title":"Some title","priceAmount":100}
```

#### Create product

```
POST localhost:80/api/products
```

```http request
{"title":"Some title","priceAmount":100}
```

### Carts

#### Create cart

```
POST localhost:80/api/carts
```

#### Delete product from cart

```
DELETE localhost:80/api/carts/{cartId}/product/{productId}
```

#### Add product to the cart

```
POST localhost:80/api/carts/{cartId}/product/{productId}
```

#### Get single cart

```
GET localhost:80/api/carts/{cartId}
```