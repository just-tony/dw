<?php


namespace App\Method\Balance;


use App\Repository\BalanceHistoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Required;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

class UserBalance implements JsonRpcMethodInterface, MethodWithValidatedParamsInterface
{
    public function __construct(private BalanceHistoryRepository $balanceHistoryRepository)
    {
    }

    public function apply(array $paramList = null)
    {
        $lastOperation = $this->balanceHistoryRepository->getUserLastOperation($paramList['user_id']);
        return $lastOperation?->getBalance() ?? null;
    }

    public function getParamsConstraint(): Constraint
    {
        return new Collection(
            [
                'allowExtraFields' => false,
                'fields' => [
                    'user_id' => new Required(
                        [
                            new Positive()
                        ]
                    ),
                ]
            ]
        );
    }
}
