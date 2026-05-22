<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;

class NotificationController extends ApiController
{
    protected string $modelClass = Notification::class;

    protected string $resourceClass = NotificationResource::class;

    protected string $messageKey = 'notifications';

    protected array $relationships = [
        'fromUser',
        'toUser',
        'clash',
        'comment',
        'message',
        'question',
        'assertion',
        'answer',
    ];

    protected array $storeRules = [
        'type' => ['sometimes', 'nullable', 'in:welcome_new_user,user_mention,user_birthday,clash_invitation,new_clash_attendee,clash_created,clash_started,clash_ended,clash_liked,medal_awarded,new_follower,payment_pending,payment_successful,payment_failed'],
        'is_read' => ['sometimes', 'nullable', 'boolean'],
        'from_user_id' => ['sometimes', 'nullable', 'integer'],
        'to_user_id' => ['sometimes', 'nullable', 'integer'],
        'clash_id' => ['sometimes', 'nullable', 'integer'],
        'comment_id' => ['sometimes', 'nullable', 'integer'],
        'message_id' => ['sometimes', 'nullable', 'integer'],
        'question_id' => ['sometimes', 'nullable', 'integer'],
        'assertion_id' => ['sometimes', 'nullable', 'integer'],
        'answer_id' => ['sometimes', 'nullable', 'integer'],
    ];

    protected array $updateRules = [
        'type' => ['sometimes', 'nullable', 'in:welcome_new_user,user_mention,user_birthday,clash_invitation,new_clash_attendee,clash_created,clash_started,clash_ended,clash_liked,medal_awarded,new_follower,payment_pending,payment_successful,payment_failed'],
        'is_read' => ['sometimes', 'nullable', 'boolean'],
        'from_user_id' => ['sometimes', 'nullable', 'integer'],
        'to_user_id' => ['sometimes', 'nullable', 'integer'],
        'clash_id' => ['sometimes', 'nullable', 'integer'],
        'comment_id' => ['sometimes', 'nullable', 'integer'],
        'message_id' => ['sometimes', 'nullable', 'integer'],
        'question_id' => ['sometimes', 'nullable', 'integer'],
        'assertion_id' => ['sometimes', 'nullable', 'integer'],
        'answer_id' => ['sometimes', 'nullable', 'integer'],
    ];
}
