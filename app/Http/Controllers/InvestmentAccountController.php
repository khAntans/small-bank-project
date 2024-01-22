<?php

namespace App\Http\Controllers;

use App\Models\CheckingAccount;
use App\Models\ExchangeRate;
use App\Models\InvestmentAccount;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvestmentAccountController extends Controller
{
    public function create()
    {
        $found = InvestmentAccount::where('user_id', '=', auth()->id())->first();
        if ($found) {
            return redirect('/');
        }

        InvestmentAccount::create(['user_id' => auth()->id(), 'investments' => json_encode(['crypto' => []])]);
        return redirect('/dashboard/invest');
    }

    public function buyCrypto(Request $request)
    {
        if (auth()->check()) {

            $incomingFields = $request->validate([
                'from_account' => ['required'],
                'to_account' => ['required'],
                'currency' => ['required'],
                'amount' => ['required', 'numeric']
            ]);

            $fromAccount = CheckingAccount::where('account_number', '=', $incomingFields['from_account'])->first();

            if (auth()->id() !== $fromAccount->user_id) {
                return redirect('/');
            }

            $rate = 1 / ExchangeRate::where('iso_code', '=', $incomingFields['currency'])->first()->rate;

            $amount = ceil($incomingFields['amount'] * $rate * 100);

            if ($amount > $fromAccount['balance_in_lcm']) {
                return redirect('/');
            }

            $fromAccount->update(['balance_in_lcm' => $fromAccount->balance_in_lcm - $amount]);

            $newTransaction = [
                'sending_account_id' => $incomingFields['from_account'],
                'sending_user_id' => auth()->id(),
                'sending_account_currency' => 'USD',
                'receiving_account_id' => $incomingFields['to_account'],
                'receiving_user_id' => 0,
                'receiving_account_currency' => $incomingFields['currency'],
                'money_in_lcm' => $amount
            ];

            Transaction::create($newTransaction);

            $account = InvestmentAccount::where('user_id', '=', auth()->id())->first();

            $data = json_decode($account->investments);
            if (!$data->crypto) {
                $data->crypto = [];
            }

            $data->crypto [] = [
                'currency' => $incomingFields['currency'],
                'amount' => $incomingFields['amount'],
                'rate_at_purchase' => 1/ExchangeRate::where('iso_code', '=', $incomingFields['currency'])->first()->rate * 100,
                'date' => Carbon::now()->toDateTimeString()
            ];

            $account->update(['investments' => json_encode($data)]);


            return redirect('/dashboard/invest');

        }
        return redirect('/');
    }

    public function sellCrypto(Request $request)
    {
        if (auth()->check()) {

            $incomingFields = $request->validate([
                'from_account' => ['required'],
                'date' => ['required'],
                'currency' => ['required'],
                'sell_amount' => ['required', 'numeric']
            ]);

            $toAccount = CheckingAccount::where('user_id','=',auth()->id())->where('currency_iso','=','USD')->first();

            $userInvestments = array_values(json_decode(InvestmentAccount::where('user_id','=',auth()->id())->first()['investments'],true))[0];

            $selling = '';
            $sellingId = '';

            for($i=0;$i<count($userInvestments);$i++){
                if($userInvestments[$i]['date']== $incomingFields['date'] && $userInvestments[$i]['currency'] == $incomingFields['currency']){
                    $sellingId = $i;
                }
            }

            $rate = 1 / ExchangeRate::where('iso_code','=',$incomingFields['currency'])->first()->rate;

            $amount = floor($rate * $incomingFields['sell_amount'] * 100 );



            if($incomingFields['sell_amount'] >= $userInvestments[$sellingId]['amount']){
                unset($userInvestments[$sellingId]);
            }else{
                $userInvestments[$sellingId]['amount'] -= $incomingFields['sell_amount'];
            }

            $toAccount->update(['balance_in_lcm' => $toAccount->balance_in_lcm + $amount]);
            $userInvestments =['crypto' => array_values($userInvestments)];
            InvestmentAccount::where('user_id','=',auth()->id())->first()->update(['investments'=> json_encode($userInvestments)]);

            $newTransaction = [
                'sending_account_id' => $incomingFields['from_account'],
                'sending_user_id' => 0,
                'sending_account_currency' => 'USD',
                'receiving_account_id' => $toAccount->account_number,
                'receiving_user_id' => auth()->id(),
                'receiving_account_currency' => 'USD',
                'money_in_lcm' => $amount
            ];

            Transaction::create($newTransaction);


            return redirect('/dashboard/invest');


        }
        return redirect('/');

    }

}
