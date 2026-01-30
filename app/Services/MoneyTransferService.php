<?php

namespace App\Services;

use App\Enums\LedgerEntryType;
use App\Enums\TransferStatus;
use App\Http\Resources\Api\TransferResource;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\LedgerEntryRepositoryInterface;
use App\Repositories\Contracts\TransferRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Traits\ResponseTrait;
use App\Models\Transfer;
use Illuminate\Support\Collection;

class MoneyTransferService
{
    use ResponseTrait;

    protected AccountRepositoryInterface $accountRepository;
    protected TransferRepositoryInterface $transferRepository;
    protected LedgerEntryRepositoryInterface $ledgerEntryRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        TransferRepositoryInterface $transferRepository,
        LedgerEntryRepositoryInterface $ledgerEntryRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->transferRepository = $transferRepository;
        $this->ledgerEntryRepository = $ledgerEntryRepository;
    }

    public function transferMoney(int $sourceAccountId, int $destinationAccountId, int $amount): JsonResponse
    {
        $accountIds = [$sourceAccountId, $destinationAccountId];
        sort($accountIds);
        [$firstAccountId, $secondAccountId] = $accountIds;
        try {
            return DB::transaction(function () use ($sourceAccountId, $destinationAccountId, $amount, $firstAccountId, $secondAccountId) {
                // Step 1: Create transfer record with status PENDING
                $transfer = $this->transferRepository->create([
                    'source_account_id' => $sourceAccountId,
                    'destination_account_id' => $destinationAccountId,
                    'amount' => $amount,
                    'status' => TransferStatus::PENDING,
                    'reference' => Transfer::generateReference(),
                ]);

                $firstAccount = $this->accountRepository->findByIdWithLock($firstAccountId);
                $secondAccount = $this->accountRepository->findByIdWithLock($secondAccountId);

                // Ensure we have the correct accounts
                $sourceAccount = ($firstAccountId === $sourceAccountId) ? $firstAccount : $secondAccount;
                $destinationAccount = ($firstAccountId === $destinationAccountId) ? $firstAccount : $secondAccount;

                // Step 3: Update balances
                $sourceNewBalance = $sourceAccount->balance - $amount;
                $destinationNewBalance = $destinationAccount->balance + $amount;

                $this->accountRepository->updateBalance($sourceAccountId, $sourceNewBalance);
                $this->accountRepository->updateBalance($destinationAccountId, $destinationNewBalance);

                $this->ledgerEntryRepository->create([
                    'account_id' => $sourceAccountId,
                    'transfer_id' => $transfer->id,
                    'amount' => -$amount,
                    'type' => LedgerEntryType::DEBIT,
                    'balance_after' => $sourceNewBalance,
                    'description' => "Transfer to account {$destinationAccount->account_number}",
                ]);

                $this->ledgerEntryRepository->create([
                    'account_id' => $destinationAccountId,
                    'transfer_id' => $transfer->id,
                    'amount' => $amount,
                    'type' => LedgerEntryType::CREDIT,
                    'balance_after' => $destinationNewBalance,
                    'description' => "Transfer from account {$sourceAccount->account_number}",
                ]);

                $this->transferRepository->updateStatus(
                    $transfer->id,
                    TransferStatus::SUCCESS,
                    null,
                    Carbon::now()
                );

                // Refresh transfer model
                $transfer->refresh();

                return $this->successData(new TransferResource($transfer));
            });
        } catch (\Exception $e) {
            Log::error('Money transfer failed', [
                'source_account_id' => $sourceAccountId,
                'destination_account_id' => $destinationAccountId,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);

            return $this->failMsg(__('apis.failed_to_transfer_money', ['error' => $e->getMessage()]));
        }
    }

    public function getAllTransfers(): Collection
    {
        try {
            return $this->transferRepository->all();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

}
