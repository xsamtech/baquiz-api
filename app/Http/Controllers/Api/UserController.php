<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FileResource;
use App\Http\Resources\PasswordResetResource;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\Notification;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

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
        ]);

        unset($data['confirm_password']);
        $data['api_key'] = Str::random(80);

        [$user, $passwordResets] = DB::transaction(function () use ($data): array {
            $user = User::query()->create($data);
            $passwordResets = $this->createPasswordResets($user);
            Notification::query()->create([
                'type' => 'welcome_new_user',
                'to_user_id' => $user->id,
            ]);

            return [$user, $passwordResets];
        });

        return $this->handleResponse(
            [
                'users' => new UserResource($user),
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

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('email', $data['username'])
            ->orWhere('phone', $data['username'])
            ->orWhere('username', $data['username'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return $this->handleError(null, Lang::get('api.users.invalid_credentials'), Response::HTTP_UNAUTHORIZED);
        }

        $passwordResets = $this->missingVerificationResets($user);

        if ($passwordResets->isNotEmpty()) {
            return $this->handleError(
                ['password_resets' => PasswordResetResource::collection($passwordResets)],
                Lang::get('api.users.not_verified'),
                Response::HTTP_FORBIDDEN
            );
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
            'file_name' => ['sometimes', 'nullable', 'string'],
            'file_url' => ['required', 'string'],
            'file_description' => ['sometimes', 'nullable', 'string'],
            'file_type' => ['sometimes', 'nullable', 'in:photo,id_card'],
        ]);

        $file = File::query()->create([
            ...$data,
            'file_type' => $data['file_type'] ?? 'photo',
            'user_id' => $user->id,
        ]);

        $user->update(['avatar_url' => $file->file_url]);

        return $this->handleResponse(
            [
                'users' => new UserResource($user->refresh()),
                'files' => new FileResource($file),
            ],
            Lang::get('api.users.avatar_changed')
        );
    }

    /**
     * @return Collection<int, PasswordReset>
     */
    private function createPasswordResets(User $user)
    {
        return collect(['email', 'phone'])
            ->filter(fn (string $column): bool => filled($user->{$column}))
            ->map(fn (string $column): PasswordReset => PasswordReset::query()->create([
                $column => $user->{$column},
                'token' => Str::upper(Str::random(6)),
                'former_password' => null,
            ]))
            ->values();
    }

    /**
     * @return Collection<int, PasswordReset>
     */
    private function missingVerificationResets(User $user)
    {
        $needsEmailVerification = filled($user->email) && blank($user->email_verified_at);
        $needsPhoneVerification = filled($user->phone) && blank($user->phone_verfied_at);

        return collect([
            'email' => $needsEmailVerification,
            'phone' => $needsPhoneVerification,
        ])
            ->filter()
            ->map(fn (bool $unused, string $column): PasswordReset => PasswordReset::query()->create([
                $column => $user->{$column},
                'token' => Str::upper(Str::random(6)),
                'former_password' => null,
            ]))
            ->values();
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
