#!/usr/bin/env bash

# Nettoyage de l'écran
clear

# Lecture du fichier .env
if [ -f .env ]; then
  set -a
  . .env
  set +a

  # Echo with figlet if exists
  if type "figlet" > /dev/null; then
    figlet $COMPOSE_PROJECT_NAME
  else
    echo "Docker $COMPOSE_PROJECT_NAME"
  fi

  echo "------------------------------"
  echo ""
fi

if [ -z "$1" ]
  then
    echo "Démarrage de tous les containers"
    echo "------------------------------"
    echo "------------------------------"
		# Commande de lancement (avec lecture du fichier .env)
    docker-compose --env-file .env up -d --build --force-recreate
else
	echo "Démarrage du container $@"
  echo "------------------------------"
  echo "------------------------------"
  docker-compose --env-file .env up $@ -d --build --force-recreate
fi



# Create network for containers
#docker network create prestashop-net

# Container for mysql 5.6
#docker run -ti --name docker-prestashop-mysql --network prestashop-net -e MYSQL_ROOT_PASSWORD=admin -p 3307:3306 -d mysql:5.6

# for ARM
#docker run -ti --name docker-prestashop-mysql --network prestashop-net -e MYSQL_ROOT_PASSWORD=admin -p 3307:3306 -d arm64v8/mysql:5.6


# Container Prestashop
#docker run -ti --name docker-prestashop --network prestashop-net -e DB_SERVER=docker-prestashop-mysql -e PS_ERASE_DB=1 -e PS_INSTALL_AUTO=1 -e PS_LANGUAGE=fr -e PS_DEMO_MODE=1 -e ADMIN_MAIL=demo@prestashop.com -e ADMIN_PASSWD=azerty -p 808:80 -d prestashop/prestashop:1.4
