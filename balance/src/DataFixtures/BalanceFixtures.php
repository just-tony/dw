<?php

namespace App\DataFixtures;

use App\Entity\BalanceHistory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BalanceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $balance = '0.0';

        $date = (new \DateTimeImmutable())->sub(new \DateInterval('P2Y'));
        $lastDay = (new \DateTimeImmutable())->sub(new \DateInterval('P15D'));

        do {
            //Add some BTC
            $date = $date->add(new \DateInterval('P' . rand(12, 14) . 'D'));
            $amount = rand(100000, 750000);
            $cashInAmount = sprintf("0.%08d", $amount);
            $balance = bcadd($balance, $cashInAmount, 8);

            //echo $date->format('d.m.Y') . PHP_EOL . "CASH_IN\t" . $cashInAmount . "\t[" . $balance . "]" . PHP_EOL;
            $balanceState = $this->createBalanceHistoryEntity(
                $date,
                $cashInAmount,
                $balance,
                1
            );
            $manager->persist($balanceState);

            //Spent some BTC
            $date = $date->add(new \DateInterval('P' . rand(0, 2) . 'D'));

            $leftOver = sprintf("0.%08d", rand(0, 10000));
            $paymentAmount = bcsub($balance, $leftOver, 8);
            $balance = $leftOver;

            //echo $date->format('d.m.Y') . PHP_EOL . echo "PAYMENT\t" . $paymentAmount . "\t[" . $balance . "]" . PHP_EOL;
            $balanceState = $this->createBalanceHistoryEntity(
                $date,
                bcmul($paymentAmount, '-1', 8),
                $balance,
                1
            );
            $manager->persist($balanceState);
        } while ($date <= $lastDay);

        $manager->flush();
    }

    protected function createBalanceHistoryEntity(
        \DateTimeImmutable $createdAt,
        string $value,
        string $balance,
        int $userId
    ): BalanceHistory {
        $balanceState = new BalanceHistory();
        $balanceState->setCreatedAt($createdAt);
        $balanceState->setValue($value);
        $balanceState->setBalance($balance);
        $balanceState->setUserId($userId);
        return $balanceState;
    }
}
