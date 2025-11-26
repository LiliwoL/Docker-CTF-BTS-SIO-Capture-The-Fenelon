# XSS


# Lancement

docker-compose up -d

# Accès

http://localhost:8000

# Identifiants

admin / password

# Page vulnérable

http://localhost:8000/vuln.php

# Payload XSS

Affiche le cookie dans la console
http://localhost:8000/vuln.php?message=<script>console.log('Cookie:', document.cookie);</script>
