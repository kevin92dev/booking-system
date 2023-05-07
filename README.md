# Booking Management
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
