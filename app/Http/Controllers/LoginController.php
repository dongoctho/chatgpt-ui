<?php

namespace App\Http\Controllers;

use App\Services\LoginGoogleService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Log;


class LoginController extends Controller
{
    protected $loginGoogleService;

    /**
     *
     * @param LoginGoogleService $loginGoogleService
     * @return void
     */
    public function __construct(LoginGoogleService $loginGoogleService)
    {
        $this->loginGoogleService = $loginGoogleService;
    }

    /**
     * Redirect to google login
     *
     * @return
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle google callback
     *
     * @return
     */
    public function handleGoogleCallback()
    {
        try {
            return $this->loginGoogleService->googleCallback();
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * Get information of user
     *
     * @return JsonResponse
     *
     */
    public function profile()
    {
        try {
            $user = auth()->user();

            return response()->json($user, Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([$e->getMessage()]);
        }
    }

    /**
     * Logout
     *
     * @return JsonResponse
     */
    public function logout()
    {
        try {
            Auth::logout();
            Session::flush();

            return redirect()->route('login');
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([$e->getMessage()]);
        }
    }
}
