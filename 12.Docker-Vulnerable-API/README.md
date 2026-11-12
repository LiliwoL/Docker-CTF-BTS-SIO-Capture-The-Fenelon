# Docker Vulnerbable API

Basé sur: https://github.com/erev0s/VAmPI

## Lancement

docker compose up -d

## Création de la base de données

curl http://127.0.0.1:5000/createdb
{ “message”: “Database populated.” }

## Lancement nuclei

nuclei -u http://127.0.0.1:5000 -tags exposure