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
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class UsersController extends Controller
{
    //Signup
    public function signup()
    {
        if (Auth::check()) {
            return redirect(route('myDay'));
        }
        $listTitle = "Signup";
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.signup', compact('themes','currentTheme', 'listTitle'));
    }

    public function signupPost(request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'verifyPassword' => 'required|same:password'
        ]);


        if ($validator->fails()) {
            if ($request->profile) {
                $tempFile = TempFile::where('folder', $request->profile)->first();
                Storage::disk('local')->deleteDirectory('profiles/tmp/' . $request->profile);
                $tempFile->delete();
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $newUser = new User();
        $newUser -> name = $request->name;
        $newUser -> email = $request->email;
        $newUser -> password = Hash::make($request->password);
        $newUser->save();

        if ($request->profile) {
            $tempFile = TempFile::where('folder', $request->profile)->first();
            $newUser->addMedia(storage_path('app/profiles/tmp/' . $request->profile . '/' . $tempFile->fileName))
                ->toMediaCollection('profile');
            $profile = $newUser->getFirstMedia('profile');
            $newUser ->profile = 'storage/'.$profile->id.'/'.$profile->file_name;
            $newUser->save();

            Storage::disk('local')->deleteDirectory('profiles/tmp/' . $request->profile);
            $tempFile->delete();
        }

        auth()->login($newUser);
        $newList = new TodoList();
        $newList -> title = 'Tasks';
        $newList -> user_id = $newUser->id;
        $newList -> save();


        return redirect(route('myDay'));

    }

    //Login
    public function login()
    {
        if (Auth::check()) {
            return redirect(route('myDay'));
        }
        $listTitle = "Login";
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.login', compact('themes','currentTheme', 'listTitle'));
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
        $listTitle = "Forgot Password";
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.forgotPassword', compact('themes','currentTheme', 'listTitle'));
    }

    public function forgotPost(request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if (Auth::check()) {
            $url = route('deleteAccount');
        } else {
            $url = route('login');
        }

        return $status === Password::RESET_LINK_SENT
            ? redirect($url)
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword($token)
    {
        $listTitle = "Reset Password";
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.resetPassword', compact('themes','currentTheme', 'token', 'listTitle'));
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

    //Edi Info

    public function editInfo(request $request)
    {
        $user = Auth::user();

        if($request->name) {
            $user -> name = $request->name;
        }

        if($request->profile) {
            $tempFile = TempFile::where('folder', $request->profile)->first();

            $user->clearMediaCollection('profile');

            $user->addMedia(storage_path('app/profiles/tmp/' . $request->profile . '/' . $tempFile->fileName))->toMediaCollection('profile');
            Storage::disk('local')->deleteDirectory('profiles/tmp/' . $request->profile);

            $profile = $user->getFirstMedia('profile');
            $user->profile = 'storage/'.$profile->id.'/'.$profile->file_name;

            $tempFile->delete();
        }

        $user->save();

        return redirect()->back();
    }

    //Delete Account
    public function deleteAccount()
    {
        $listTitle = "Forgot Password";
        $themes = Theme::all();
        $currentTheme = Theme::where('id', rand(1,7))->first();
        return view('auth.deleteAccount', compact('themes','currentTheme', 'listTitle'));
    }

    public function deleteAccountPost(request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6'
        ]);
        $user = Auth::user();

        if (password_verify($request->password, $user->password)) {
            $user->delete();
            return redirect(route('signup'));
        } else {
            $validator->getMessageBag()->add('password', 'Wrong password');
        }

        return redirect()->back()->withErrors($validator)->withInput();
    }
}
