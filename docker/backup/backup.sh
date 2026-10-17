#!/bin/sh
# Dumps the database once a day at $BACKUP_AT (UTC) into /backups and deletes dumps older than
# $BACKUP_KEEP_DAYS days. Runs in the mysql image, which brings mysqldump; MYSQL_PWD holds the root password.
set -eu

while true; do
	now=$(date -u +%s)
	next=$(date -u -d "today $BACKUP_AT" +%s)
	if [ "$next" -le "$now" ]; then
		next=$(date -u -d "tomorrow $BACKUP_AT" +%s)
	fi
	echo "backup: next dump at $(date -u -d "@$next" '+%F %R') UTC"
	sleep $((next - now))

	file="/backups/$DB_DATABASE-$(date -u +%F).sql.gz"
	if mysqldump -h "$DB_HOST" -uroot --single-transaction --no-tablespaces "$DB_DATABASE" | gzip > "$file.partial"; then
		mv "$file.partial" "$file"
		echo "backup: wrote $file"
	else
		rm -f "$file.partial"
		echo "backup: mysqldump failed" >&2
	fi
	find /backups -name '*.sql.gz' -mtime +"$BACKUP_KEEP_DAYS" -delete
done
