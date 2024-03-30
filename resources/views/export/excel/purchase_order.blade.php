<table>
  <thead>
      <tr>
          <th>Código</th>
          <th>Empresa</th>
          <th>Sede</th>
          <th>Taller</th>
          <th>Documento</th>
          <th>Cliente</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Placa</th>
          <th>Vin</th>
          <th>Año</th>
          <th>Color</th>
          <th>Kilometraje</th>
          <th>Tipo de pago</th>
          <th>Financiamiento</th>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Mecánico</th>
          <th>Usuario</th>
          <th>Estado Pago</th>
          <th>Estado OT</th>
          <th>Canal</th>
          <th>Subcanal</th>
          <th>Proveedor</th>
          <th>% Cías de Seguro</th>
          <th>Moneda</th>
          <th>Precio Total</th>
          <th>Monto Ajuste</th>
          <th>Costo</th>
          <th>Precio Repuesto</th>
          <th>Precio Operaciones</th>
          <th>Precio Cliente</th>
          <th>Precio Proveedor</th>
          <th>Comentario Cita</th>
          <th>Comentario Cotización</th>
          <th>Comentario OT</th>
          <th>Comentario Entrega</th>
      </tr>
  </thead>
  <tbody>
      @foreach ($purchase_orders as $puor)
          <tr>
              <td>{{ $puor->puorNumber }}</td>
              {{-- <td>{{ $puor->workshop_quotation->appointment->workshop->sede->business->description }}</td>
              <td>{{ $puor->workshop_quotation->appointment->workshop->sede->description }}</td>
              <td>{{ $puor->workshop_quotation->appointment->workshop->description}}</td>
              <td>{{ $puor->customer->document }}</td>
              <td>{{ $puor->customer->name_complete }}</td>
              <td>{{ $puor->customer->phone }}</td>
              <td>{{ $puor->customer->email }}</td>
              <td>{{ $puor->workshop_quotation->appointment?->plate?->mark?->description }}</td>
              <td>{{ $puor->workshop_quotation->appointment?->plate?->markModel?->description}}</td>
              <td>{{ $puor->workshop_quotation->appointment?->plate?->plate }}</td>
              <td>{{ $puor->workshop_quotation->appointment?->plate?->vin }}</td>
              <td>{{ $puor->workshop_quotation->appointment?->plate?->year?->description }}</td>
              <td>{{ $puor->workshop_quotation->appointment?->plate?->color }}</td>
              <td>{{ $puor->workshop_quotation->appointment?->millage }}</td>
              <td>{{ $puor->type_payment?->description }}</td>
              <td>{{ $puor->bank_financing?->description }}</td>
              <td>{{ $puor->date}}</td>
              <td>{{ $puor->hour}}</td>
              <td>{{ $puor->lastest_mechanic?->name}}</td>
              <td>{{ $puor->user?->name}}</td>
              @if($puor->payment_status)
              <td>{{ $puor->payment_status}}</td>
              @else
              <td>pendiente</td>
              @endif
              <td>{{ $puor->status_order}}</td>
              <td>{{ $puor->workshop_quotation?->workshop_channel?->description }}</td>
              <td>{{ $puor->workshop_quotation?->workshop_subchannel?->description }}</td>
              <td>{{ $puor->workshop_quotation->provider ? $puor->workshop_quotation->provider->description:'' }}</td>
              <td>{{ $puor->workshop_quotation->safe_percentage }}</td>
              <td>{{ $puor->workshop_quotation->currency->code }}</td>
              <td>{{ $puor->workshop_quotation->quotations_approved }}</td>
              <td>{{ $puor->workshop_quotation->adjustment_amount }}</td>
              <td>{{ $puor->workshop_quotation->getCost() }}</td>
              <td>{{ ($puor->workshop_quotation->subtotal_item * 1.18) }}</td>
              <td>{{ ($puor->workshop_quotation->subtotal_ope * 1.18) }}</td>
              @if( $puor->workshop_quotation->workshop_channel->assumed_by =='both')
              <td>{{$puor->workshop_quotation->quotations_approved*(1-(((int)$puor->workshop_quotation->safe_percentage)/100))}}</td>
              <td>{{$puor->workshop_quotation->quotations_approved*(((int)$puor->workshop_quotation->safe_percentage)/100)}}</td>
              @elseif ($puor->workshop_quotation->workshop_channel->assumed_by =='provider')
              <td></td>
              <td>{{$puor->workshop_quotation->quotations_approved}}</td>
              @elseif ($puor->workshop_quotation->workshop_channel->assumed_by =='customer')
              <td>{{$puor->workshop_quotation->quotations_approved}}</td>
              <td></td>
              @endif
              <td>{{ $puor?->workshop_quotation?->appointment?->description }}</td>
              <td>{{ $puor?->workshop_quotation?->description }}</td>
              <td>{{ $puor?->comment }}</td>
              <td>{{ $puor?->puor_delivery?->comment }}</td> --}}
          </tr>
      @endforeach
  </tbody>
</table>
