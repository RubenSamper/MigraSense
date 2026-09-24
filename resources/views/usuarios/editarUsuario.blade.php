@extends("layouts.default")
@section('title','Editar Usuario')
@section('scripts')
<style>
    .registro-wrapper {
        width: min(500px, calc(100% - 32px));
        box-sizing: border-box;
        margin: 40px auto;
        padding: 30px;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        background: #fff;
    }
    .registro-wrapper h3 {
        color: #006994;
        margin: 0 0 20px;
        text-align: center;
        font-size: 1.5em;
    }
    .registro-wrapper label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: bold;
    }
    .registro-wrapper input, .registro-wrapper select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
        box-sizing: border-box;
    }
    .registro-wrapper input:focus, .registro-wrapper select:focus {
        outline: none;
        border-color: #006994;
        box-shadow: 0 0 5px rgba(0,105,148,0.3);
    }
    .registro-wrapper button {
        width: 100%;
        padding: 12px;
        background: #006994;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1em;
        font-weight: bold;
        cursor: pointer;
    }
    .registro-wrapper button:hover {
        background: #004b6b;
    }
    .registro-wrapper .alerta {
        padding: 8px;
        border: 1px solid #ff0000;
        color: #ff0000;
        background-color: #ced3eb;
        border-radius: 5px;
        margin-top: 5px;
        font-size: 0.9em;
    }
    .registro-wrapper .success-box {
        padding: 10px;
        border: 1px solid #00cc00;
        color: #006600;
        background-color: #e8f5e8;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 0.9em;
        text-align: center;
    }

    .edit-actions {
        display: flex;
        gap: 10px;
    }

    .edit-actions > * { min-width: 0; flex: 1; }

    @media (max-width: 480px) {
        .registro-wrapper { margin: 24px auto; padding: 20px 16px; }
        .edit-actions { flex-direction: column; }
    }
</style>
<script>
    function validarNombreUsuario(){
        $.ajax({
            url: "{{ route('usuarios.validarNombreUsuario') }}",
            type: 'POST',
            data: {
                nombre: $('#nombre').val(),
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.errors != null) {
                    $('#nombreErrorSpan').text(response.errors[0]);
                    $('#nombreValido').hide();
                    $('#nombreErrorSpan').parent().show();
                } else {
                    $('#nombreErrorSpan').text("");
                    $('#nombreErrorSpan').parent().hide();
                    $('#nombreValido').show();
                }
            },
            error: function (response) {
                console.error("Error", response);
            }
        });
    }

    function validarApellidosUsuario(){
         $.ajax({
            url: "{{ route('usuarios.validarApellidosUsuario') }}",
            type: 'POST',
            data: {
                apellidos: $('#apellidos').val(),
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.errors != null) {
                    $('#apellidosErrorSpan').text(response.errors[0]);
                    $('#apellidosValido').hide();
                    $('#apellidosErrorSpan').parent().show();
                } else {
                    $('#apellidosErrorSpan').text("");
                    $('#apellidosErrorSpan').parent().hide()
                    $('#apellidosValido').show();
                }
            },
            error: function (response) {
                console.error("Error", response);
            }
        });
    }

    function validarPasswordUsuario(){
        $.ajax({
            url: "{{ route('usuarios.validarPasswordUsuario') }}",
            type: 'POST',
            data: {
                password: $('#password').val(),
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.errors != null) {
                    $('#passwordErrorSpan').text(response.errors[0]);
                    $('#passwordValido').hide();
                    $('#passwordErrorSpan').parent().show();
                } else {
                    $('#passwordErrorSpan').text("");
                    $('#passwordValido').show();
                }
            },
            error: function (response) {
                console.error("Error", response);
            }
        });
    }
</script>
@endsection
@section('contenido')
<div class="registro-wrapper">
    <h3>Editar Usuario</h3>

    @if(session('success'))
    <div class="success-box">{{ session('success') }}</div>
    @endif

    <form action="{{ route('usuarios.actualizarUsuario', $usuario->id) }}" method="post">
        @csrf
        <div style="margin-bottom: 20px;">
            <label for="nombre">Nombre</label>
            <input type="text" onInput="validarNombreUsuario()" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" placeholder="Ej: Juan"/>
            <span id="nombreValido" class="valido" style="color:#00cc00; display:none;">✔ Nombre válido</span>
            <div class="alerta" style="display:none;">
                Error: <span id="nombreErrorSpan"></span>
            </div>
            @error('nombre')
            <div class="alerta">{{ $message }}</div>
            @enderror
        </div>
        <div style="margin-bottom: 20px;">
            <label for="apellidos">Apellidos</label>
            <input type="text" onInput="validarApellidosUsuario()" id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" placeholder="Ej: García López"/>
            <span id="apellidosValido" class="valido" style="color:#00cc00; display:none;">✔ Apellidos válidos</span>
            <div class="alerta" style="display:none;">
                Error: <span id="apellidosErrorSpan"></span>
            </div>
            @error('apellidos')
            <div class="alerta">{{ $message }}</div>
            @enderror
        </div>
        <div style="margin-bottom: 20px;">
            <label for="correo">Correo Electrónico</label>
            <input type="text" id="correo" name="correo" value="{{ old('correo', $usuario->correo) }}" placeholder="ejemplo@correo.com"/>
            @error('correo')
            <div class="alerta">{{ $message }}</div>
            @enderror
        </div>
        <div style="margin-bottom: 20px;">
            <label for="rol_id">Rol</label>
            <select id="rol_id" name="rol_id">
                @foreach($roles as $rol)
                    @if((int) $rol->id !== 3)
                        <option value="{{ $rol->id }}" {{ old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                    @endif
                @endforeach
            </select>
            @error('rol_id')
            <div class="alerta">{{ $message }}</div>
            @enderror
        </div>
        <div style="margin-bottom: 20px;">
            <label for="password">Nueva Contraseña <span style="font-weight:normal; color:#888;">(dejar en blanco para mantener)</span></label>
            <input type="password" onInput="validarPasswordUsuario()" id="password" name="password" placeholder="Mínimo 8 caracteres"/>
            <span id="passwordValido" class="valido" style="color:#00cc00; display:none;">✔ Contraseña válida</span>
            <div class="alerta" style="display:none;">
                Error: <span id="passwordErrorSpan"></span>
            </div>
            @error('password')
            <div class="alerta">{{ $message }}</div>
            @enderror
        </div>
        <div class="edit-actions">
            <button type="submit">Guardar cambios</button>
            <a href="{{ route('usuarios.obtenerListadoUsuarios') }}" style="display:block; text-align:center; padding:12px; background:#888; color:white; text-decoration:none; border-radius:5px; font-weight:bold; flex:1;">Cancelar</a>
        </div>
    </form>
</div>
@endsection
