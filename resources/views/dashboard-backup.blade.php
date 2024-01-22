<x-app-layout>
    <div class="mx-auto w-[100%] max-w-[400px] p-4 place-content-center justify-center grid">
        <form action="/create-new-checking-account" method="post" class="mb-6">
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
            <button class="bg-blue-500 text-white py-2 px-4 rounded">Create account</button>
        </form>

{{--        <form action="/add-money" method="post" class="mb-6">--}}
{{--            @csrf--}}
{{--            <h3 class="text-xl font-semibold mb-4">Add money</h3>--}}
{{--            <label for="to_account" class="block text-sm font-medium text-gray-700 mb-2">To Account</label>--}}
{{--            <select name="to_account" id="to_account" class="form-select mb-4">--}}
{{--                @foreach($checkingAccounts as $checkingAccount)--}}
{{--                    <option value="{{$checkingAccount['account_number']}}">{{$checkingAccount['currency_iso']}}--}}
{{--                        - {{$checkingAccount['account_number']}}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            <label for="sendAmount" class="block text-sm font-medium text-gray-700 mb-2">Enter amount</label>--}}
{{--            <input id="sendAmount" type="text" name="send_amount" min="0" class="form-input mb-4"/>--}}
{{--            <button class="bg-blue-500 text-white py-2 px-4 rounded">Send</button>--}}
{{--        </form>--}}

        <form action="/send-money" method="post" class="mb-6">
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
            <button class="bg-blue-500 text-white py-2 px-4 rounded">Send</button>
        </form>

        <div class="border-3 border-black p-4 mb-6">
            <h1 class="text-2xl font-semibold mb-4">All debit accounts</h1>
            @foreach($checkingAccounts as $checkingAccount)
                <div class="border-b-3 border-gray-400 mb-4">
                    <h3 class="text-lg">{{$checkingAccount['account_number']}}</h3>
                    <h3 class="text-lg">{{@Money($checkingAccount['balance_in_lcm'],$checkingAccount['currency_iso'])}}</h3>
                    <h3 class="text-lg">{{@Currency($checkingAccount['currency_iso'])}}</h3>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
