<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FileResource;
use App\Http\Resources\PasswordResetResource;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\Notification;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File as FileRule;

class UserController extends ApiController
{
    protected string $modelClass = User::class;

    protected string $resourceClass = UserResource::class;

    protected string $messageKey = 'users';

    protected array $relationships = [];

    protected array $storeRules = [
        'firstname' => ['sometimes', 'nullable', 'string'],
        'lastname' => ['sometimes', 'nullable', 'string'],
        'surname' => ['sometimes', 'nullable', 'string'],
        'organization_name' => ['sometimes', 'nullable', 'string'],
        'gender' => ['sometimes', 'nullable', 'string'],
        'birthdate' => ['sometimes', 'nullable', 'date'],
        'country' => ['sometimes', 'nullable', 'string'],
        'city' => ['sometimes', 'nullable', 'string'],
        'address_1' => ['sometimes', 'nullable', 'string'],
        'address_2' => ['sometimes', 'nullable', 'string'],
        'p_o_box' => ['sometimes', 'nullable', 'string'],
        'currency' => ['sometimes', 'nullable', 'string'],
        'email' => ['sometimes', 'nullable', 'email'],
        'phone' => ['sometimes', 'nullable', 'string'],
        'username' => ['sometimes', 'nullable', 'string'],
        'password' => ['sometimes', 'nullable', 'string', 'min:8'],
        'api_token' => ['sometimes', 'nullable', 'string'],
        'api_key' => ['sometimes', 'nullable', 'string'],
        'avatar_url' => ['sometimes', 'nullable', 'string'],
        'cover_url' => ['sometimes', 'nullable', 'string'],
        'belongs_to' => ['sometimes', 'nullable', 'integer'],
        'promo_code' => ['sometimes', 'nullable', 'string'],
        'two_factor_secret' => ['sometimes', 'nullable', 'string'],
        'two_factor_recovery_codes' => ['sometimes', 'nullable', 'string'],
        'two_factor_email_confirmed_at' => ['sometimes', 'nullable', 'date'],
        'two_factor_phone_confirmed_at' => ['sometimes', 'nullable', 'date'],
        'tips_at_every_login' => ['sometimes', 'nullable', 'boolean'],
        'is_online' => ['sometimes', 'nullable', 'boolean'],
        'status' => ['sometimes', 'nullable', 'in:created,activated,disabled,blocked,deleted'],
        'type' => ['sometimes', 'nullable', 'in:uncertified,certified'],
    ];

    protected array $updateRules = [
        'firstname' => ['sometimes', 'nullable', 'string'],
        'lastname' => ['sometimes', 'nullable', 'string'],
        'surname' => ['sometimes', 'nullable', 'string'],
        'organization_name' => ['sometimes', 'nullable', 'string'],
        'gender' => ['sometimes', 'nullable', 'string'],
        'birthdate' => ['sometimes', 'nullable', 'date'],
        'country' => ['sometimes', 'nullable', 'string'],
        'city' => ['sometimes', 'nullable', 'string'],
        'address_1' => ['sometimes', 'nullable', 'string'],
        'address_2' => ['sometimes', 'nullable', 'string'],
        'p_o_box' => ['sometimes', 'nullable', 'string'],
        'currency' => ['sometimes', 'nullable', 'string'],
        'email' => ['sometimes', 'nullable', 'email'],
        'phone' => ['sometimes', 'nullable', 'string'],
        'username' => ['sometimes', 'nullable', 'string'],
        'password' => ['sometimes', 'nullable', 'string', 'min:8'],
        'api_token' => ['sometimes', 'nullable', 'string'],
        'api_key' => ['sometimes', 'nullable', 'string'],
        'avatar_url' => ['sometimes', 'nullable', 'string'],
        'cover_url' => ['sometimes', 'nullable', 'string'],
        'belongs_to' => ['sometimes', 'nullable', 'integer'],
        'promo_code' => ['sometimes', 'nullable', 'string'],
        'two_factor_secret' => ['sometimes', 'nullable', 'string'],
        'two_factor_recovery_codes' => ['sometimes', 'nullable', 'string'],
        'two_factor_email_confirmed_at' => ['sometimes', 'nullable', 'date'],
        'two_factor_phone_confirmed_at' => ['sometimes', 'nullable', 'date'],
        'tips_at_every_login' => ['sometimes', 'nullable', 'boolean'],
        'is_online' => ['sometimes', 'nullable', 'boolean'],
        'status' => ['sometimes', 'nullable', 'in:created,activated,disabled,blocked,deleted'],
        'type' => ['sometimes', 'nullable', 'in:uncertified,certified'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            ...$this->storeRules,
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
            'avatar' => ['sometimes', FileRule::image()->max('5mb')],
        ]);

        unset($data['confirm_password']);
        $data['api_key'] = Str::random(80);

        if (isset($data['avatar'])) {
            $data['avatar_url'] = $this->storeAvatar($data['avatar']);
            unset($data['avatar']);
        }

        [$user, $passwordResets] = DB::transaction(function () use ($data): array {
            $user = User::query()->create($data);
            $passwordResets = $this->createPasswordResets($user);
            $user->roles()->syncWithoutDetaching([
                $this->memberRole()->id => ['is_selected' => true],
            ]);
            Notification::query()->create([
                'type' => 'welcome_new_user',
                'to_user_id' => $user->id,
            ]);

            return [$user, $passwordResets];
        });

        return $this->handleResponse(
            [
                'users' => new UserResource($user->load('roles')),
                'password_resets' => PasswordResetResource::collection($passwordResets),
            ],
            Lang::get('api.users.store')
        );
    }

    public function findByUsername(string $username): JsonResponse
    {
        $user = User::query()
            ->where('username', $username)
            ->first();

        if (! $user) {
            return $this->handleError(null, Lang::get('api.users.not_found'), Response::HTTP_NOT_FOUND);
        }

        return $this->handleResponse(
            new UserResource($user),
            Lang::get('api.users.show')
        );
    }

    public function usersWithHistoriesByPeriod(Request $request): JsonResponse
    {
        $data = $request->validate([
            'period' => ['required', 'in:daily,weekly,monthly,quarterly,semi-annually,annually'],
        ]);
        [$startsAt, $endsAt] = $this->periodRange($data['period']);

        $users = User::query()
            ->with(['histories' => fn (HasMany $query): HasMany => $query->whereBetween('created_at', [$startsAt, $endsAt])])
            ->whereHas('histories', fn (Builder $query): Builder => $query->whereBetween('created_at', [$startsAt, $endsAt]))
            ->latest('id')
            ->paginate(20);

        return $this->handleResponse(
            UserResource::collection($users),
            Lang::get('api.users.with_histories'),
            $users->lastPage(),
            $users->total()
        );
    }

    public function usersWithMedals(): JsonResponse
    {
        $users = User::query()
            ->with('medals')
            ->whereHas('medals')
            ->latest('id')
            ->paginate(20);

        return $this->handleResponse(
            UserResource::collection($users),
            Lang::get('api.users.with_medals'),
            $users->lastPage(),
            $users->total()
        );
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        [$user, $credentialType] = $this->findUserForLogin($data['username']);

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return $this->handleError(null, Lang::get('api.users.invalid_credentials'), Response::HTTP_UNAUTHORIZED);
        }

        $verificationMessage = $this->missingVerificationMessage($user, $credentialType);

        if ($verificationMessage) {
            return $this->handleError(null, Lang::get($verificationMessage), Response::HTTP_FORBIDDEN);
        }

        $user->forceFill(['api_key' => Str::random(80)])->save();

        return $this->handleResponse(
            [
                'users' => new UserResource($user->refresh()),
                'password_resets' => [],
            ],
            Lang::get('api.users.login')
        );
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        return $this->updateSingleAttribute($request, $id, 'status', ['required', 'in:created,activated,disabled,blocked,deleted'], 'status_changed');
    }

    public function changePassword(Request $request, int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return $this->handleError(null, Lang::get('api.users.not_found'), Response::HTTP_NOT_FOUND);
        }

        $data = $request->validate([
            'former_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:new_password'],
        ]);

        if (! Hash::check($data['former_password'], $user->password)) {
            return $this->handleError(null, Lang::get('api.users.invalid_former_password'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->update(['password' => $data['new_password']]);

        return $this->handleResponse(
            new UserResource($user->refresh()),
            Lang::get('api.users.password_changed')
        );
    }

    public function changeType(Request $request, int $id): JsonResponse
    {
        return $this->updateSingleAttribute($request, $id, 'type', ['required', 'in:uncertified,certified'], 'type_changed');
    }

    public function updateAvatar(Request $request, int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return $this->handleError(null, Lang::get('api.users.not_found'), Response::HTTP_NOT_FOUND);
        }

        $data = $request->validate([
            'avatar' => ['required', FileRule::image()->max('5mb')],
        ]);

        $user->update(['avatar_url' => $this->storeAvatar($data['avatar'])]);

        return $this->handleResponse(
            new UserResource($user->refresh()),
            Lang::get('api.users.avatar_changed')
        );
    }

    public function addFiles(Request $request, int $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return $this->handleError(null, Lang::get('api.users.not_found'), Response::HTTP_NOT_FOUND);
        }

        $data = $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*.file_name' => ['sometimes', 'nullable', 'string'],
            'files.*.file_url' => ['required', 'string'],
            'files.*.file_description' => ['sometimes', 'nullable', 'string'],
            'files.*.file_type' => ['sometimes', 'nullable', 'in:video,photo,audio,document,id_card,ad,qr_code'],
        ]);

        $files = collect($data['files'])
            ->map(fn (array $file): File => File::query()->create([
                ...$file,
                'file_type' => $file['file_type'] ?? 'photo',
                'user_id' => $user->id,
            ]));

        return $this->handleResponse(
            FileResource::collection($files),
            Lang::get('api.files.store')
        );
    }

    /**
     * @return Collection<int, PasswordReset>
     */
    private function createPasswordResets(User $user)
    {
        if (blank($user->email) && blank($user->phone)) {
            return collect();
        }

        return collect([
            PasswordReset::query()->create([
                'email' => $user->email,
                'phone' => $user->phone,
                'token' => Str::upper(Str::random(6)),
                'former_password' => null,
            ]),
        ]);
    }

    private function missingVerificationMessage(User $user, string $credentialType): ?string
    {
        if ($credentialType === 'email' && filled($user->email) && blank($user->email_verified_at)) {
            return 'api.users.not_verified_email';
        }

        if ($credentialType === 'phone' && filled($user->phone) && blank($user->phone_verfied_at)) {
            return 'api.users.not_verified_phone';
        }

        return null;
    }

    /**
     * @return array{0: ?User, 1: string}
     */
    private function findUserForLogin(string $username): array
    {
        $user = User::query()->where('email', $username)->first();

        if ($user) {
            return [$user, 'email'];
        }

        $user = User::query()->where('phone', $username)->first();

        if ($user) {
            return [$user, 'phone'];
        }

        return [
            User::query()->where('username', $username)->first(),
            'username',
        ];
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function periodRange(string $period): array
    {
        $now = now();

        return match ($period) {
            'daily' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'weekly' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'monthly' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'quarterly' => [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()],
            'semi-annually' => $now->month <= 6
                ? [$now->copy()->startOfYear(), $now->copy()->month(6)->endOfMonth()]
                : [$now->copy()->month(7)->startOfMonth(), $now->copy()->endOfYear()],
            'annually' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
        };
    }

    private function storeAvatar(UploadedFile $avatar): string
    {
        $path = $avatar->store('avatars', 'public');

        return Storage::disk('public')->url($path);
    }

    private function memberRole(): Role
    {
        $role = Role::query()
            ->whereJsonContainsLocale('role_name', 'fr', 'Membre')
            ->first();

        if ($role) {
            return $role;
        }

        return Role::query()->create([
            'role_name' => [
                'fr' => 'Membre',
                'en' => 'Member',
                'ln' => 'Membre',
            ],
            'role_description' => [
                'fr' => 'Personne ou organisation qui utilise les fonctionnalités de la plateforme.',
                'en' => 'Person or organization that uses the platform\'s features.',
                'ln' => 'Moto to pe ebongiseli oyo esalelaka makambo ya plateforme.',
            ],
        ]);
    }

    /**
     * @param  array<int, string>  $rules
     */
    private function updateSingleAttribute(Request $request, int $id, string $attribute, array $rules, string $message): JsonResponse
    {
        $user = User::query()->find($id);

        if (! $user) {
            return $this->handleError(null, Lang::get('api.users.not_found'), Response::HTTP_NOT_FOUND);
        }

        $data = $request->validate([$attribute => $rules]);
        $user->update([$attribute => $data[$attribute]]);

        return $this->handleResponse(
            new UserResource($user->refresh()),
            Lang::get("api.users.{$message}")
        );
    }
}
