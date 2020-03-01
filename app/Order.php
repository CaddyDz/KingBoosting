<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Order
 *
 * @property int $id
 * @property int $client_id
 * @property int $booster_id
 * @property string $status
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $booster
 * @property-read \App\User $client
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereBoosterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function booster()
    {
        return $this->belongsTo(User::class, 'booster_id');
    }

    public function getIconAttribute(): string
    {
        switch ($this->status) {
            case 'pending':
                return 'entypo:circular-graph';
            case 'progress':
                return 'entypo:controller-play';
            case 'paused':
                return 'entypo:controller-paus';
            case 'completed':
                return 'entypo:check';
            case 'suspended':
                return 'entypo:circle-with-cross';
            default:
                return 'entypo:controller-play';
        }
    }

    public function details()
    {
        return $this->hasOne(OrderDetails::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getTypeAttribute()
    {
        if ($this->service->name === 'League Boosting') {
            return 'What the fuck';
        }
    }
}
