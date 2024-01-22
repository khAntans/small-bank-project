<x-app-layout>
    <div class="grid grid-cols-6">
        <h5 class="font-bold row-start-1 col-start-1 text-center border-b-2 border-gray-400 ">From Account</h5>
        <h5 class="font-bold row-start-1 col-start-2 text-center border-b-2 border-gray-400 ">Currency</h5>
        <h5 class="font-bold row-start-1 col-start-3 text-center border-b-2 border-gray-400 ">To Account</h5>
        <h5 class="font-bold row-start-1 col-start-4 text-center border-b-2 border-gray-400 ">Currency</h5>
        <h5 class="font-bold row-start-1 col-start-5 text-center border-b-2 border-gray-400 ">Amount</h5>
        <h5 class="font-bold row-start-1 col-start-6 text-center border-b-2 border-gray-400 ">Time</h5>
        @foreach($transactions as $transaction)
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
            <p class="text-center text-md row-auto col-start-1 font-bold p-1 text-gray-700 border-b border-gray-400">{{$transaction['sending_account_id']}}</p>
            <p class="text-md col-start-2 text-center p-1 border-b border-gray-400">{{$transaction['sending_account_currency']}}</p>
            <p class="text-center text-md row-auto col-start-3 font-bold p-1 text-gray-700 border-b border-gray-400">{{$transaction['receiving_account_id']}}</p>
            <p class="text-md col-start-4 text-center p-1 border-b  border-gray-400">{{$transaction['receiving_account_currency']}}</p>
            <p class="col-start-5 text-md text-center {{$color}} p-1 border-b  border-gray-400">{{@Money($transaction['money_in_lcm'],$transaction['sending_account_currency'])}}</p>
            <p class="col-start-6 text-md text-center p-1 border-b  border-gray-400">{{$transaction['created_at']}}</p>
        @endforeach
    </div>
</x-app-layout>
