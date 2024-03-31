<!doctype html>
<html lang="es">

<head>
    <title>Orden de Compra</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> --}}
    <style>
        p.cell {
            margin-top: 10px;
            margin-left: 15px;
            margin-bottom: 0px;
            padding-bottom: 3px;
            padding-top: 0px;
            border: solid 1px;
            width: 20px;
            display: inline-block;
            height: 20px;
            text-align: center;
        }

        p.name {
            border: none;
        }

        p.past {
            margin-top: 0;
            margin-bottom: 1rem;
            width: 200px;
            height: 250px;
        }

        p.firma {
            margin-top: 0;
            margin-bottom: 1rem;
            border: solid 1px;
            width: 100px;
            height: 150px;
            text-align: center;
        }

        p.status {
            margin-left: 0px;
            margin-bottom: 2px;
        }

        .align_left {
            text-align: left;
        }

        .align_right {
            text-align: right;
        }

        #circle {
            background: lightblue;
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        .container_1 {
            margin: 0.4% 0.8%;
            font-size: 0.50rem;
        }

        .stroke {
            text-align: center;
            color: #ffffff;

            text-shadow: -1px -1px 1px #000, 1px 1px 1px #000, -1px 1px 1px #000, 1px -1px 1px #000;
            -webkit-text-fill-color: #ffffff;
            -webkit-text-stroke: 2px black;
        }

        small {
            font-size: 0.6rem;
        }

        h6 {
            font-size: 10px !important;
        }

    </style>
</head>

<body>
    <div class="container_1" >
        <table  class="table table-borderless table-sm" style="padding:0px; margin:0px; border-collapse: collapse;">
            <tbody>
                <tr class="text-sm" >
                    <td style="padding:5px;  border-collapse: collapse;" colspan="1">
                        {{-- <img width="150" src="{{ public_path('img/logo.png') }}"> --}}
                    </td>
                    <td style="padding:0px;  border-collapse: collapse;" colspan="20">
                        <h6>
                            {{-- {{ strtoupper($purchase_orders?->workshop?->sede?->business?->description) ?? strtoupper($purchase_orders->warehouse_workshop->workshop->sede->business->description) }} --}}
                        </h6>
                        <h6>
                            {{-- R.U.C:{{ strtoupper($purchase_orders?->workshop?->sede?->business?->ruc) ?? strtoupper($purchase_orders->warehouse_workshop->workshop->sede->business->ruc) }}</h6> --}}
                        <h6>
                            {{-- {{ strtoupper($purchase_orders?->workshop?->sede?->business?->direction) ?? strtoupper($purchase_orders->warehouse_workshop->workshop->sede->business->direction) }} --}}
                        </h6>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    {{-- <h5> Orden de Compra {{ strtoupper($purchase_orders->code) }}</h5> --}}
    <div class="container_1">
        <table class="table table-borderless table-sm">
            <tbody>
                <tr class="text-sm">
                    <td colspan="10">
                        {{-- <small>Proveedor: {{ strtoupper($purchase_orders->provider->description) }}</small> --}}
                    </td>
                    <td colspan="5">
                        {{-- <small>Documento: {{ strtoupper($purchase_orders->provider->document) }}</small> --}}
                    </td>
                </tr>
                <tr class="text-sm">
                    <td colspan="15">
                        {{-- <small>Dirección: {{ ($purchase_orders?->workshop?->address) ?? ($purchase_orders?->warehouse_workshop?->address) }} </small> --}}
                    </td>
                </tr>
                <tr class="text-sm">
                    <td colspan="10">
                        {{-- <small>Representante de compra: {{ strtoupper($purchase_orders->user->name) }}</small> --}}
                    </td>
                    <td colspan="5">
                        {{-- <small>Fecha orden:  {{ date("d/m/Y", strtotime($purchase_orders->created_at)) }}</small> --}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class="container_1">
        <table class="table table-bordered table-sm">
            <tbody>
                <tr class="text-sm">
                    <td>SKU</td>
                    <td>Descripción</td>
                    <td>Cantidad</td>
                    <td>Importe</td>
                    <td>Monto</td>
                </tr>
                {{-- @if (!empty($purchase_orders->purchase_order_items))
                    @foreach ($purchase_orders->purchase_order_items as $item)
                    <tr>
                        <td>{{$item->item->code}}</td>
                        <td>{{$item->item->description}}</td>
                        <td>{{$item->amount}}</td>
                        <td>{{$purchase_orders->currency->symbol}} {{$item->price}}</td>
                        <td>{{$purchase_orders->currency->symbol}} {{ ($item->amount * $item->price) }}</td>
                    </tr>
                    @endforeach
                @endif
                @if (!empty($purchase_orders->purchase_order_services))
                    @foreach ($purchase_orders->purchase_order_services as $item)
                        <tr>
                            <td>{{$item->workshop_operation?->code}}</td>
                            <td>{{$item->workshop_operation?->description}}</td>
                            <td>{{$item->amount}}</td>
                            <td>{{$purchase_orders->currency->symbol}} {{$item->price}}</td>
                            <td>{{$purchase_orders->currency->symbol}} {{ ($item->amount * $item->price) }}</td>
                        </tr>
                    @endforeach
                @endif --}}
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Subtotal:</td>
                    {{-- <td>{{$purchase_orders->currency->symbol}} {{$purchase_orders->subtotal}}</td> --}}
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Impuestos:</td>
                    {{-- <td>{{$purchase_orders->currency->symbol}} {{$purchase_orders->total_igv}}</td> --}}
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total:</td>
                    {{-- <td>{{$purchase_orders->currency->symbol}} {{($purchase_orders->subtotal + $purchase_orders->total_igv)}}</td> --}}
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>