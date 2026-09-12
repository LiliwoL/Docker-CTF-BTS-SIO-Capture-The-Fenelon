# Choose the right Debian tag
# https://hub.docker.com/_/debian/tags
FROM debian:trixie-slim

# Récupération des variables depuis le fichier .env
# -------------------------------------------------
ARG FENELON_1
ARG FENELON_2
ARG FENELON_3

ARG USER_1
ARG PASSWORD_1
ARG USER_2
ARG PASSWORD_2
ARG USER_3
ARG PASSWORD_3
#ARG USER_4
#ARG PASSWORD_4


# Commandes d'installation de base
# --------------------------------
RUN apt update && apt install openssh-server locales vim zsh iproute2 sudo nano rsyslog -y
RUN locale-gen fr_FR.UTF-8
RUN update-locale

# useradd https://manpages.debian.org/trixie/passwd/useradd.8.fr.html
# -m create home directoiry

# Création de l'utilisateur user1
# -------------------------------
RUN useradd -m --badname -U -d /home/${USER_1} -s /usr/bin/zsh ${USER_1}
RUN echo "${USER_1}:${PASSWORD_1}" | chpasswd
# Création du fichier .zshrc
RUN echo "# Empty .zshrc" > /home/${USER_1}/.zshrc
# Droits du répertoire home
RUN chmod 700 "/home/${USER_1}"


# Création de l'utilisateur user2
# -------------------------------
RUN useradd -m --badname -U -d /home/${USER_2} -s /usr/bin/zsh ${USER_2}
RUN echo "${USER_2}:${PASSWORD_2}" | chpasswd
# Création du fichier .zshrc
RUN echo "# Empty .zshrc" > /home/${USER_2}/.zshrc
# Droits du répertoire home
RUN chmod 700 "/home/${USER_2}"

# Création de l'utilisateur user3
# -------------------------------
RUN useradd -m --badname -U -d /home/${USER_3} -s /usr/bin/zsh ${USER_3}
RUN echo "${USER_3}:${PASSWORD_3}" | chpasswd
# Création du fichier .zshrc
RUN echo "# Empty .zshrc" > /home/${USER_3}/.zshrc
# Droits du répertoire home
RUN chmod 700 "/home/${USER_3}"


# Clés publiques
# -------------------------------
COPY ssh_public_keys/id_rsa_prof.pub /root/.ssh/authorized_keys

# Création des fichiers de FLAGS
# -------------------------------
RUN echo "${FENELON_1}" > /home/${USER_1}/fenelon.txt
RUN echo "${FENELON_2}" > /home/${USER_2}/fenelon.txt
RUN echo "${FENELON_3}" > /home/${USER_3}/fenelon.txt
#RUN echo "${FENELON_4}" > "/home/${USER_4}/fenelon.txt"


# Configuration du serveur SSH pour activer les logs
# --------------------------------------------------
RUN sed -i -e 's/^[#[:space:]]*SyslogFacility[[:space:]]\+AUTH/SyslogFacility AUTH/' \
           -e 's/^[#[:space:]]*LogLevel[[:space:]]\+INFO/LogLevel INFO/' \
           /etc/ssh/sshd_config


# Ensure the logger is running (rsyslog) and sshd starts in the foreground
RUN mkdir -p /var/run/sshd && \
    systemctl enable rsyslog.service && \
    systemctl enable ssh.service


# Port 22 (qui sera différent via docker-compose)
EXPOSE 22

CMD ["/usr/sbin/sshd","-D"]