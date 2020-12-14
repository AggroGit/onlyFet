@extends('admin.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Pedido 0000{{$purchase->id}}</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Pedido 0000{{$purchase->id}}
        </div>
        <div class="card-body">
          <div class="card-body">
              <form method="POST" action="" enctype="multipart/form-data">
                <div class="container">
                  <div class="col-md-12">
                    <div class="container">
                      <div class="row">
                        <div class="col-md-6">
                          <strong>Pedido numero</strong>: 0000{{$purchase->id}}
                        </div>
                        <div class="col-md-6">
                          <strong>Realizado el</strong>: {{$purchase->created_at}}
                        </div>
                        <div class="col-md-6">
                          <strong>Cliente</strong>: {{$purchase->user->name}}
                        </div>
                        <div class="col-md-6">
                          <strong>Dirección</strong>: {{$purchase->user->direction}}
                        </div>
                        <div class="col-md-6">
                          <strong>Estado pedido</strong>: {{$purchase->status}}
                        </div>
                        <div class="col-md-6">
                          <strong>Cobrado Cliente</strong>: {{$purchase->total_price}}
                        </div>
                        <div class="col-md-6">
                          <strong>Comisión Stripe</strong>: {{$purchase->stripe_commisions}}
                        </div>
                        <div class="col-md-6">
                          <strong>Gastos de envío</strong>: {{$purchase->sending_price}}
                        </div>
                        <div class="col-md-6">
                          <strong>Total plataforma ({{$purchase->total_price}} - {{$purchase->stripe_commisions}})</strong>: {{$purchase->ourPrice}}
                        </div>
                        <div class="col-md-6 down">
                          @if($purchase->status == "pending")
                            <a href="{{url('/prof/purchase/'.$purchase->id.'/deliver')}}">
                              <button type="button" class="btn btn-primary" name="button">Entregar</button>
                            </a>
                          @endif

                        </div>
                      </div>

                      <div class="row down">
                        <h3>Productos</h3>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Nombre</th>
                              <th>Comentario</th>
                              <th>Cantidad</th>
                              <th></th>
                              <th></th>

                              <th class="text-right">Precio</th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($purchase->orders as $order)
                              <tr>
                                <td>{{$order->product->name}}</td>
                                <td>{{$order->description}}</td>
                                <td>{{$order->quantity}} x {{$order->howmuch}}</td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{$order->price}} €</td>
                              </tr>
                            @endforeach


                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="text-right">Total productos: {{$purchase->orders()->sum('price')}}€</td>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="text-right">Gastos de envío: + {{$purchase->sending_price}}€</td>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="text-right">Stripe: - {{$purchase->stripe_commisions}}€</td>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td class="text-right"><strong>Total resultante = {{$purchase->total_price - $purchase->stripe_commisions}}€</strong></td>
                            </tr>
                          </tbody>
                        </table>

                      </div>


                    </div>
                  </div>
                </div>
              </form>
          </div>
        </div>
    </div>
</div>
@endsection
