<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\HandlesSocialContent;
use App\Http\Resources\ClashResource;
use App\Models\Clash;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class ClashController extends ApiController
{
    use HandlesSocialContent;

    protected string $modelClass = Clash::class;

    protected string $resourceClass = ClashResource::class;

    protected string $messageKey = 'clashs';

    protected array $relationships = [
        'field',
        'hashtags',
        'user',
    ];

    protected array $storeRules = [
        'clash_code' => ['sometimes', 'nullable', 'string'],
        'clash_description' => ['sometimes', 'nullable', 'string'],
        'start_at' => ['sometimes', 'nullable', 'date'],
        'end_at' => ['sometimes', 'nullable', 'date'],
        'price' => ['sometimes', 'nullable', 'numeric'],
        'type' => ['sometimes', 'nullable', 'in:public,private'],
        'last_boost_at' => ['sometimes', 'nullable', 'date'],
        'boost_type' => ['sometimes', 'nullable', 'in:daily,weekly,monthly,yearly'],
        'field_id' => ['sometimes', 'nullable', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'clash_code' => ['sometimes', 'nullable', 'string'],
        'clash_description' => ['sometimes', 'nullable', 'string'],
        'start_at' => ['sometimes', 'nullable', 'date'],
        'end_at' => ['sometimes', 'nullable', 'date'],
        'price' => ['sometimes', 'nullable', 'numeric'],
        'type' => ['sometimes', 'nullable', 'in:public,private'],
        'last_boost_at' => ['sometimes', 'nullable', 'date'],
        'boost_type' => ['sometimes', 'nullable', 'in:daily,weekly,monthly,yearly'],
        'field_id' => ['sometimes', 'nullable', 'integer'],
        'user_id' => ['sometimes', 'nullable', 'integer'],
    ];

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->storeRules);

        if (($data['type'] ?? null) === 'private' && blank($data['clash_code'] ?? null)) {
            $data['clash_code'] = Str::lower(Str::random(8));
        }

        $clash = DB::transaction(function () use ($data): Clash {
            $clash = Clash::query()->create($data);
            $this->selectQuizMasterRole($clash);
            $this->syncHashtags($clash, 'clash_description');
            $this->notifyMentionedUsers($clash->user_id, $clash->clash_description, ['clash_id' => $clash->id]);
            $this->notifyFollowers($clash);

            return $clash;
        });

        return $this->handleResponse(
            new ClashResource($clash->load($this->relationships)),
            Lang::get('api.clashs.store')
        );
    }

    public function inviteMember(int $clashId, int $userId): JsonResponse
    {
        $clash = Clash::query()->find($clashId);

        if (! $clash) {
            return $this->handleError(null, Lang::get('api.clashs.not_found'), Response::HTTP_NOT_FOUND);
        }

        $clash->participants()->syncWithoutDetaching([
            $userId => ['participated' => false],
        ]);

        Notification::query()->create([
            'type' => 'clash_invitation',
            'from_user_id' => $clash->user_id,
            'to_user_id' => $userId,
            'clash_id' => $clash->id,
        ]);

        return $this->handleResponse(
            new ClashResource($clash->load($this->relationships)),
            Lang::get('api.clashs.member_invited')
        );
    }

    public function reactToClash(Request $request, int $clashId, int $userId): JsonResponse
    {
        $data = $request->validate([
            'reaction' => ['required', 'in:like,funny,difficult,informative,perfect'],
        ]);
        $clash = Clash::query()->find($clashId);

        if (! $clash) {
            return $this->handleError(null, Lang::get('api.clashs.not_found'), Response::HTTP_NOT_FOUND);
        }

        $clash->participants()->syncWithoutDetaching([
            $userId => ['reaction' => $data['reaction']],
        ]);

        Notification::query()->create([
            'type' => 'clash_liked',
            'from_user_id' => $userId,
            'to_user_id' => $clash->user_id,
            'clash_id' => $clash->id,
        ]);

        return $this->handleResponse(
            new ClashResource($clash->load($this->relationships)),
            Lang::get('api.clashs.reacted')
        );
    }

    public function participate(Request $request, int $clashId, int $userId): JsonResponse
    {
        $data = $request->validate([
            'participated' => ['required', 'boolean'],
        ]);
        $clash = Clash::query()->find($clashId);

        if (! $clash) {
            return $this->handleError(null, Lang::get('api.clashs.not_found'), Response::HTTP_NOT_FOUND);
        }

        if ($data['participated']) {
            $clash->participants()->syncWithoutDetaching([
                $userId => ['participated' => true],
            ]);

            Notification::query()->create([
                'type' => 'new_clash_attendee',
                'from_user_id' => $userId,
                'to_user_id' => $clash->user_id,
                'clash_id' => $clash->id,
            ]);
        } else {
            $clash->participants()->detach($userId);
        }

        return $this->handleResponse(
            new ClashResource($clash->load($this->relationships)),
            Lang::get('api.clashs.participation_changed')
        );
    }

    public function newsFeed(int $userId): JsonResponse
    {
        $followedUserIds = Subscription::query()
            ->where('follower_id', $userId)
            ->pluck('user_id');
        $sameClashIds = DB::table('clash_user')
            ->where('user_id', $userId)
            ->pluck('clash_id');
        $coParticipantIds = DB::table('clash_user')
            ->whereIn('clash_id', $sameClashIds)
            ->where('user_id', '!=', $userId)
            ->pluck('user_id');
        $subjectIds = DB::table('subjects')
            ->join('clashs', 'subjects.clash_id', '=', 'clashs.id')
            ->where('clashs.user_id', $userId)
            ->pluck('subjects.id');
        $sameSubjectClashIds = DB::table('subjects')
            ->whereIn('id', $subjectIds)
            ->pluck('clash_id');

        $orderedIds = collect()
            ->merge(Clash::query()->whereIn('user_id', $followedUserIds)->latest('id')->pluck('id'))
            ->merge(Clash::query()->whereIn('user_id', $coParticipantIds)->latest('id')->pluck('id'))
            ->merge(Clash::query()->whereIn('id', $sameSubjectClashIds)->latest('id')->pluck('id'))
            ->merge($this->popularPublicClashIds())
            ->merge($this->reactedClashIds())
            ->merge(Clash::query()->latest('id')->pluck('id'))
            ->unique()
            ->values();

        $clashes = Clash::query()
            ->with($this->relationships)
            ->whereIn('id', $orderedIds)
            ->get()
            ->sortBy(fn (Clash $clash): int => $orderedIds->search($clash->id))
            ->values();
        $paginated = paginate($clashes->all(), 20);

        return $this->handleResponse(
            ClashResource::collection($paginated),
            Lang::get('api.clashs.news_feed'),
            $paginated->lastPage(),
            $paginated->total()
        );
    }

    private function notifyFollowers(Clash $clash): void
    {
        Subscription::query()
            ->where('user_id', $clash->user_id)
            ->pluck('follower_id')
            ->each(fn (int $followerId): Notification => Notification::query()->create([
                'type' => 'clash_created',
                'from_user_id' => $clash->user_id,
                'to_user_id' => $followerId,
                'clash_id' => $clash->id,
            ]));
    }

    private function selectQuizMasterRole(Clash $clash): void
    {
        if (! $clash->user_id) {
            return;
        }

        $user = User::query()
            ->with('roles')
            ->find($clash->user_id);

        if (! $user || $this->hasRole($user, 'Administrateur')) {
            return;
        }

        $quizMasterRole = $this->quizMasterRole();

        DB::table('role_user')
            ->where('user_id', $user->id)
            ->update(['is_selected' => false]);

        $user->roles()->syncWithoutDetaching([
            $quizMasterRole->id => ['is_selected' => true],
        ]);
    }

    private function hasRole(User $user, string $roleName): bool
    {
        return $user->roles->contains(
            fn (Role $role): bool => $role->getTranslation('role_name', 'fr') === $roleName
        );
    }

    private function quizMasterRole(): Role
    {
        $role = Role::query()
            ->whereJsonContainsLocale('role_name', 'fr', 'Quiz master')
            ->first();

        if ($role) {
            return $role;
        }

        return Role::query()->create([
            'role_name' => [
                'fr' => 'Quiz master',
                'en' => 'Quiz master',
                'ln' => 'Quiz master',
            ],
            'role_description' => [
                'fr' => 'Personne ou organisation qui a créé au moins une fois un clash sur la plateforme.',
                'en' => 'Person or organization that has created at least one clash on the platform.',
                'ln' => 'Moto to ebongiseli oyo esili kosala clash ata mbala moko na plateforme.',
            ],
        ]);
    }

    /**
     * @return Collection<int, int>
     */
    private function popularPublicClashIds(): Collection
    {
        return Clash::query()
            ->where('type', 'public')
            ->withCount('participants')
            ->orderByDesc('participants_count')
            ->latest('id')
            ->pluck('id');
    }

    /**
     * @return Collection<int, int>
     */
    private function reactedClashIds(): Collection
    {
        return DB::table('clash_user')
            ->select('clash_id', DB::raw('count(reaction) as reactions_count'))
            ->whereNotNull('reaction')
            ->groupBy('clash_id')
            ->orderByDesc('reactions_count')
            ->pluck('clash_id');
    }
}
