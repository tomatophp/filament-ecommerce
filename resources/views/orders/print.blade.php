<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>{{trans('filament-ecommerce::messages.orders.print.order')}}: {{$model->uuid}}</title>

    <style>
        body, html {
            margin: 0 !important;
            padding: 0 !important;
        }
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000000;
            padding: 5px;
        }
    </style>
</head>
<body style="text-align: center" onload="window.print()">
<div dir="rtl"  style="margin-left: auto; margin-right:auto; display:block">
    <div>
        <img style="width: 100px" src="{{$model->company?->getFirstMediaUrl('logo')}}"></div><br>
    <br>
    <h3 style="border: 1px solid #000000; text-align: center; padding: 5px;">{{trans('filament-ecommerce::messages.orders.print.order')}} {{$model->uuid}}</h3>
    <br>
    <p style="margin-top: -15px;">{{trans('filament-ecommerce::messages.orders.print.printed_at')}}: {{\Carbon\Carbon::now()->format('d/m/Y g:i A')}}</p>
    <p style="margin-top: -15px;">{{trans('filament-ecommerce::messages.orders.print.casher_id')}}: {{ $model->user?->name }}
    </p>
    <table border="0" style="width: 100%">
        <tbody>
        @if($model->name || $model->phone)
            <tr>
                <th>{{trans('filament-ecommerce::messages.orders.print.billed_to')}}</th>
                <td>
                    @if($model->name)<span>{{$model->name}}</span>@endif
                    @if($model->phone)<br><span>{{$model->phone}}</span>@endif
                    @if($model->address)<br><span>{{$model->address}}</span>@endif
                    @if($model->city)<br><span>{{$model->city?->name}}</span>@endif
                    @if($model->area)<br><span>{{$model->area?->name}}</span>@endif
                </td>
            </tr>
        @endif
        </tbody>
    </table><br>
    <table style="width: 100%">
        <thead>
        <tr>
            <th>{{trans('filament-ecommerce::messages.orders.print.item')}}</th>
            <th>{{trans('filament-ecommerce::messages.orders.print.qty')}}</th>
            <th>{{trans('filament-ecommerce::messages.orders.print.total')}}</th>

        </tr>
        </thead>
        <tbody>
        @php $Count = 1 @endphp
        @foreach($model->ordersItems ?? [] as $item)
            <tr>
                <td>
                    <b>{{$item->product->name}} - [{{$item->product->sku}}]
{{--                        @if($item->options)--}}
{{--                            @php $counter = 0; @endphp--}}
{{--                            @foreach($item->options as $op)--}}
{{--                                {{str($op)->title}}--}}
{{--                                @if($counter+1 != sizeof($item->options))--}}
{{--                                    {{' - '}}--}}
{{--                                @endif--}}
{{--                                @php $counter++; @endphp--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
                    </b>
                </td>
                <td>
                    {{$item->qty}}
                </td>
                <td>
                    {!! dollar($item->total) !!}
                </td>
            </tr>
            @php $Count++ @endphp
        @endforeach
        </tbody>
    </table><br>
    <table border="0" style="width: 100%">
        <tbody>
        <tr>
            <th>{{trans('filament-ecommerce::messages.orders.print.sub_total')}}</th>
            <td>{!! dollar(($model->total+$model->discount)-($model->shipping+$model->vat)) !!}</td>
        </tr>
        @if($model->shipping)
            <tr>
                <th>{{trans('filament-ecommerce::messages.orders.print.shipping')}}</th>
                <td>{!! dollar($model->shipping) !!}</td>
            </tr>
        @endif
        @if($model->vat)
            <tr>
                <th>{{trans('filament-ecommerce::messages.orders.print.vat')}}</th>
                <td>{!! dollar($model->vat) !!}</td>
            </tr>
        @endif
        @if($model->discount)
            <tr>
                <th>{{trans('filament-ecommerce::messages.orders.print.discount')}}</th>
                <td>
                    {!! dollar($model->discount) !!}
                </td>
            </tr>
        @endif
        <tr>
            <th>{{trans('filament-ecommerce::messages.orders.print.total')}}</th>
            <td>
                <b>
                    {!! dollar($model->total) !!}
                </b>
            </td>
        </tr>

        @if($model->notes)
            <tr>
                <th>{{trans('filament-ecommerce::messages.orders.print.notes')}}</th>
                <td>{{$model->notes}}</td>
            </tr>
        @endif

        </tbody>
    </table>
    <br>

    <div>{{$model->branch?->name}}</div>
    <div>{{$model->branch?->address}}</div>
    <div><b>{{$model->branch?->phone}}</b></div>
    <hr style="width: 100%">
    <img src="data:image/png;base64,{{\DNS1D::getBarcodePNG((string)$model->uuid, 'C128',1,44,array(1,1,1), true)}}" alt="barcode"  />

</div>
</body>
</html>
