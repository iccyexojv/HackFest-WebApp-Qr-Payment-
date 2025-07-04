<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Wallet\Enums\WalletType;
use App\Traits\Models\HasStallOrderLog;
use Illuminate\Database\Eloquent\Model;
use App\Enums\StallOrder\StallOrderStatus;
use App\Enums\StallOrder\StallOrderTransactionStatus;
use App\Filament\Pages\QuickPayPayment;
use App\Wallet\Enums\WalletTransactionMetaType;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class StallOrder extends Model
{
    use HasStallOrderLog;

    protected $fillable = [
        'stall_id',
        'stall_owner_id',
        'ordered_for_type',
        'ordered_for_id',
        'fest_point_total_amount',
        'game_point_total_amount',
        'wallet_type',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'ordered_for_type' => 'string',
            'ordered_for_id' => 'integer',
            'fest_point_total_amount' => 'decimal:2',
            'game_point_total_amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'wallet_type' => WalletType::class,
            'status' => StallOrderStatus::class,
        ];
    }

    // Booted
    protected static function booted()
    {
        self::creating(function (StallOrder $stallOrder) {
            $code = Str::random(12) . '-' . now()->timestamp . '-' . $stallOrder->id;

            $stallOrder->code = Str::random(12) . '-' . $code;
        });
    }

    // Helpers

    public function hasCompletedPayment(): bool
    {
        return false;
    }

    public static function parseQrData(string $data): ?StallOrder
    {
        return StallOrder::where('code', Crypt::decryptString($data))->first();
    }

    public function getTotalAmount(): float
    {
        $items = $this->stallOrderItems()
            ->get([
                'fest_point_total_amount',
                'game_point_total_amount',
            ]);

        if ($this->wallet_type == WalletType::FEST_POINT) {
            return $items->sum('fest_point_total_amount');
        }
        return $items->sum('game_point_total_amount');
    }

    public function getPaidAmount(): float
    {
        return $this->stallOrderTransactions()
            ->get()
            ->where('status', StallOrderTransactionStatus::SUCCESS->value)
            ->where('wallet_type', $this->wallet_type->value)
            ->sum('amount');
    }

    public function payUsing(Wallet $wallet, float $amount): bool
    {
        $this->loadMissing('stall');
        $stallWallet = $this->wallet_type->of($this->stall);
        $payer = $wallet->holder()->first();
        try {
            if ($amount > $wallet->balance) {
                return false;
            } else {
                $transferred = DB::transaction(function () use ($wallet, $amount, $stallWallet, $payer): bool {
                    $wallet->transfer(
                        $stallWallet,
                        $amount,
                        WalletTransactionMetaType::TRANSFER
                            ->getUserMeta()
                    );

                    StallOrderTransaction::create([
                        'stall_id' => $this->stall_id,
                        'stall_order_id' => $this->id,
                        'stall_owner_id' => $this->stall_owner_id,
                        'wallet_type' => $this->wallet_type,
                        'amount' => $amount,
                        'status' => StallOrderTransactionStatus::SUCCESS,
                        'payer_id' => $payer->id,
                        'payer_type' => get_class($payer),
                    ]);

                    return true;
                });

                return $transferred;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    // Relationships

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    public function stallOwner()
    {
        return $this->belongsTo(StallOwner::class);
    }

    public function orderedFor()
    {
        return $this->morphTo('ordered_for');
    }

    public function stallOrderItems()
    {
        return $this->hasMany(StallOrderItem::class);
    }

    public function stallOrderTransactions()
    {
        return $this->hasMany(StallOrderTransaction::class);
    }
}
