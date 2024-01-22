@php use App\Models\InvestmentAccount; @endphp
<x-app-layout>

    @if(! InvestmentAccount::where('user_id', '=', auth()->user()->id)->first())
        <div class="w-[600px] self-center">
            <h2 class="font-extrabold text-8xl">HEY!</h2>
            <h2 class="font-bold text-4xl">Want to start investing?</h2>
            <p class="font-extrabold text-6xl text-left">Open a investment account today!</p>
            <form action="/dashboard/invest" method="post" class="text-center py-4">
                @csrf
                <button class="bg-black hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-xl">Create
                </button>
            </form>
            <p class="text-sm text-center font-extralight">We take no responsibility for your investment decisions.
                Don't be a dum dum and invest wisely.</p>
        </div>
    @else
        @php
            $currencies = ["BTC" => 'Bitcoin',"ETH"=>"Ethereum","DOGE"=>"Doge coin","SOL" =>"Solana"];
        @endphp

        <div class="grid grid-cols-2 gap-3 pt-4 w-1/1.5">
            <div id="coins" class="grid col-start-1  pb-7 h-min">
                <h2 class="font-bold text-4xl h-min pb-2 text-center">Available coins</h2>
                <div class="row-start-2 h-min flex gap-3 flex-wrap w-auto">
                    @foreach ($currencies as $iso => $name)
                        @php
                            $rate = 1 / \App\Models\ExchangeRate::where('iso_code','=',$iso)->first()->rate;
                        @endphp
                        <div class=" border border-gray-100 shadow-md p-2 rounded-md
             bg-gray-50 h-min w-[48%]">
                            <div class="flex justify-end px-4 pt-4">
                            </div>
                            <div class="flex flex-col items-center pb-6">
                                <img class=" h-24 mb-3 rounded-full shadow-lg"
                                     src="https://coinicons-api.vercel.app/api/icon/{{strtolower($iso)}}"
                                     alt="{{$name}}"/>
                                <h5 class="mb-1 text-xl font-medium text-black">{{$iso}}</h5>
                                <h5 class="mb-1 text-xl font-medium text-black">${{$rate}}</h5>
                                <div class="flex mt-4 md:mt-6">
                                    <button value="{{$iso}}:{{$rate}}"
                                            class="open-button bg-black mt-2 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-xl">
                                        Buy
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div id="active-investments" class="grid pb-7 h-min">
                <h2 class="font-bold text-4xl h-min pb-2 text-center">Active investments</h2>
                <div class="row-start-2 grid gap-3">
                    @foreach($cryptoInvestments as $investment)
                        @if($investment->rate_at_purchase > (1 /\App\Models\ExchangeRate::where('iso_code','=',$investment->currency)->first()->rate *100))
                            @php
                                $color = "text-red-700";
                            @endphp
                        @elseif($investment->rate_at_purchase < (1 /\App\Models\ExchangeRate::where('iso_code','=',$investment->currency)->first()->rate *100))
                            @php
                                $color = "text-green-700";
                            @endphp
                        @else
                            @php
                                $color = "text-black";
                                @endphp
                            @endif
                            <div class="grid grid-cols-auto border border-gray-100 shadow-md p-2 rounded-md
             bg-gray-50 h-min">
                                <img class="justify-center align-middle m-auto h-24  rounded-full shadow-lg col-span-1"
                                     src="https://coinicons-api.vercel.app/api/icon/{{strtolower($investment->currency)}}"
                                     alt="{{$name}}"/>
                                <div class="col-start-2">
                                    <h5 class="text-md font-bold">{{$investment->currency}}</h5>
                                    <p>Amount: {{$investment->amount}}</p>
                                    <p>Bought for: {{ @Money($investment->rate_at_purchase,'USD')}}</p>
                                    <p>Current
                                        value: <span class="{{$color}}">{{ @Money((1 /\App\Models\ExchangeRate::where('iso_code','=',$investment->currency)->first()->rate) * $investment->amount *100,'USD')}}
                                        ({{@Money((1 /\App\Models\ExchangeRate::where('iso_code','=',$investment->currency)->first()->rate)*100,'USD')}}
                                        )</span> </p>
                                    <p>{{$investment->date}}</p>
                                </div>
                                <form action="/sell-crypto" method="post" class="col-start-3 grid grid-rows-3 grid-cols-1 h-min">
                                    @csrf
                                    <label for="from_account" class="h-min" >Sell amount</label>
                                    <input class="pb-2 h-min" name="sell_amount" type="number" step="0.01" min="0"
                                           max="{{$investment->amount}}" placeholder="0.01"
                                           onchange="updatePrice()"/>

                                    <input type="hidden" name="date" value="{{$investment->date}}"/>
                                    <input type="hidden" name="currency" value="{{$investment->currency}}"/>

                                    <input name="from_account" type="hidden" value="Bank: investment"/>

                                    <button type="submit" class="w-16 bg-black mt-2 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-xl">Sell</button>
                                </form>
                            </div>
                            @endforeach
                </div>
            </div>

        </div>

    @endif

    <dialog data-modal class="border border-gray-100 shadow-md p-2
             bg-gray-50 h-min rounded-md">
        <h2 id="coin-name" class="text-center text-xl font-bold pb-2">coin</h2>
        <form action="/buy-crypto" method="post">
            @csrf
            @if(! empty($usdDebitAccounts))
            <label for="from_account">Pay from account:</label><br>

            <select name="from_account" id="from_account" class="form-select mb-4">
                @foreach($usdDebitAccounts as $checkingAccount)
                    <option
                        value="{{$checkingAccount['account_number']}}">  {{@Money($checkingAccount['balance_in_lcm'],$checkingAccount['currency_iso'])}}
                        - {{$checkingAccount['account_number']}}</option>
                @endforeach
            </select>
            <input name="to_account" type="hidden" value="Bank: investment"/>
            <input name="currency" id="currency-selected" type="hidden" value=""/>
            <h4 id="price"></h4>
            <input id="buy-amount" name="amount" type="number" step="0.01" min="0" placeholder="0.01"
                   onchange="updatePrice()"/>
            <button type="submit" class="bg-black mt-2 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-md">Buy</button>
            @else
                <h3 class="text-center text-xl font-bold">You need a USD account to start investing in crypto.<br> Sorry for the inconvenience!</h3>
            @endif
        </form>

        <button class="close-button text-md absolute top-1 font-extrabold hover:text-gray-700">X</button>
    </dialog>
    <script>
        const openButton = document.querySelectorAll('.open-button');
        const closeButton = document.querySelectorAll('.close-button');
        const modal = document.querySelector("[data-modal]");
        const modalCoinTitle = document.querySelector("#coin-name");
        let rate;

        openButton.forEach(open => {
            open.addEventListener("click", () => {
                modal.showModal()
                let data = open.value.split(":");
                modalCoinTitle.textContent = data[0];
                rate = data[1];
                document.querySelector("#currency-selected").value = data[0];
            })
        })

        closeButton.forEach(close => {
            close.addEventListener("click", () => {
                modal.close()
            })
        })


        const priceText = document.querySelector("#price");
        const buyAmount = document.querySelector("#buy-amount");

        function updatePrice() {
            priceText.textContent = "Total: " + Math.ceil((parseFloat(buyAmount.value) * parseFloat(rate)) * 100) / 100;
        }
    </script>
</x-app-layout>
