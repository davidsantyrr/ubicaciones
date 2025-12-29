<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ubicaciones</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/ubicaciones/ubicaciones.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <header class="site-nav">
    <div class="nav-container">
      <div class="brand">Ubicaciones</div>
      <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="margin-left:auto;">
        @csrf
        <button type="submit" class="btn-link">Cerrar sesión</button>
      </form>
    </div>
  </header>
  <main>
    <div class="container">
      <section class="card">
        <h1>Gestión de ubicaciones</h1>
        <form method="POST" action="{{ route('ubicaciones.store') }}" class="form-inline">
          @csrf
          <div class="field field-bodega">
            <label for="bodega_select">Bodega</label>
            <div class="stack">
              <select id="bodega_select" name="bodega_select">
                <option value="">— Seleccionar bodega —</option>
                @foreach(($bodegas ?? []) as $b)
                  <option value="{{ $b }}">{{ $b }}</option>
                @endforeach
                <option value="__new__">+ Nueva bodega…</option>
              </select>
              <div id="bodega_new_field" class="bodega-new">
                <label for="bodega_new">Nueva bodega</label>
                <input id="bodega_new" type="text" name="bodega_new" placeholder="Nombre de nueva bodega">
                @error('bodega_new')<p class="field-error">{{ $message }}</p>@enderror
              </div>
            </div>
          </div>
          <div class="field field-ubicacion">
            <label for="ubicacion">Ubicación</label>
            <textarea id="ubicacion" name="ubicacion" required placeholder="Descripción de ubicación">{{ old('ubicacion') }}</textarea>
            @error('ubicacion')<p class="field-error">{{ $message }}</p>@enderror
          </div>
          <div class="actions-submit">
            <button type="submit" class="btn-primary">Crear</button>
          </div>
        </form>
      </section>

      <section class="card">
        <h2>Listado</h2>
        @if(session('status'))
          <p class="status">{{ session('status') }}</p>
        @endif
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Bodega</th>
              <th>Ubicación</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($ubicaciones as $u)
              <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->bodega }}</td>
                <td>{{ $u->ubicacion }}</td>
                <td class="actions">
                  <form method="POST" action="{{ route('ubicaciones.update', $u->id) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <select name="bodega_select">
                      <option value="">— Seleccionar —</option>
                      @foreach(($bodegas ?? []) as $b)
                        <option value="{{ $b }}" {{ $u->bodega === $b ? 'selected' : '' }}>{{ $b }}</option>
                      @endforeach
                    </select>
                    <input type="text" name="ubicacion" value="{{ $u->ubicacion }}" placeholder="Ubicación" required>
                    <button type="submit" class="btn-secondary">Actualizar</button>
                  </form>
                  <form method="POST" action="{{ route('ubicaciones.destroy', $u->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" style="text-align:center;">Sin registros</td></tr>
            @endforelse
          </tbody>
        </table>
        <div class="pager">
          {{ $ubicaciones->links() }}
        </div>
      </section>
    </div>
  </main>

  <script>
    const select = document.getElementById('bodega_select');
    const newField = document.getElementById('bodega_new_field');
    if (select && newField) {
      // oculto inicialmente
      newField.style.display = 'none';
      select.addEventListener('change', function(){
        const isNew = this.value === '__new__';
        newField.style.display = isNew ? 'block' : 'none';
        if (!isNew) {
          const input = document.getElementById('bodega_new');
          if (input) input.value = '';
        }
      });
    }

    // Validación en acciones: requiere seleccionar bodega
    document.querySelectorAll('form.inline').forEach(function(f){
      f.addEventListener('submit', function(ev){
        const sel = f.querySelector('select[name="bodega_select"]');
        if (sel && !sel.value) {
          ev.preventDefault();
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
          });
          Toast.fire({ icon: 'error', title: 'Debes seleccionar una bodega para poder continuar.' });
        }
      });
    });

    // Toast de éxito si existe status en sesión
    @if(session('status'))
      const ToastSuccess = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });
      ToastSuccess.fire({ icon: 'success', title: @json(session('status')) });
    @endif

    (function(){
      const form = document.getElementById('logoutForm');
      if(!form) return;
      form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
          title: '¿Cerrar sesión?',
          text: 'Se cerrará tu sesión actual.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, cerrar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: 'Cerrando sesión...',
              allowOutsideClick: false,
              didOpen: () => { Swal.showLoading(); }
            });
            form.submit();
          }
        });
      });
    })();
  </script>
</body>
</html>
