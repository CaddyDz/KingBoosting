<?php

namespace Tests;

use App\User;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Set the currently logged in user for the application.
     *
     * @param Illuminate\Contracts\Auth\Authenticatable
     *
     * @return App\User authenticated
     */
    protected function login(string $role, ?User $user = null): TestCase
    {
        $role = Role::create(['name' => $role]);
        $role->givePermissionTo(Permission::all());
        $user = $user ?: create(User::class);
        $user->assignRole($role);
        Passport::actingAs($user);
        return $this;
    }

    /**
     * Get log information
     *
     * Get today's log file and convert its content to array
     *
     * @return array $log
     **/
    protected function getLogInfo()
    {
        $log = explode(PHP_EOL, file_get_contents($this->getLogFileFullPath()));
        return $log;
    }

    /**
     * Get log file full path
     *
     * resolve the log file system path in storage
     *
     * @return string $logFilePullPath the path to the log file
     **/
    private function getLogFileFullPath()
    {
        $date = now()->format('Y-m-d');
        $logfileFullpath = storage_path("logs/laravel-{$date}.log");
        return $logfileFullpath;
    }

    protected function resetAuth(array $guards = null)
    {
        $guards = $guards ?: array_keys(config('auth.guards'));

        foreach ($guards as $guard) {
            $guard = $this->app['auth']->guard($guard);

            if ($guard instanceof \Illuminate\Auth\SessionGuard) {
                $guard->logout();
            }
        }

        $protectedProperty = new \ReflectionProperty($this->app['auth'], 'guards');
        $protectedProperty->setAccessible(true);
        $protectedProperty->setValue($this->app['auth'], []);
    }

    /**
     * Get index of logged message
     *
     * Get log line index number of the message
     *
     * @param string $title the logged message title
     * @param array $log log content
     * @return int line index number
     **/
    protected function getIndexOfAloggedMessage($title, array $log)
    {
        // Remove the last line CRLF
        for ($i = count($log) - 1; $i >= 0; $i--) { // Start at the end of the array 12
            // reduce line each time and stop when reach 0
            if (strpos($log[$i], $title) !== false) { // Strict checking for line index 0 is false
                return $i;
            }
        }
        $this->fail('No information found in the log file ' . $this->getLogFileFullPath());
    }
}
