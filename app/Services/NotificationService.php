<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function send(int $userId, string $type, string $title, string $body, array $data = []): ?Notification
    {
        $user = User::find($userId);
        if (!$user || !$user->is_active) {
            return null;
        }

        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => empty($data) ? null : $data,
            'is_read' => false,
        ]);

        FirebaseService::sendToUser($userId, $title, $body, [
            'type' => $type,
            'notification_id' => (string) $notification->id,
        ] + $data);

        return $notification;
    }

    public static function sendToZone(int $zoneId, string $type, string $title, string $body, array $data = []): int
    {
        $users = User::where('zone_id', $zoneId)
            ->where('is_active', true)
            ->get();

        $count = 0;
        foreach ($users as $user) {
            $n = Notification::create([
                'zone_id' => $zoneId,
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'data' => empty($data) ? null : $data,
                'is_read' => false,
            ]);

            FirebaseService::sendToUser($user->id, $title, $body, [
                'type' => $type,
                'notification_id' => (string) $n->id,
            ] + $data);

            $count++;
        }

        return $count;
    }

    public static function sendToRole(string $role, string $type, string $title, string $body, array $data = []): int
    {
        $users = User::role($role)->where('is_active', true)->get();

        $count = 0;
        foreach ($users as $user) {
            $n = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'data' => empty($data) ? null : $data,
                'is_read' => false,
            ]);

            FirebaseService::sendToUser($user->id, $title, $body, [
                'type' => $type,
                'notification_id' => (string) $n->id,
            ] + $data);

            $count++;
        }

        return $count;
    }
}
