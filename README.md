# Booking Management
## How does it work?
This application will help you to maximize your profit when you have to decide how to handle the booking of your 
property.

This application exposes two endpoints ```/stats``` and ```/maximize```. The ```/stats``` endpoint will give you the 
average profit you're getting for a list of bookings. The awesome ```/maximize``` endpoint will 
give you the best booking combination to get the maximum profit from your rentals, you just need to provide the 
bookings you can have, it will do the job for you.

## How to install
1. Clone the repository
2. Run ```composer start```
3. Now the application is running over 8080 port

### Available requests:
Stats endpoint:
```
curl --location '127.0.0.1:8080/stats' \
--header 'Content-Type: application/json' \
--data '[
    {
        "request_id": "bookata_XY123",
        "check_in": "2020-01-01",
        "nights": 5,
        "selling_rate": 200,
        "margin": 20
    }
]'
```

Maximize endpoint:
```
curl --location '127.0.0.1:8080/maximize' \
--header 'Content-Type: application/json' \
--data '[
    {
        "request_id": "bookata_XY123",
        "check_in": "2020-01-01",
        "nights": 5,
        "selling_rate": 200,
        "margin": 20
    },
    {
        "request_id": "kayete_PP234",
        "check_in": "2020-01-04",
        "nights": 4,
        "selling_rate": 156,
        "margin": 5
    }
]'
```

*Find OpenAPI definition here*: https://app.swaggerhub.com/apis-docs/BlackfireSFL/BackendChallenge/1.0.1

## Other available commands
```composer tests```: It executes the application tests

```composer tests-coverage```: It executes the application tests and generates the HTML 
tests coverage report on ```var/``` folder

```composer mutant-testing```: It executes the application mutant testing and generates the HTML
tests coverage report on ```var/``` folder
