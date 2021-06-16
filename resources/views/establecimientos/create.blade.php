@extends('layouts.app')

@section('styles_custom')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />

<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.1/dropzone.min.css" integrity="sha256-iDg4SF4hvBdxAAFXfdNrl3nbKuyVBU3tug+sFi1nth8=" crossorigin="anonymous" />

@endsection

@section('content')
<div class="container">
    <h1 class="text-center-mt-4">Registrar Establecimiento</h1>
    <div class="mt-5 row justify-content-center">
        <form class="col-md-9 col-xs-12 card card-body">
            <fieldset class="border p-4">
                <legend class="text-primary">Nombre, Categoria e Imagen Principal</legend>
                <div class="form-group">
                    <label for="nombre">Nombre Establecimiento</label>
                    <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Nombre Establecimiento" value="{{ old('nombre') }}">
                    @error('nombre')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <select name="categoria_id" id="categoria" class="form-control @error('categoria_id') is-invalid @enderror">
                        <option value="" selected desabled>Seleccione</option>
                        @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{old('categoria_id') == $categoria->id ? 'selected' : ''}}>{{ $categoria->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="imagen_principal">Imagen Principal</label>
                    <input type="file" id="imagen_principal" name="imagen_principal" class="form-control @error('imagen_principal') is-invalid @enderror" placeholder="Nombre Establecimiento" value="{{ old('imagen_principal') }}">
                    @error('imagen_principal')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>
            </fieldset>
            <fieldset class="border p-4 mt-5">
                <legend class="text-primary">Ubicacion</legend>
                <div class="form-group">
                    <label for="formbuscador">Coloca la direccion de tu establecimiento</label>
                    <input type="text" id="formbuscador" class="form-control" placeholder="Calle del negocio o establecimiento">
                    <p class="text-secundary mt-5 mb-3 text-center">El asistente colocara una direccion estimada o mueva
                        el PIN hacia el lugar correcto</p>
                </div>
                <div class="form-group">
                    <div id="mapa" style="height: 400px;"></div>
                </div>
                <p class="informacion">Confirma que los siguientes campos son correctos</p>
                <div class="form-group">
                    <label for="direccion">Direccion</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" placeholder="Direccion" value="{{ old('direccion') }}">
                    @error('direccion')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="colonia">Colonia</label>
                    <input type="text" class="form-control @error('colonia') is-invalid @enderror" id="colonia" name="colonia" placeholder="Colonia" value="{{ old('colonia') }}">
                    @error('colonia')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>
                <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
                <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}">

            </fieldset>
            <fieldset class="border p-4 mt-5">
                <legend class="text-primary">Informacion Establecimiento</legend>
                <div class="form-group">
                    <label for="telefono">Telefono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control @error('telefono') is-invalid @enderror" placeholder="Telefono del establecimiento" value="{{ old('telefono') }}">
                    @error('telefono')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripcion</label>
                    <textarea name="descripcion" id="descripcion" cols="30" rows="5" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion') }}"></textarea>
                    @error('descripcion')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="apertura">Hora Apertura</label>
                    <input type="time" id="apertura" name="apertura" class="form-control @error('apertura') is-invalid @enderror" placeholder="Telefono del establecimiento" value="{{ old('apertura') }}">
                    @error('apertura')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cierre">Hora Cierre</label>
                    <input type="time" id="cierre" name="cierre" class="form-control @error('cierre') is-invalid @enderror" placeholder="Telefono del establecimiento" value="{{ old('cierre') }}">
                    @error('cierre')
                    <div class="invalid-feedback">{{ message }}</div>
                    @enderror
                </div>
            </fieldset>
            <fieldset class="border p-4 mt-5">
                <legend class="text-primary">Informacion Establecimiento</legend>
                <div class="form-group">

                    <label for="imagenes">Imagenes</label>
                    <div id="dropzone" class="dropzone form-control"></div>

                </div>
            </fieldset>
            <input type="hidden" id="uuid" name="uuid" value="{{ Str::uuid()->toString() }}">
            <input type="submit" class="btn btn-primary mt-3 d-block" value="Registrar Establecimiento">
        </form>
    </div>
</div>
@endsection

@section('scripts_custom')
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

<script src="https://unpkg.com/esri-leaflet" defer></script>
<script src="https://unpkg.com/esri-leaflet-geocoder" defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.1/dropzone.min.js" integrity="sha256-fegGeSK7Ez4lvniVEiz1nKMx9pYtlLwPNRPf6uc8d+8=" crossorigin="anonymous" defer></script>

@endsection
