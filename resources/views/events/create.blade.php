@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center py-4">
    <div class="col-md-8">
      <div class="card">
        <h2 class="card-header text-white bg-primary">Ajout d'un événement</h2>
        <div class="card-body">
          {!! Form::open(['route' => 'event.store', 'files' => 'true']) !!}
            <div class="form-group">
              {!! Form::label('title', 'Titre') !!}
              {!! Form::text('title', null, ['class' => 'form-control' .($errors->has('title')? ' is-invalid' : ''), 'required' => 'true']) !!}

              <div class="invalid-feedback">
                {!! $errors->first('title', ':message') !!}
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('date_start', 'Date du début') !!}
                  {!! Form::date('date_start', Carbon\Carbon::now(), ['class' => 'form-control' .($errors->has('date_start')? ' is-invalid' : ''), 'min'=>Carbon\Carbon::now()->toDateString(), 'required' => 'true']) !!}

                  <div class="invalid-feedback">
                    {!! $errors->first('date_start', ':message') !!}
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('date_end', 'Date de fin') !!}
                  {!! Form::date('date_end', Carbon\Carbon::now(), ['class' => 'form-control' .($errors->has('date_end')? ' is-invalid' : ''), 'min'=>Carbon\Carbon::now()->toDateString(),  'required' => 'true']) !!}

                  <div class="invalid-feedback">
                    {!! $errors->first('date_end', ':message') !!}
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('time_start', 'Heure du début') !!}
                  {!! Form::time('time_start', null, ['class' => 'form-control' .($errors->has('time_start')? ' is-invalid' : ''), 'placeholder' => 'hh:mm', 'required' => 'true']) !!}

                  <div class="invalid-feedback">
                    {!! $errors->first('time_start', ':message') !!}
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('time_end', 'Heure de la fin') !!}
                  {!! Form::time('time_end', null, ['class' => 'form-control' .($errors->has('time_end')? ' is-invalid' : ''), 'placeholder' => 'hh:mm', 'required' => 'true']) !!}

                  <div class="invalid-feedback">
                    {!! $errors->first('time_end', ':message') !!}
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              {{ Form::label('place', 'Lieu')}}
              {{ Form::text('place', null, ['class' => 'form-control' .($errors->has('place')? 'is-invalid' : ''), 'required' => 'true'])}}
              {{ Form::hidden('place_id') }}
              {{ Form::hidden('place_verification') }}

              <div class="invalid-feedback">
                {!! $errors->first('place', ':message') !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('content', 'Présentation') !!}
              {!! Form::textarea('content', null, ['id' => 'content', 'class' => 'form-control' .($errors->has('content')? ' is-invalid' : '')]) !!}

              <div class="invalid-feedback">
                {!! $errors->first('content', ':message') !!}
              </div>
            </div>
            <div class="form-group">
              {{ Form::label('tags', 'Mots-clés') }}
              {{ Form::text('tags', null, ['class' => 'form-control' .($errors->has('tags')? ' is-invalid' : ''), 'placeholder' => 'Entrez les mots-clés séparés par des virgules', 'required' => 'true']) }}

              <div class="invalid-feedback">
                {!! $errors->first('tags', ':message') !!}
              </div>
            </div>
            <div class="form-group">
              <div class="custom-file">
                {!! Form::file('image', ['class' => 'custom-file-input', 'id' => 'file-input']) !!}
                {!! Form::label('image', 'Choisir une image pour illustrer', ['class' => 'custom-file-label']) !!}
                <small class="form-text text-muted">La taille du fichier doit-être moins de 1 Mo</small>
                <div class="invalid-feedback">
                  {!! $errors->first('image', ':message') !!}
                </div>
              </div>
            </div>
            {!! Form::submit('Publier', ['class' => 'btn btn-primary float-right']) !!}
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script src="/js/form.js"></script>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_maps_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
  <script src="/js/tinymce/tinymce.min.js"></script>
  <script>tinymce.init({
    selector: '#content',
    min_height:250,
    plugins:'lists,image',
    language:'fr_FR'
  });</script>
@endsection
