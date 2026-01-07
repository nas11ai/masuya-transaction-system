<?php

namespace App\Services;

use App\Contracts\Repositories\DiscountTypeRepositoryInterface;

class DiscountService
{
    public function __construct(
        private DiscountTypeRepositoryInterface $discountTypeRepository
    ) {
    }

    /**
     * Calculate cascading discounts
     *
     * @param float $basePrice
     * @param array $discounts [['type' => 'percentage|fixed', 'value' => 10], ...]
     * @return array ['total_discount' => float, 'net_price' => float, 'breakdown' => []]
     */
    public function calculateCascadingDiscounts(float $basePrice, array $discounts): array
    {
        $currentPrice = $basePrice;
        $totalDiscount = 0;
        $breakdown = [];

        foreach ($discounts as $index => $discount) {
            $discountAmount = 0;

            if ($discount['type'] === 'percentage') {
                $discountAmount = round($currentPrice * ($discount['value'] / 100), 2);
            } else {
                $discountAmount = min($discount['value'], $currentPrice);
            }

            $totalDiscount += $discountAmount;
            $currentPrice -= $discountAmount;

            $breakdown[] = [
                'sequence' => $index + 1,
                'type' => $discount['type'],
                'value' => $discount['value'],
                'amount' => $discountAmount,
                'price_after' => $currentPrice,
            ];
        }

        return [
            'total_discount' => round($totalDiscount, 2),
            'net_price' => round($currentPrice, 2),
            'breakdown' => $breakdown,
        ];
    }

    public function getActiveDiscounts()
    {
        return $this->discountTypeRepository->getActive();
    }

    public function getDiscountByCode(string $code)
    {
        return $this->discountTypeRepository->findByCode($code);
    }
}
