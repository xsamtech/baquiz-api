<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Clash;
use App\Models\Field;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File as FileRule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function show(): View
    {
        return view('admin', [
            'canRegister' => ! User::query()->exists(),
            'path' => request()->path(),
            'user' => request()->user(),
            'userData' => request()->user()?->only(['id', 'uuid', 'firstname', 'lastname', 'email', 'avatar_url']),
        ]);
    }

    public function dashboard(): JsonResponse
    {
        return response()->json([
            'statistics' => [
                'members' => $this->usersInRole('Membre'),
                'medalled_members' => DB::table('medal_user')->distinct('user_id')->count('user_id'),
                'clashs' => Clash::query()->count(),
                'quiz_masters' => $this->usersInRole('Quiz master'),
                'partners' => $this->usersInRole('Partenaire'),
            ],
            'payments' => [
                'pending' => Payment::query()->where('status', 2)->count(),
                'successful' => Payment::query()->where('status', 0)->count(),
                'failed' => Payment::query()->where('status', 1)->count(),
                'latest' => Payment::query()->with('user')->latest('id')->paginate(10),
            ],
        ]);
    }

    public function notifications(Request $request): JsonResponse
    {
        $query = Notification::query()
            ->with(['fromUser', 'toUser', 'clash'])
            ->where('to_user_id', $request->user()->id)
            ->latest('id');

        if (! $request->boolean('all')) {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(10);

        return NotificationResource::collection($notifications)->response();
    }

    public function account(Request $request): JsonResponse
    {
        $user = $request->user()->load([
            'roles' => fn ($query) => $query->select(['roles.id', 'role_name']),
            'websites:id,website_name,website_url,logo_icon,user_id',
        ]);
        $selectedRole = $user->roles->firstWhere('pivot.is_selected', true) ?? $user->roles->first();

        return response()->json([
            'user' => $user->only(['id', 'uuid', 'firstname', 'lastname', 'surname', 'email', 'username', 'phone', 'avatar_url', 'cover_url', 'about', 'city', 'country']),
            'role' => $selectedRole?->getTranslations('role_name'),
            'websites' => $user->websites->map(fn ($website) => [
                'id' => $website->id,
                'website_name' => $website->website_name,
                'website_url' => $website->website_url,
                'logo_icon' => filled($website->logo_icon) ? 'bi bi-'.$website->logo_icon : 'bi bi-globe2',
            ]),
        ]);
    }

    public function updateAccount(Request $request): JsonResponse
    {
        $data = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'surname' => ['sometimes', 'nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$request->user()->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
            'phone' => ['sometimes', 'nullable', 'string', 'max:45', 'unique:users,phone,'.$request->user()->id],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'about' => ['sometimes', 'nullable', 'string', 'max:5000'],
        ]);

        $request->user()->update($data);

        return $this->account($request);
    }

    public function updateWebsites(Request $request): JsonResponse
    {
        $data = $request->validate([
            'websites' => ['required', 'array'],
            'websites.*.id' => ['sometimes', 'integer'],
            'websites.*.website_name' => ['required', 'string', 'max:255'],
            'websites.*.website_url' => ['required', 'url', 'max:2048'],
            'websites.*.logo_icon' => ['sometimes', 'nullable', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/'],
        ]);
        $user = $request->user();
        $websiteIds = collect($data['websites'])->pluck('id')->filter()->all();

        DB::transaction(function () use ($data, $user, $websiteIds): void {
            $user->websites()->whereNotIn('id', $websiteIds)->delete();

            foreach ($data['websites'] as $website) {
                $id = $website['id'] ?? null;
                unset($website['id']);
                $user->websites()->updateOrCreate(['id' => $id], $website);
            }
        });

        return $this->account($request);
    }

    public function updateAccountPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'former_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->forceFill(['password' => Hash::make($data['password'])])->save();

        return response()->json(['message' => 'Password updated.']);
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $data = $request->validate([
            'avatar' => ['required', FileRule::image()->max('5mb')],
        ]);

        $path = $data['avatar']->store('baquiz/avatars', 's3');
        $request->user()->update(['avatar_url' => Storage::disk('s3')->url($path)]);

        return $this->account($request);
    }

    public function destroyAccount(Request $request): JsonResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);
        $user = $request->user();

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['redirect' => route('login')]);
    }

    public function search(Request $request): JsonResponse
    {
        $data = $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);
        $term = '%'.$data['q'].'%';

        return response()->json([
            'users' => User::query()->select(['id', 'uuid', 'firstname', 'lastname', 'username', 'avatar_url'])->where(fn ($query) => $query->where('firstname', 'like', $term)->orWhere('lastname', 'like', $term)->orWhere('username', 'like', $term))->limit(5)->get(),
            'clashs' => Clash::query()->select(['id', 'uuid', 'clash_code', 'clash_description'])->where(fn ($query) => $query->where('clash_code', 'like', $term)->orWhere('clash_description', 'like', $term))->limit(5)->get(),
            'fields' => Field::query()->select(['id', 'uuid', 'field_name'])->where('field_name->fr', 'like', $term)->limit(5)->get(),
        ]);
    }

    private function usersInRole(string $role): int
    {
        return User::query()
            ->whereHas('roles', fn ($query) => $query->whereJsonContainsLocale('role_name', 'fr', $role))
            ->count();
    }
}
