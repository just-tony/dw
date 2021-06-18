<?php


namespace App\Method\Balance;


use App\Repository\BalanceHistoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Required;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

class History implements JsonRpcMethodInterface, MethodWithValidatedParamsInterface
{
    public function __construct(private BalanceHistoryRepository $balanceHistoryRepository)
    {
    }

    public function apply(array $paramList = null)
    {
        return $this->balanceHistoryRepository->getUserLastOperations(
            $paramList['user_id'],
            $paramList['limit'] ?? 10
        );
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
                    'limit' => new Optional(
                        [
                            new Positive(),
                            new LessThan(100)
                        ]
                    ),
                ]
            ]
        );
    }
}
