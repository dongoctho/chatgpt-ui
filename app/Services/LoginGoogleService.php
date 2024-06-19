<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\DivisionRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Log;
use Str;

class LoginGoogleService
{
    protected $userRepository;
    protected $divisionRepository;
    protected $userService;

    /**
     * Define repository
     * @param UserRepository $userRepository
     * @param DivisionRepository $divisionRepository
     *
     */
    public function __construct(
        UserRepository $userRepository,
        DivisionRepository $divisionRepository,
        UserService $userService
    ) {
        $this->userRepository = $userRepository;
        $this->divisionRepository = $divisionRepository;
        $this->userService = $userService;
    }

    /**
     * Handle data from google callback
     *
     * @return Redirector|RedirectResponse
     */
    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $emailDomain = substr(strrchr($user->email, "@"), 1);
            if ($emailDomain !== 'vnext.vn') {
                session()->flash('error', 'The account does not belong to VNext.');

                return redirect()->route('login');
            }
            $existingUser = User::whereEmail($user->email)->first();
            $newUser = $existingUser ?: $this->createNewUser($user);
            Auth::login($newUser);

            return redirect()->route('home');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('home');
        }
    }

    private function createNewUser($googleUser)
    {
        $divisionName = $this->extractDivisionName($googleUser['family_name']);
        $division = $this->divisionRepository->getDivisionByName($divisionName);
        DB::beginTransaction();
        try {
            $newUser = $this->userService->createUser([
                'name' => $googleUser->user['given_name'],
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar,
                'google_id' => $googleUser->id,
                'division_id' => $division->id,
                'password' => Hash::make(Str::random(15)),
                'phone' => 012345,
                'role_id' => 1
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $newUser;
    }
    /**
     * Extract name from division
     *
     * @param mixed $familyName
     * @return string
     */
    private function extractDivisionName($familyName)
    {
        $parts = explode('.', $familyName);

        return rtrim(end($parts), ']');
    }
}
