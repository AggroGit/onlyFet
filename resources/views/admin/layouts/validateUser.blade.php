@extends('admin.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Validación de {{$model->name}} ({{$model->nickname}})</h1>
    @if($model->verified)
      <div class="alert alert-success">
        <h4>Usuario ya validado</h4>
      </div>
    @else
    <div class="alert alert-warning">
      <h4>Usuario sin validar</h4>
    </div>
    @endif

    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            <a href="{{url('admin/'.$tabletate['singular'].'/edit/'.$model->id)}}">
              <button type="button" class="btn btn-warning btn-sm">
                Editar Usuario
              </button>
            </a>
        </div>
        <div class="card-body">
          <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Fecha Nacimiento</th>
                    <th>Nickname</th>
                    <th>Email</th>
                    <th>Cuenta bancaria (IBAN)</th>
                  </tr>
                </thead>
                <!--  -->
                <tbody>
                  <tr>
                    <td>{{$model->name}}</td>
                    <td>{{$model->birthday}}</td>
                    <td>{{$model->nickname}}</td>
                    <td>{{$model->email}}</td>
                    <td>{{$model->bank_account}}</td>
                  </tr>
                </tbody>
              </table>
          </div>
        </div>
    </div>
    <!--  -->
    <div class="card mt-5">
      <div class="card-header">
        <h2>Documentos</h2>

      </div>
      <div class="card-body">
        <div class="row mt-5">
          @foreach($model->documents as $document)
            <div class="col-md-4 text-center mt-3">
                <img class="img-fluid" src="{{$document->sizes['Full']}}" alt="">
                <a target="_blank" href="{{$document->sizes['Full']}}">
                  <button type="button" class="btn btn-info mt-3" name="button">Ver imágen</button>
                </a>
            </div>
          @endforeach

        </div>
      </div>
    </div>
    <!-- Validaciones  -->
    @if($model->wantToBeInfluencer and $model->verified == false)
      <div class="card mt-4">
        <div class="card-header">
          Opciones
        </div>

        <div class="card-body">
          <div class="row justify-content-center text-center">
            <div class="col-md-6">
              <a target="_blank" href="{{$stripe_url}}">
                <button type="button" class="btn btn-success mt-3" name="button">Hacer Stripe y Validar</button>
              </a>
            </div>
            <div class="col-md-6">
              <a href="#rechazar" data-toggle="collapse">
                <button type="button" class="btn btn-warning mt-3" name="button">No Validar</button>
              </a>

              <div id="rechazar" class="collapse mt-2">
                <form class="" action="{{url('/admin/user/validation/'.$model->id.'/refuse')}}" method="post">
                  @csrf 
                  <div class="form-group">
                    <label for="comment">Motivo de rechazo: :</label>
                    <span>Se le enviará un correo al usuario</span>
                    <textarea name="reason" required class="form-control" rows="5" id="comment"></textarea>
                    <button type="submit" class="btn btn-lg btn-warning mt-3" name="button">Enviar y rechazar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    @endif
</div>
@endsection
