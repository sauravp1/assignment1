
# Assignment1

A basic php symfony application to demonstrate CRUD operations.

## What does it do?
- Create, Update, Read or Remove Customer Info
- Create, Update, Read or Remove Order Info

## API Reference

### Customer

#### Get all customer

```http
  GET api/customer
```


#### Get customer

```http
  GET api/customer/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of item to fetch |

#### Add customer

```http
  POST api/customer
```
| Fields | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `firstName`      | `str` | **Required**. First Name of the customer|
| `lastName`      | `str` | **Required**. Last Name of the customer|
| `email`      | `str` | **Required**. Email of the customer |
| `phoneNumber`      | `str` | **Required**. Phone Number of the customer |


#### Update customer

```http
  PUT api/customer/{id}
```
| Fields | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `firstName`      | `str` | **Optional**. First Name of the customer|
| `lastName`      | `str` | **Optional**. Last Name of the customer|
| `email`      | `str` | **Optional**. Email of the customer |
| `phoneNumber`      | `str` | **Optional**. Phone Number of the customer |

#### Remove customer

```http
  DELETE api/customer/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of customer to delete |



### Order

#### Get all order

```http
  GET api/order
```


#### Get order

```http
  GET api/order/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of order to fetch |

#### Add order

```http
  POST api/order
```
| Fields | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `customer_id`      | `int` | **Required**. id of the customer|
| `product_id`      | `int` | **Required**. id of the product|
| `price`      | `float` | **Required**. price of the product|


#### Update order

```http
  PUT api/order/{id}
```
| Fields | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `customer_id`      | `int` | **Required**. id of the customer|
| `product_id`      | `int` | **Required**. id of the product|
| `price`      | `float` | **Required**. price of the product|

#### Remove order

```http
  DELETE api/order/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `int` | **Required**. Id of order to delete |


