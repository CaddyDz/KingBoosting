<?php

declare(strict_types=1);

use App\Models\{Order, User};
use Illuminate\Support\Facades\Broadcast;

// Notifications for one user
Broadcast::channel('App.Models.User.{id}', fn (User $user, int $id): bool => $user->id === $id);

// One order's chat and live status, for whoever OrderPolicy::chat allows
Broadcast::channel('orders.{order}', fn (User $user, Order $order): bool => $user->can('chat', $order));
