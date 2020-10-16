@extends('admin.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">{{$tabletate['name'] ?? ''}}</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Añadir {{$tabletate['name'] ?? ''}}
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
                            @if(!is_array($header)?? false)
                              @if(Schema::hasColumn($model->getTable(), $header))
                              <label class="small mb-1" for="{{$key}}">{{$key}}</label>
                                <input class="form-control py-4" id="inputLastName" type="text" value="{{$tabletate['data'][$header]?? ''}}" name="{{$header}}" placeholder="{{$key}}" />
                              @endif
                            @else
                            <label class="small mb-1" for="{{$key}}">{{$key}}</label>
                              <select class="form-control" name="{{$header['model_name'].'_id'}}" id="sel1">
                                  <option></option>
                                  @foreach($header['select'] as $select)
                                    <option
                                    @if ($tabletate['data']?? false and $tabletate['data'][$header['model_name'].'_id']==$select['id'])
                                      selected
                                    @endif
                                     value="{{$select['id']}}">{{$select[$header['show']]}}
                                   </option>
                                  @endforeach
                              </select>
                            @endif
                          </div>
                      </div>
                    @endforeach

                    @if($tabletate['options']['image']?? false)
                      <div class="col-md-6">
                          <div class="form-group">
                            <label class="small mb-1" for="image">Imagen</label>
                            <picture-input
                              name="image"
                              ref="image"
                              width="200"
                              height="200"
                              margin="16"
                              accept="image/jpeg,image/png,image/JPG,image/jpg"
                              size="10"
                              @if($tabletate['data']['image']?? false)
                              prefill="http://127.0.0.1:8000/storage/category/ed59976b500ca15e3d1148fedd84eb38/100.JPG"
                              @endif
                              buttonClass="btn"
                              >
                            </picture-input>
                          </div>
                      </div>
                    @endif

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
