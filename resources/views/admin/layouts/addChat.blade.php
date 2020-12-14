@extends('admin.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">{{$tabletate['name'] ?? ''}}</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
        </div>
        <div class="card-body">
          <div class="card-body">
              <form method="POST" action="{{url('admin/'.$tabletate['singular'].'/add')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$tabletate['data']['id']?? false}}">
                  <div class="form-row">
                    @foreach($tabletate['headers'] as $key => $header)
                      <div class="col-md-6">
                          <div class="form-group">
                            <!-- SI ES UN CAMPO DE TEXTO  -->
                            @if(!is_array($header)?? false)
                              @if(Schema::hasColumn($model->getTable(), $header))
                                @if($header !== "expires_at")
                                <label class="small mb-1" for="{{$key}}">{{$key}}</label>
                                <input class="form-control py-4" id="inputLastName" type="text" value="{{$tabletate['data'][$header]?? ''}}" name="{{$header}}" placeholder="{{$key}}" />
                                @else
                                <label class="small mb-1" for="{{$key}}">{{$key}}</label>
                                <input class="form-control py-4" id="inputLastName" type="date" value="{{$tabletate['data'][$header]?? ''}}" name="{{$header}}" placeholder="{{$key}}" />
                                @endif
                              @endif
                            @else
                            <!-- Si no es un campo de texto -->



                            @endif
                          </div>
                      </div>
                    @endforeach
                    <br>
                    <div class="col-md-6">
                      Usuarios :
                      @forEach($model->users as $user)
                      {{$user->name}} ||
                      @endforeach

                    </div>


                    @if($tabletate['options']['image']?? false)
                    @if(Schema::hasColumn($model->getTable(), 'image_id'))
                        <div class="col-md-4">
                            <div class="form-group">
                              <label class="small mb-1" for="image">Imagen</label>
                              <picture-input
                                name="image"
                                ref="image"
                                width="200"
                                height="200"
                                margin="16"
                                accept="image/jpeg,image/png,image/jpg"
                                size="10"
                                @if($tabletate['data']['image']?? $tabletate['data']['images'][0]?? false)
                                prefill="{{$tabletate['data']['image']['sizes']['Big']?? $tabletate['data']['images'][0]['sizes']['Big']?? $tabletate['data']['images'][0]?? ''}}"
                                @endif
                                buttonClass="btn"
                                >
                              </picture-input>
                            </div>
                        </div>
                      @else

                      @endif
                    @endif

                  </div>

                  <br>
                  <div class=" row">
                    <h5>Mensajes</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                  <th>Usuario </th>
                                  <th>Mensaje</th>
                                  <th>Fecha</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($model->messages as $message)
                                <tr>
                                  <td>
                                    <a href="{{url('admin/user/edit/'.$message->user->id)}}">
                                      {{$message->user->name}}
                                    </a>
                                  </td>
                                  <td>
                                    {{$message->message}}
                                  </td>
                                  <td>{{$message->created_at}}</td>
                                </tr>
                              @endforeach
                            </table>

                          </div>
                  </div>






                  <div class="d-none d-md-inline-block  ml-auto mr-0 mr-md-3 my-2 my-md-0">
                      <button type="submit" class="btn btn-success btn-sm">
                        Actualizar
                      </button>
                  </div>
              </form>
          </div>
        </div>
    </div>
</div>
@endsection
