<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\File as FileRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        abort_if(User::query()->exists(), 403);

        return view('admin', ['canRegister' => true, 'path' => 'register']);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        abort_if(User::query()->exists(), 403);

        $data = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:45', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['sometimes', FileRule::image()->max('5mb')],
        ]);

        $attributes = [
            'uuid' => (string) Str::uuid(),
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'username' => $data['username'],
            'phone' => $data['phone'],
            'password' => Hash::make($request->password),
        ];

        if (Schema::hasColumn('users', 'name')) {
            $attributes['name'] = trim("{$data['firstname']} {$data['lastname']} {$data['surname']}");
        }

        if (isset($data['avatar'])) {
            $attributes['avatar_url'] = Storage::disk('public')->url($data['avatar']->store('avatars', 'public'));
        }

        $user = User::create($attributes);

        $administratorRole = Role::query()->whereJsonContainsLocale('role_name', 'fr', 'Administrateur')->first()
            ?? Role::query()->create([
                'role_name' => ['fr' => 'Administrateur', 'en' => 'Administrator'],
                'role_description' => ['fr' => 'Gestion des données de fonctionnement de la plateforme.', 'en' => 'Management of the platform operating data.'],
            ]);

        $user->roles()->attach($administratorRole, ['is_selected' => true]);
        PasswordReset::query()->create(['email' => $user->email, 'phone' => $user->phone, 'token' => (string) random_int(100000, 999999), 'former_password' => null]);
        Notification::query()->create([
            'type' => 'welcome_new_user',
            'is_read' => false,
            'to_user_id' => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
