<?php

namespace App\Http\Controllers;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Models\CheckingAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\NoReturn;

class CheckingAccountController extends Controller
{

    public function create(Request $request)
    {
        $incomingFields = $request->validate([
            'initial_amount' => ['required', 'numeric', 'min:0'],
            'currency_iso' => ['required'],
        ]);
        $incomingFields['initial_amount'] *= (10 ** Currency($request['currency_iso'])->getPrecision());

        $incomingFields['balance_in_lcm'] = (int)$incomingFields['initial_amount'];


        if (DB::table('checking_accounts')->orderBy('id', 'desc')->first()) {
            $lastId = DB::table('checking_accounts')->orderBy('id', 'desc')->first()->id;
        }
        $lastDigits = (int)isset($lastId) && $lastId > 0 ? $lastId + 1 : 1;
        $incomingFields['account_number'] = 'LV42BABA0' . str_repeat(0, 15 - strlen($lastDigits)) . $lastDigits;

        $incomingFields['user_id'] = auth()->id();

        CheckingAccount::create($incomingFields);
        return redirect('/');

    }

    public function sendMoney(Request $request)
    {
        $incomingFields = $request->validate([
            'from_account' => ['required', 'size:24'],
            'to_account' => ['required'],
            'send_amount' => ['required', 'min:0', 'numeric']
        ]);

        $fromAccount = CheckingAccount::where('account_number', '=', $incomingFields['from_account'])->first();

        if(auth()->user()->id !== $fromAccount->user_id){
            return redirect('/');
        }

        $toAccount = CheckingAccount::where('account_number', '=', $incomingFields['to_account'])->first();

        $fromCurrencyCode = $fromAccount->currency_iso;
        $toCurrencyCode = $toAccount->currency_iso;

        $fetchedRates = Http::get('https://api.coinbase.com/v2/exchange-rates?currency=' . $fromCurrencyCode);

        $exchangeRate = $fetchedRates->json()['data']['rates'][$toCurrencyCode];
        $fromCurrencyModel = new Currency($fromCurrencyCode);
        $toCurrencyModel = new Currency($toCurrencyCode);

        if (ceil($incomingFields['send_amount'] * (10 ** $fromCurrencyModel->getPrecision())) > floor($fromAccount->balance_in_lcm)) {
            return redirect('/dashboard');
        }

        $sendAmountInLcm = $incomingFields['send_amount']* (10 ** $fromCurrencyModel->getPrecision());
        $money = new Money($sendAmountInLcm, $fromCurrencyModel);

        $fromAccount->update(['balance_in_lcm' => $fromAccount->balance_in_lcm - $money->getAmount()]);
        $toAccount->update(['balance_in_lcm' => $toAccount->balance_in_lcm + $money->convert($toCurrencyModel,$exchangeRate)->getAmount()]);

        $newTransaction = [
            'sending_account_id' => $incomingFields['from_account'],
            'sending_user_id' => $fromAccount->user_id,
            'sending_account_currency' => $fromCurrencyCode,
            'receiving_account_id' => $incomingFields['to_account'],
            'receiving_user_id' => $toAccount->user_id,
            'receiving_account_currency' => $toCurrencyCode,
            'money_in_lcm' => $sendAmountInLcm
        ];
        Transaction::create($newTransaction);

        return redirect('/dashboard');
    }

    public function addMoney(Request $request)
    {
        return;
    }

}
