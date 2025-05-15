<?php
$basePath = realpath(__DIR__);
chdir($basePath);
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan key:generate');
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan storage:link');
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan migrate');
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan db:seed --class=AdminSeeder');
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan db:seed --class=ConfigSeeder');
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan config:clear');
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan route:clear');
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan view:clear');
echo shell_exec('/opt/plesk/php/8.2/bin/php artisan cache:clear');
