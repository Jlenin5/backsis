<!doctype html>
<html lang="es">

<head>
    <title>Orden de Compra</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
                        <img width="100" src="https://sismova.tech/backsis/public/images/company/{{ $purchase_order->companies->comImage }}">
                    </td>
                    <td style="padding:0px;  border-collapse: collapse;" colspan="20">
                        <h4> Orden de Compra {{ strtoupper($purchase_order->serialNumber->snSerie) }}-{{ strtoupper($purchase_order->puorNumber) }}</h4>
                        <h6>
                            R.U.C:{{ strtoupper($purchase_order->companies->comRUC) }}
                        </h6>
                        <h6>
                            E-mail: {{ strtoupper($purchase_order->companies->comEmail) }}
                        </h6>
                        <h6>
                            Dirección: {{ strtoupper($purchase_order->companies->comAddress) }}
                        </h6>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="container_1">
        <table class="table table-borderless table-sm">
            <tbody>
                <tr class="text-sm">
                    <td colspan="10">
                        <small>Proveedor: {{ strtoupper($purchase_order->suppliers->suppCompanyName) }}</small>
                    </td>
                    <td colspan="5">
                        <small>{{ strtoupper($purchase_order->suppliers->documentType->doctAbbreviation) }}: {{ $purchase_order->suppliers->suppDocument }}</small>
                    </td>
                </tr>
                <tr class="text-sm">
                    <td colspan="15">
                        <small>Dirección: {{ ($purchase_order->suppliers->suppAddress) }} </small>
                    </td>
                </tr>
                <tr class="text-sm">
                    <td colspan="10">
                      <small>Representante de compra: {{ strtoupper($purchase_order->users->employees->empFirstName) }} 
                        {{ strtoupper($purchase_order->users->employees->empSecondName ?? '') }} 
                        {{ strtoupper($purchase_order->users->employees->empSurname) }} 
                        {{ strtoupper($purchase_order->users->employees->empSecondSurname) }}</small>
                    </td>
                    <td colspan="5">
                        <small>Fecha orden:  {{ date("d/m/Y", strtotime($purchase_order->puorStartDate)) }}</small>
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
                    {{-- <td>SKU</td> --}}
                    <td>Precio</td>
                    <td>Cantidad</td>
                    <td>Descuento</td>
                    <td>Importe</td>
                    <td>Monto</td>
                </tr>
                @if (!empty($purchase_order->purchaseOrderDetails))
                    @foreach ($purchase_order->purchaseOrderDetails as $item)
                    <tr>
                        {{-- <td>{{$item->item->code}}</td> --}}
                        <td>{{$item->podPrice}}</td>
                        <td>{{$item->podQuantity}}</td>
                        <td>{{$item->podDiscount}}</td>
                        <td>{{$item->podTax}}</td>
                        <td>{{$item->podTotal }}</td>
                    </tr>
                    @endforeach
                @endif
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Subtotal:</td>
                    <td>{{$purchase_order->puorSubtotal}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Impuestos:</td>
                    {{-- <td>{{$purchase_order->currency->symbol}} {{$purchase_order->total_igv}}</td> --}}
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total:</td>
                    <td>{{$purchase_order->puorTotal}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>