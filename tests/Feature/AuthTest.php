<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Client;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;

class AuthTest extends TestCase
{

    // call artisan command in the setup method
    // This will ensure the passport client are generated for testing purpose.
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /**
     * Assert guests can register for an account
     *
     * Make sure the user has actually been created and stored in database.
     *
     * @return void
     */
    public function test_registration()
    {
        $user = create(User::class, [], 'make')->toArray(); // Make an in memory user data array
        $credentials = ['password' => 'password', 'password_confirmation' => 'password'];
        $response = $this->post('/api/register', array_merge($user, $credentials));
        $response = (object) json_decode($response->getContent(), true);
        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
            'username' => $response->success['username']
        ]);
    }

    /**
     * Assert registered users can verify their email
     *
     * @return void
     */
    public function test_email_verification()
    {
        $user = create(User::class, [], 'make')->toArray(); // Make an in memory user data array
        // Remove verification timestamp to force verification email
        $user = array_except($user, 'email_verified_at');
        $credentials = ['password' => 'password', 'password_confirmation' => 'password'];
        $response = $this->post('/api/register', array_merge($user, $credentials));
        $response = sanitize($response);
        $log = $this->getLogInfo();
        // log is the entire file, we need to get the verification url
        $this->assertVerificationEmailIsLogged($log, $user['email']);
        Event::fake(); // No log if defined earlier
        // We need the token here
        $token = $response->success['token'];
        $response = $this->get($this->getVerificationUrl($user['email'], $log), [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertOk();
        $response->assertExactJson([
            'status' => 'Verified'
        ]);
        Event::assertDispatched(Verified::class);
    }

    /**
     * Assert only authenticated user can verify their email
     *
     * @return void
     */
    public function test_user_validation_on_email_verification()
    {
        $user = create(User::class, [], 'make')->toArray(); // Make an in memory user data array
        $secondUser = create(User::class, [], 'make')->toArray(); // Make an in memory user data array
        // Remove verification timestamp to force verification email
        $user = array_except($user, 'email_verified_at');
        $secondUser = array_except($secondUser, 'email_verified_at');
        $credentials = ['password' => 'password', 'password_confirmation' => 'password'];
        $secondCredentials = ['password' => 'password', 'password_confirmation' => 'password'];
        $token = $this->post('/api/register', array_merge($user, $credentials));
        $secondToken = $this->post('/api/register', array_merge($secondUser, $secondCredentials));
        // Get login token
        $token = $token->getData()->success->token;
        $secondToken = $secondToken->getData()->success->token;
        $log = $this->getLogInfo();
        $this->assertVerificationEmailIsLogged($log, $user['email']);
        $this->assertVerificationEmailIsLogged($log, $secondUser['email'], 2);
        Event::fake(); // No log if defined earlier
        $response = $this->get($this->getVerificationUrl($user['email'], $log), [
            'Authorization' => 'Bearer ' . $secondToken
        ]);
        $response->assertForbidden();
        $response->assertExactJson([
            'status' => 'Forbidden'
        ]);
        Event::assertNotDispatched(Verified::class);
    }

    /**
     * Assert already verified users can't verify their email
     *
     * @return void
     */
    public function test_already_verified_validation()
    {
        $user = create(User::class, [], 'make')->toArray(); // Make an in memory user data array
        // Remove verification timestamp to force verification email
        $user = array_except($user, 'email_verified_at');
        $credentials = ['password' => 'password', 'password_confirmation' => 'password'];
        $token = $this->post('/api/register', array_merge($user, $credentials));
        // Get login token
        $token = $token->getData()->success->token;
        $log = $this->getLogInfo();
        $this->get($this->getVerificationUrl($user['email'], $log), [
            'Authorization' => 'Bearer ' . $token
        ]);
        Event::fake(); // No log if defined earlier
        $response = $this->get($this->getVerificationUrl($user['email'], $log), [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertUnauthorized();
        $response->assertExactJson([
            'status' => 'Already verified'
        ]);
        Event::assertNotDispatched(Verified::class);
    }

    /**
     * Assert guests can't register for an account with no password
     *
     * @return void
     */
    public function test_validation_upon_registration()
    {
        $this->withExceptionHandling();
        $user = create(User::class, [], 'make')->toArray(); // Make an in memory user data array
        $user = array_except($user, 'email');
        $response = $this->post('/api/register', $user);
        $response->assertJsonStructure([
            'errors' => ['email']
        ]);
        $response->assertStatus(422);
    }

    /**
     * Assert guests can login to their account
     *
     * @return void
     */
    public function test_login()
    {
        $client_secret = Client::find(2)->secret;
        config(['passport.client_secret' => $client_secret]);
        $user = create(User::class);
        $credentials = ['email' => $user->email, 'password' => 'password'];
        $response = $this->post('/api/login', $credentials);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success' => ['token_type', 'expires_in', 'access_token', 'refresh_token']]);
        $this->assertNotNull($response->getData());
        $this->assertNotEmpty($response->getData());
        $data = $response->getData()->success;
        $this->assertEquals('Bearer', $data->token_type);
        $this->assertEquals(
            now()->addYears(1)->toDateString(),
            now()->addSeconds($data->expires_in)->toDateString()
        );
        $this->assertNotEmpty($data->access_token);
        $this->assertNotEmpty($data->refresh_token);
    }

    public function test_logout()
    {
        $this->withExceptionHandling();
        $data = $this->getLoginResponse()->getData()->success;
        $response = $this->json('POST', '/api/logout', [], [
            'Authorization' => $data->token_type . ' ' . $data->access_token
        ]);
        $response->assertOk();

        // Directly assert the api user's token was revoked.
        $this->assertTrue($this->app['auth']->guard('api')->user()->token()->revoked);

        $this->resetAuth();

        // Assert using the revoked token for the next request won't work.
        $response = $this->json('GET', '/api/user', [], [
            'Authorization' => $data->token_type . ' ' . $data->access_token
        ]);
        $response->assertUnauthorized();
    }

    /**
     * Assert guests can't login to their account with no password
     *
     * @return void
     */
    public function test_validation_upon_login()
    {
        $client_secret = Client::find(2)->secret;
        config(['passport.client_secret' => $client_secret]);
        $user = create(User::class);
        $credentials = ['email' => $user->email];
        $response = $this->post('/api/login', $credentials);
        $response->assertStatus(401);
    }

    /**
     * Assert guests can't bruteforce login.
     *
     * @return void
     */
    public function test_throttle_upon_login()
    {
        $this->withExceptionHandling();
        Event::fake();
        $client_secret = Client::find(2)->secret;
        config(['passport.client_secret' => $client_secret]);
        $user = create(User::class);
        $credentials = ['email' => $user->email, 'password' => 'wrong_password'];
        for ($i = 0; $i < 6; $i++) {
            $this->post('/api/login', $credentials);
        }
        Event::assertDispatched(Lockout::class);
    }

    /**
     * Test verification email is logged
     *
     * assert that registered users receive a url to verify email
     *
     * @param array $log log content
     * @param string $email The receiving registered user email
     **/
    public function assertVerificationEmailIsLogged($log, $email, $id = 1)
    {
        $indexOfLoggedEmail = $this->getIndexOfAloggedMessage($email, $log);
        $this->assertStringContainsStringIgnoringCase("To: $email", $log[$indexOfLoggedEmail], "No emails were found.\r");
        // This is failing
        $this->assertStringContainsStringIgnoringCase(
            // 1 => user ID
            config('app.url') . "/api/email/verify/$id/",
            // We must find that line
            $log[$indexOfLoggedEmail + 60], // Jump by 60 lines to find link
            "No links were found.\n"
        );
    }

    /**
     * Get verification link
     *
     * Extract application relative URI to verify email
     *
     * @param string $email User's email to extract verification url for
     * @param array $log log content
     * @return string the relative url to verify email
     **/
    public function getVerificationUrl(string $email = null, $log)
    {
        $indexOfLoggedLink = $this->getIndexOfAloggedMessage($email, $log);
        $indexOfLoggedLink = $indexOfLoggedLink + 115;
        $fullLink = $log[$indexOfLoggedLink];
        // Remove trailing space from link
        $fullLink = rtrim($fullLink);
        // Extract as a relative URL
        $fullLink = str_replace_first('Verify Email Address: ' . config('app.url'), '', $fullLink);
        return $fullLink;
    }

    /**
     * Login with user
     *
     * Log the given user into the application
     *
     * @param User $user Optional user to login with
     * @return \Illuminate\Http\Response $response The auth response object
     **/
    public function getLoginResponse(User $user = null)
    {
        $client_secret = Client::find(2)->secret;
        config(['passport.client_secret' => $client_secret]);
        $user = create(User::class);
        $credentials = ['email' => $user->email, 'password' => 'password'];
        $response = $this->post('/api/login', $credentials);
        return $response;
    }
}
