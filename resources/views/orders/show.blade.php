<div class="fi-ecommerce-order-summary">
    <x-filament::section>
        <div class="flex justify-between xl:gap-60 lg:gap-48 md:gap-16 sm:gap-8 sm:flex-row flex-col gap-4">
            <div class="w-full">
                @if($record->company)
                <div class=" my-4">
                    <img src="{{$record->company->getFirstMediaUrl('logo')}}" alt="{{$record->company->name}}" class="h-12 ">
                </div>
                <div class="flex flex-col">
                    <div>
                        {{trans('filament-ecommerce::messages.orders.print.from')}}
                    </div>
                    <div class="text-lg font-bold mt-2">
                        {{$record->company->name}}
                    </div>
                    <div class="text-sm">
                        {{$record->company->ceo}}
                    </div>
                    <div class="text-sm">
                        {{$record->company->address}}
                    </div>
                    <div class="text-sm">
                        {{$record->company->zip}} {{$record->company->city}}
                    </div>
                    <div class="text-sm">
                        {{$record->company->country?->name}}
                    </div>
                </div>
                @endif
                <div class="mt-4">
                    <div>
                        {{trans('filament-ecommerce::messages.orders.print.to')}}
                    </div>
                    <div class="mt-4">
                        <div class="text-lg font-bold mt-2">
                            {{$record->account?->name}}
                        </div>
                        <div class="text-sm">
                            {{$record->account?->email}}
                        </div>
                        <div class="text-sm">
                            {{$record->account?->phone}}
                        </div>
                        <div class="text-sm">
                            {{$record->address}}
                        </div>
                        <div class="text-sm">
                            {{$record->country?->name}} , {{$record->city?->name}}, {{$record->area?->name}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-4 w-full">
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.order')}}
                    </div>
                    <div>
                        {{$record->uuid}}
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.issue_date')}}
                    </div>
                    <div>
                        {{$record->created_at->toDateString()}}
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.due_date')}}
                    </div>
                    <div>
                        {{$record->created_at->toDateString()}}
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.status')}}
                    </div>
                    <div>
                        {{str($record->status)->upper()}}
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.source')}}
                    </div>
                    <div class="font-bold text-primary-500">
                        {{str($record->source)->upper()}}
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="grid grid-cols-12 gap-4 border-b dark:border-gray-700 py-4 my-4 font-bold">
                <div class="col-span-4 ">
                    {{trans('filament-ecommerce::messages.orders.print.item')}}
                </div>
                <div>
                    {{trans('filament-ecommerce::messages.orders.print.price')}}
                </div>
                <div>
                    {{trans('filament-ecommerce::messages.orders.print.discount')}}
                </div>
                <div class="col-span-2">
                    {{trans('filament-ecommerce::messages.orders.print.vat')}}
                </div>
                <div>
                    {{trans('filament-ecommerce::messages.orders.print.qty')}}
                </div>
                <div>
                    {{trans('filament-ecommerce::messages.orders.print.total')}}
                </div>
            </div>
            <div class="flex flex-col gap-4">
                @foreach($record->ordersItems as $item)
                    <div class="grid grid-cols-12 gap-4 border-b dark:border-gray-700 py-4">
                        <div class="col-span-4 flex  flex-col justify-start">
                            <div>
                                {{ $item->product?->name }}
                            </div>
{{--                            <div class="text-gray-400">--}}
{{--                                @foreach($item->options as $label=>$options)--}}
{{--                                    <span>{{  str($label)->ucfirst() }}</span> : {{$options}} <br>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
                        </div>
                        <div>
                            {!! dollar($item->price ?? 0) !!}
                        </div>
                        <div>
                            {!! dollar($item->discount ?? 0) !!}
                        </div>
                        <div class="col-span-2">
                            {!! dollar($item->vat ?? 0) !!}
                        </div>
                        <div>
                            {{$item->qty}}
                        </div>
                        <div>
                            {!! dollar($item->total) !!}
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="flex flex-col gap-4 mt-4">
                <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700">
                    <div class="font-bold">
                        {{trans('filament-ecommerce::messages.orders.print.sub_total')}}
                    </div>
                    <div>
                        {!! dollar(($record->total + $record->discount) - ($record->vat + $record->shipping)) !!}
                    </div>
                </div>
                @if($record->vat)
                <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700 text-success-500">
                    <div class="font-bold">
                        {{trans('filament-ecommerce::messages.orders.print.vat')}}
                    </div>
                    <div>
                        {!! dollar($record->vat ) !!}
                    </div>
                </div>
                @endif
                @if($record->shipping)
                <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700 text-success-500">
                    <div class="font-bold">
                        {{trans('filament-ecommerce::messages.orders.print.shipping')}}
                    </div>
                    <div>
                        {!! dollar($record->shipping ) !!}
                    </div>
                </div>
                @endif
                @if($record->coupon)
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700 text-danger-500">
                        <div class="font-bold">
                            {{trans('filament-ecommerce::messages.orders.print.coupon')}} [{{ $record->coupon->code }}]
                        </div>
                        <div>
                            {!! dollar($record->coupon->discount($record->total) ) !!}
                        </div>
                    </div>
                @endif
                @if($record->discount)
                <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700 text-danger-500">
                    <div class="font-bold">
                        {{trans('filament-ecommerce::messages.orders.print.discount')}}
                    </div>
                    <div>
                        @if($record->coupon)
                            {!! dollar($record->discount - $record->coupon->discount($record->total)) !!}
                        @else
                            {!! dollar($record->discount ) !!}
                        @endif
                    </div>
                </div>
                @endif
                <div class="flex justify-between gap-4 py-4 text-primary-500">
                    <div class="font-bold">
                        {{trans('filament-ecommerce::messages.orders.print.total')}}
                    </div>
                    <div>
                        {!! dollar($record->total) !!}
                    </div>
                </div>
                @if($record->notes)
                    <div class="flex flex-col gap-4 py-4 text-gray-800">
                        <div class="font-bold">
                            {{trans('filament-ecommerce::messages.orders.print.notes')}}
                        </div>
                        <div>
                            {{ $record->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</div>
