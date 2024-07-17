<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DevPostman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:postman {guard} {user?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating access for Postman.';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): int
    {
        if (!App::environment('local')) {
            $this->warn(trans('Generating access for Postman.'));
            return 1;
        }

        $user = $this->argument('user')
            ? Customer::findOrFail($this->argument('user'))
            : Customer::firstOrFail();

        switch ($this->argument('guard')) {
            case 'api':
                $user->tokens()->delete();

                $this->info(
                    $user->createToken('test')->plainTextToken
                );
                return 0;
            case 'web':
            default:
                Route::get('/dev-login', fn() => auth::login($user))
                    ->middleware('web');

                $cookies = app(HttpKernel::class)
                    ->handle(Request::create('/dev-login'))
                    ->headers
                    ->getCookies(ResponseHeaderBag::COOKIES_ARRAY);

                $session_name = sprintf(
                    '%s_session',
                    strtolower(config('app.name'))
                );

                $session = $cookies[".localhost"]["/"][$session_name];
                $xsrf_token = $cookies[".localhost"]["/"]['XSRF-TOKEN'];

                $this->info(
                    sprintf('pm.request.addHeader({key: "Cookie", value: "%s=\'%s\'"});',
                        $session_name,
                        $session->getValue()
                    )
                );
                $this->info(
                    sprintf(
                        'pm.request.addHeader({key: "X-XSRF-TOKEN", value: "\'%s\'"});',
                        $xsrf_token->getValue()
                    )
                );
                return 0;
        }
    }
}
