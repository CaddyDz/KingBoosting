cd /home/forge/dashboard.eloboost.app
git checkout -- . && git reset --hard HEAD && git clean -df
git pull origin develop
composer install --optimize-autoloader
php artisan migrate:fresh --seed --force
php artisan l5-swagger:generate
php artisan optimize

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock
