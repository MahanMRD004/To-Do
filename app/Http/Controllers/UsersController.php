<?php

namespace App\Http\Controllers;
use App\Models\TempFile;
use App\Models\Theme;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class UsersController extends Controller
{
    //Signup
    public function signup()
    {
        if (Auth::check()) {
            return redirect(route('myDay'));
        }
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.signup', compact('themes','currentTheme'));
    }

    public function signupPost(request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'verifyPassword' => 'required|same:password'
        ]);

        $newUser = new User();
        $newUser -> name = $request->name;
        $newUser -> email = $request->email;
        $newUser -> password = Hash::make($request->password);
        $newUser->save();
        auth()->login($newUser);

        if($request->profile) {
            $tempFile = TempFile::where('folder', $request->profile)->first();

            if ($validator->fails()) {
                Storage::disk('local')->deleteDirectory('profiles/tmp/' . $request->profile);
                $tempFile->delete();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $newUser->addMedia(storage_path('app/profiles/tmp/' . $request->profile . '/' . $tempFile->fileName))->toMediaCollection('profile');
            Storage::disk('local')->deleteDirectory('profiles/tmp/' . $request->profile);

            $user = Auth::user();
            $user-> profile = 'storage/' . $user->id . '/' . $tempFile->fileName;
            $tempFile->delete();
            $user->save();
        }


        $newList = new TodoList();
        $newList -> title = 'Tasks';
        $newList -> user_id = Auth::user()->id;
        $newList -> save();


        return redirect(route('myDay'));

    }

    //Login
    public function login()
    {
        if (Auth::check()) {
            return redirect(route('myDay'));
        }
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.login', compact('themes','currentTheme'));
    }

    public function loginPost(request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ]);

        $remeber = $request->remember;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password ], $remeber)) {
            return redirect()->intended(route('myDay'));
        }

            return redirect()->back()->withInput($request->only('email'))->withErrors([
                'approve' => 'Wrong password',
            ]);
    }

    //Logout
    public function logout(request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }

    //Reset Password
    public function forgot()
    {
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.forgotPassword', compact('themes','currentTheme'));
    }

    public function forgotPost(request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword($token)
    {
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.resetPassword', compact('themes','currentTheme', 'token'));
    }

    public function resetPasswordPost(request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'verifyPassword' => 'required|same:password'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
