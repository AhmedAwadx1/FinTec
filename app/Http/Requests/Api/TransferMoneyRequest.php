<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Models\Account;
use Illuminate\Contracts\Validation\Validator;

class TransferMoneyRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'source_account_id' => 'required|integer|exists:accounts,id,deleted_at,NULL',
            'destination_account_id' => 'required|integer|exists:accounts,id,deleted_at,NULL|different:source_account_id',
            'amount' => 'required|integer|min:1',
        ];
    }

    /**
     * Configure the validator instance with custom validation rules
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $sourceAccountId = $this->input('source_account_id');
            $destinationAccountId = $this->input('destination_account_id');
            $amount = $this->input('amount');

            // Validate source account is active
            if ($sourceAccountId) {
                $sourceAccount = Account::find($sourceAccountId);
                if ($sourceAccount && !$sourceAccount->is_active) {
                    $validator->errors()->add(
                        'source_account_id',
                        __('apis.source_account_not_active')
                    );
                }

                // Validate sufficient balance
                if ($sourceAccount && $amount && $sourceAccount->balance < $amount) {
                    $validator->errors()->add(
                        'amount',
                        __('apis.insufficient_balance_in_source_account')
                    );
                }
            }

            // Validate destination account is active
            if ($destinationAccountId) {
                $destinationAccount = Account::find($destinationAccountId);
                if ($destinationAccount && !$destinationAccount->is_active) {
                    $validator->errors()->add(
                        'destination_account_id',
                        __('apis.destination_account_not_active')
                    );
                }
            }
        });
    }
}
