<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateAccountRequest;
use App\Http\Resources\Api\AccountResource;
use App\Http\Resources\Api\LedgerEntryResource;
use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Traits\PaginationTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    use ResponseTrait, PaginationTrait;

    protected AccountRepositoryInterface $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }


    public function store(CreateAccountRequest $request): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['account_number'] = $this->accountRepository->generateUniqueAccountNumber();
                $data['is_active'] = true;

                $account = $this->accountRepository->create($data);

                return $this->successMsg(__('apis.account_created_successfully'));
            });
        } catch (\Exception $e) {
            return $this->failMsg(__('apis.failed_to_create_account'));
        }
    }

    public function index(): JsonResponse
    {
        try {
            $perPage = request()->get('per_page', $this->paginateNum());
            $accounts = $this->accountRepository->paginate($perPage);
            return $this->response('success', trans('apis.success'), AccountResource::collection($accounts), $this->paginationModel($accounts));
        } catch (\Exception $e) {
            return $this->failMsg(__('apis.failed_to_get_accounts'));
        }
    }


    public function transactions(Account $account): JsonResponse
    {
        try {
            $perPage = request()->get('per_page', $this->paginateNum());
            $ledgerEntries = $this->accountRepository->getLedgerEntries($account->id, $perPage);

            return $this->response('success', trans('apis.success'), LedgerEntryResource::collection($ledgerEntries), $this->paginationModel($ledgerEntries));
        } catch (\Exception $e) {
            return $this->failMsg(__('apis.failed_to_get_transactions'));
        }
    }
}
