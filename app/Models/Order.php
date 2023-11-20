<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Notifications\SendUserOrderRefundInReview;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Order extends Model implements Sortable
{
    use HasFactory, SoftDeletes;
    use InteractsWithAuditable;
    use HasUuids;
    use SortableTrait;

    protected $guarded = [];

    protected $casts = [
        'payment_status' => PaymentStatus::class,
        'order_status' => OrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apiCalls(): HasMany
    {
        return $this->hasMany(ApiCall::class);
    }

    public function orderQueue(): HasOne
    {
        return $this->hasOne(OrderQueue::class);
    }
    // We assume this as one to one relationship becuase all previous queue will be deleted if failed and a new one will be created
    // This means we will always have one and only one active queue


    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orderDetailRefunds()
    {
        return $this->hasManyThrough(OrderDetailRefund::class, OrderDetail::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'order_details');
    }

    protected function orderNumber(): Attribute
    {
        return Attribute::get(fn ($value, $attributes) => data_get($attributes, 'order_column'));
    }

    protected function moneyOrderTotal(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->order_total, 'USD'));
    }

    protected function moneyOrderSubtotal(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->order_subtotal, 'USD'));
    }

    protected function moneyOrderTax(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->order_tax, 'USD'));
    }

    protected function moneyOrderDiscount(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->order_discount, 'USD'));
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function scopeFlagged($query)
    {
        return $query->where('order_status', OrderStatus::Failed);
    }

    public function addToQueue()
    {
        // We can change the logic of what can be queued here in the future
        if ($this->discounts()->where('is_bhn', true)->exists()) {
            $this->orderQueue()->create();
        }
        // We are not queing order in Order::boot() because this queing logic needs complex calculations such as if payment is successfull and order is placed
    }

    public function createBhRefundRequest()
    {
        $this->loadMissing('orderDetails.orderDetailRefund.discount.brand');

        $refunds = [];
        $this->orderDetails->each(function ($orderDetail) use (&$refunds) {

            $refunds[] = OrderDetailRefund::query()
                ->updateOrCreate([
                    'order_detail_id' => $orderDetail->id
                ], [
                    'quantity' => $orderDetail->quantity,
                    'note' => OrderDetailRefund::BH_FAILURE_NOTE,
                    'created_by_id' => $this->user_id
                ]);

            // $refunds[] = $orderDetail->orderDetailRefund()->create([
            //     'quantity' => $orderDetail->quantity,
            //     'note' => OrderDetailRefund::BH_FAILURE_NOTE,
            //     'created_by_id' => $this->user_id
            // ]);
        });

        $lines = array_map(function ($refund) {
            return implode(' - ', [
                $refund->orderDetail->discount->brand->name,
                $refund->orderDetail->discount->name,
                'x' . $refund->quantity,
                'Reason:' . $refund->note,
            ]);
        }, $refunds);

        $this->user->notify(
            new SendUserOrderRefundInReview(
                orderNumber: $this->id,
                item: $lines
            )
        );
    }
}
