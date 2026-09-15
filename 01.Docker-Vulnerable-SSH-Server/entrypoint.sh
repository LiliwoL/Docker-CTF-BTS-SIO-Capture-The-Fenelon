#!/bin/sh

/usr/sbin/rsyslogd
/usr/sbin/sshd -D

exec "$@"