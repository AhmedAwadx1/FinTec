<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransferMoneyRequest;
use App\Http\Resources\Api\TransferResource;
use App\Services\MoneyTransferService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferController extends Controller
{
    use ResponseTrait;

    protected MoneyTransferService $moneyTransferService;

    public function __construct(MoneyTransferService $moneyTransferService)
    {
        $this->moneyTransferService = $moneyTransferService;
    }

    /**
     * Transfer money between accounts
     *
     * @param TransferMoneyRequest $request
     * @return JsonResponse
     */
    public function store(TransferMoneyRequest $request): JsonResponse
    {
       
        try {
            return DB::transaction(function () use ($request) {
                $validated = $request->validated();

                return $this->moneyTransferService->transferMoney(
                    $validated['source_account_id'],
                    $validated['destination_account_id'],
                    $validated['amount']
                );
            });
        } catch (\Exception $e) {
            Log::error('Transfer failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->failMsg(__('apis.failed_to_transfer_money', ['error' => $e->getMessage()]));
        }
    }

    public function index(): JsonResponse
    {
        try {
            $transfers = $this->moneyTransferService->getAllTransfers();
            return $this->successData(TransferResource::collection($transfers));
        } catch (\Exception $e) {
            return $this->failMsg(__('apis.failed_to_get_transfers'));
        }
    }
}
