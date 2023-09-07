# symfony-pokeapi

- [Requirements](#requirements)
- [Installation](#installation)

This is a simple symfony project 

## Requirements

- Docker
- Docker Compose

## Installation

Clone the project and go to the root direction.

Change the name from .env.example to .env:
```bash
mv app/.env.example app/.env
```

Then we generate a APP_SECRET and add it to the .env with your favourite editor.
You can change the PokeAPI url to your own hosted API or use the default url.

Now run this command:
```bash
docker-compose up -d
```

Go to your web browser and open http://127.0.0.1:8080
