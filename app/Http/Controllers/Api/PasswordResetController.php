<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PasswordResetResource;
use App\Http\Resources\UserResource;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class PasswordResetController extends ApiController
{
    protected string $modelClass = PasswordReset::class;

    protected string $resourceClass = PasswordResetResource::class;

    protected string $messageKey = 'password_resets';

    protected array $relationships = [];

    protected array $storeRules = [
        'email' => ['sometimes', 'nullable', 'email'],
        'phone' => ['sometimes', 'nullable', 'string'],
        'token' => ['sometimes', 'nullable', 'string'],
        'former_password' => ['sometimes', 'nullable', 'string'],
    ];

    protected array $updateRules = [
        'email' => ['sometimes', 'nullable', 'email'],
        'phone' => ['sometimes', 'nullable', 'string'],
        'token' => ['sometimes', 'nullable', 'string'],
        'former_password' => ['sometimes', 'nullable', 'string'],
    ];

    public function findUser(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['sometimes', 'nullable', 'email'],
            'phone' => ['sometimes', 'nullable', 'string'],
        ]);

        if (blank($data['email'] ?? null) && blank($data['phone'] ?? null)) {
            return $this->handleError(null, Lang::get('api.password_resets.email_or_phone_required'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::query()
            ->when($data['email'] ?? null, fn ($query, string $email) => $query->where('email', $email))
            ->when($data['phone'] ?? null, fn ($query, string $phone) => $query->orWhere('phone', $phone))
            ->first();

        if (! $user) {
            return $this->handleError(null, Lang::get('api.users.not_found'), Response::HTTP_NOT_FOUND);
        }

        $passwordReset = PasswordReset::query()->create([
            'email' => $user->email,
            'phone' => $user->phone,
            'token' => Str::upper(Str::random(6)),
        ]);

        return $this->handleResponse(
            [
                'users' => new UserResource($user),
                'password_resets' => new PasswordResetResource($passwordReset),
            ],
            Lang::get('api.password_resets.user_found')
        );
    }

    public function checkToken(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['sometimes', 'nullable', 'email'],
            'phone' => ['sometimes', 'nullable', 'string'],
        ]);

        $passwordReset = PasswordReset::query()
            ->where('token', $data['token'])
            ->when($data['email'] ?? null, fn ($query, string $email) => $query->where('email', $email))
            ->when($data['phone'] ?? null, fn ($query, string $phone) => $query->where('phone', $phone))
            ->latest('id')
            ->first();

        if (! $passwordReset) {
            return $this->handleError(null, Lang::get('api.password_resets.invalid_token'), Response::HTTP_NOT_FOUND);
        }

        return $this->handleResponse(
            new PasswordResetResource($passwordReset),
            Lang::get('api.password_resets.valid_token')
        );
    }
}
