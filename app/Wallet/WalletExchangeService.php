<?php

namespace App\Wallet;

use Bavix\Wallet\Internal\Service\MathServiceInterface;
use Bavix\Wallet\Services\ExchangeServiceInterface;

class WalletExchangeService implements ExchangeServiceInterface
{
    private array $rates = [
        'FP' => [
            'GP' => 2,
        ],
        'GP' => [
            'FP' => 0.5,  // 1 / 1.75
        ],
    ];

    private MathServiceInterface $mathService;

    public function __construct(MathServiceInterface $mathService)
    {
        $this->mathService = $mathService;

        foreach ($this->rates as $from => $rates) {
            foreach ($rates as $to => $rate) {
                if (empty($this->rates[$to][$from])) {
                    $this->rates[$to][$from] = $this->mathService->div(1, $rate);
                }
            }
        }
    }

    /** @param float|int|string $amount */
    public function convertTo(string $fromCurrency, string $toCurrency, $amount): string
    {
        return $this->mathService->mul($amount, $this->rates[$fromCurrency][$toCurrency] ?? 1);
    }
}
