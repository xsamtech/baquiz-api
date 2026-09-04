<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Clash;
use App\Models\Competence;
use App\Models\Domain;
use App\Models\Field;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $notifications = Notification::query()
            ->with(['fromUser', 'toUser', 'clash'])
            ->where('to_user_id', $request->user()->id)
            ->where('is_read', false)
            ->latest('id')
            ->paginate(10);

        return NotificationResource::collection($notifications)->response();
    }

    public function search(Request $request): JsonResponse
    {
        $data = $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);
        $term = '%'.$data['q'].'%';

        return response()->json([
            'users' => User::query()->select(['id', 'uuid', 'firstname', 'lastname', 'username', 'avatar_url'])->where(fn ($query) => $query->where('firstname', 'like', $term)->orWhere('lastname', 'like', $term)->orWhere('username', 'like', $term))->limit(5)->get(),
            'subjects' => Subject::query()->select(['id', 'uuid', 'subject_name'])->where('subject_name', 'like', $term)->limit(5)->get(),
            'domains' => Domain::query()->select(['id', 'uuid', 'domain_name'])->where('domain_name', 'like', $term)->limit(5)->get(),
            'fields' => Field::query()->select(['id', 'uuid', 'field_name'])->where('field_name', 'like', $term)->limit(5)->get(),
            'competences' => Competence::query()->select(['id', 'uuid', 'competence_name'])->where('competence_name', 'like', $term)->limit(5)->get(),
        ]);
    }

    private function usersInRole(string $role): int
    {
        return User::query()
            ->whereHas('roles', fn ($query) => $query->whereJsonContainsLocale('role_name', 'fr', $role))
            ->count();
    }
}
