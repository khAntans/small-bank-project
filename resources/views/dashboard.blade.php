@php use App\Models\CheckingAccount; @endphp
<x-app-layout>
    @if(! CheckingAccount::where('user_id', '=', auth()->id())->first())
        <div class="grid gap-3 pt-3">
        <h2 class="font-bold text-4xl h-min">
            Welcome, {{auth()->user()->name}}!
        </h2>
        <div id="create-account"
             class="row-start-2 border border-gray-100 shadow-md p-2 rounded-md bg-gray-50 max-w-sm p-2">
            <form action="/create-new-checking-account" method="post" class="mb-6 grid grid-cols-1 max-w-sm">
                @csrf<h3 class="text-xl font-semibold mb-4">Create your first debit account</h3>
                <label for="currency_isos" class="block text-sm font-medium text-gray-700 mb-2">Select Account
                    currency</label>
                <select name="currency_iso" id="currency_isos" class="form-select mb-4">
                    <option value="USD">USD - {{@Currency('USD')->getName()}}</option>
                    <option value="EUR">EUR - {{@Currency('EUR')->getName()}}</option>
                    <option value="GBP">GBP - {{@Currency('GBP')->getName()}}</option>
                    <option value="PLN">PLN - {{@Currency('PLN')->getName()}}</option>
                </select>
                <label for="depositAmount" class="block text-sm font-medium text-gray-700 mb-2">Enter initial deposit
                    amount</label>
                <input id="depositAmount" type="number" name="initial_amount" class="form-input mb-4"/>
                <button class="bg-black mt-2 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-md">Create account</button>
            </form>
        </div>
    </div>
    @else
    <div class="grid grid-cols-2 gap-3 pt-4 w-1/1.5">
        <h2 class="col-start-1 col-span-4 row-start-1 row-span-1 font-bold text-4xl h-min">
            Welcome, {{auth()->user()->name}}!
        </h2>
        <div id="accounts"
             class="row-start-2 row-span-3 col-start-1 col-span-8 grid
             grid-rows-min border border-gray-100 shadow-md p-2 rounded-md
             bg-gray-50 pb-7">
            <h3 class="font-bold h-min text-2xl text-center">Your accounts</h3>
            @foreach($checkingAccounts as $checkingAccount)
                <div class="border-b-2 border-gray-400 max-h-min row-span-1 pt-2 grid-cols-5 grid grid-rows-1">
                    <div class="text-lg col-start-1 col-span-4 row-auto">
                        <p class="text-lg font-bold">{{$checkingAccount['account_number']}}</p>
                        <p class="text-lg">{{@Money($checkingAccount['balance_in_lcm'],$checkingAccount['currency_iso'])}}</p>
                        <p class="text-sm">Created at: {{$checkingAccount['created_at']}}</p>
                    </div>
                    <p class="text-lg col-start-5 col-span-1 row-start-1 row-span-2 grid align-middle m-auto pb-2">{{@Currency($checkingAccount['currency_iso'])}}</p>
                </div>
            @endforeach

            <div class="h-max row-span-12"></div>
        </div>

        <div id="transfers"
             class="row-start-1 row-span-2 col-start-9 col-span-4 border border-gray-100 shadow-md p-2 rounded-md bg-gray-50 max-w-sm">
            <form action="/send-money" method="post" class="mb-6 grid grid-cols-1 max-w-sm">
                @csrf
                <h3 class="text-xl font-semibold mb-4">Transfer money</h3>
                <label for="from_account" class="block text-sm font-medium text-gray-700 mb-2">From Account</label>
                <select name="from_account" id="from_account" class="form-select mb-4">
                    @foreach($checkingAccounts as $checkingAccount)
                        <option value="{{$checkingAccount['account_number']}}">{{$checkingAccount['currency_iso']}}
                            - {{$checkingAccount['account_number']}}</option>
                    @endforeach
                </select>
                <label for="to_account" class="block text-sm font-medium text-gray-700 mb-2">To Account</label>
                <input type="text" id="to_account" name="to_account" value="LV42BABA0" class="form-input mb-4"/>
                <label for="sendAmount" class="block text-sm font-medium text-gray-700 mb-2">Enter amount</label>
                <input id="sendAmount" type="text" name="send_amount" min="0" class="form-input mb-4"/>
                <button class="bg-black mt-2 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-md">Send</button>
            </form>
        </div>

        <div id="create-account"
             class="row-start-3 row-span-1 col-start-9 col-span-4 border border-gray-100 shadow-md p-2 rounded-md bg-gray-50 max-w-sm p-2">
            <form action="/create-new-checking-account" method="post" class="mb-6 grid grid-cols-1 max-w-sm">
                @csrf<h3 class="text-xl font-semibold mb-4">Create new debit account</h3>
                <label for="currency_isos" class="block text-sm font-medium text-gray-700 mb-2">Select Account
                    currency</label>
                <select name="currency_iso" id="currency_isos" class="form-select mb-4">
                    <option value="USD">USD - {{@Currency('USD')->getName()}}</option>
                    <option value="EUR">EUR - {{@Currency('EUR')->getName()}}</option>
                    <option value="GBP">GBP - {{@Currency('GBP')->getName()}}</option>
                    <option value="PLN">PLN - {{@Currency('PLN')->getName()}}</option>
                </select>
                <label for="depositAmount" class="block text-sm font-medium text-gray-700 mb-2">Enter initial deposit
                    amount</label>
                <input id="depositAmount" type="number" name="initial_amount" class="form-input mb-4"/>
                <button class="bg-black mt-2 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-md">Create account</button>
            </form>
        </div>

        <div id="newest-transactions"
             class="row-start-5 row-span-1 col-start-1 col-span-8 border border-gray-100 shadow-md p-2 rounded-md bg-gray-50 pb-4 max-w-[1000px]">
            <h4 class="font-bold h-min text-2xl text-center pb-2">Recent transactions</h4>
            <div class="grid grid-cols-5 grid-rows-4">
                <h5 class="font-bold row-start-1 col-start-1 text-center border-b-2 border-gray-400 ">From Account</h5>
                <h5 class="font-bold row-start-1 col-start-2 text-center border-b-2 border-gray-400 ">From Currency</h5>
                <h5 class="font-bold row-start-1 col-start-3 text-center border-b-2 border-gray-400 ">To Account</h5>
                <h5 class="font-bold row-start-1 col-start-4 text-center border-b-2 border-gray-400 ">To Currency</h5>
                <h5 class="font-bold row-start-1 col-start-5 text-center border-b-2 border-gray-400 ">Amount</h5>
                @foreach($latestTransactions as $transaction)
                    @if($transaction['sending_user_id'] == auth()->user()->id)
                        @php
                            $color = "text-red-700";
                        @endphp
                    @elseif($transaction['receiving_user_id'] == auth()->user()->id)
                        @php
                            $color = "text-green-700";
                        @endphp
                    @endif
                    @if($transaction['receiving_user_id'] == auth()->user()->id && $transaction['sending_user_id'] == auth()->user()->id)
                        @php
                            $color = "text-black";
                        @endphp
                    @endif
                    <p class="text-center text-sm row-auto col-start-1 font-bold p-1 text-gray-700 border-b">{{$transaction['sending_account_id']}}</p>
                    <p class="text-sm col-start-2 text-center p-1 border-b">{{$transaction['sending_account_currency']}}</p>
                    <p class="text-center text-sm row-auto col-start-3 font-bold p-1 text-gray-700 border-b">{{$transaction['receiving_account_id']}}</p>
                    <p class="text-sm col-start-4 text-center p-1 border-b">{{$transaction['receiving_account_currency']}}</p>
                    <p class="col-start-5 text-sm text-center {{$color}} p-1 border-b">{{@Money($transaction['money_in_lcm'],$transaction['sending_account_currency'])}}</p>
                @endforeach
            </div>
        </div>

    </div>
    @endif
</x-app-layout>
