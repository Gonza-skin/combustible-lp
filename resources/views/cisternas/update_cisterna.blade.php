
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cisterna — La Paz</title>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            background: #f4f6f8;
            color: #2c3e50;
        }

        header {
            background: #1F4E5F;
            color: white;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo { font-weight: bold; font-size: 18px; }

        .btn {
            display: inline-block;
            padding: 11px 17px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary { background: #1F4E5F; color: white; }
        .btn-primary:hover { background: #163b48; }
        .btn-secondary { background: #e8edf0; color: #2c3e50; }

        .container {
            max-width: 800px;
            margin: 38px auto;
            padding: 0 22px;
        }

        h1 { color: #1F4E5F; margin-bottom: 8px; }
        .subtitle { color: #64748b; margin-bottom: 25px; }

        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .field { display: flex; flex-direction: column; gap: 8px; }
        .full { grid-column: 1 / -1; }

        label { color: #1F4E5F; font-weight: 600; font-size: 14px; }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccd5dc;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            color: #2c3e50;
        }

        input:focus, select:focus {
            outline: 2px solid #a6cbd2;
            border-color: #1F4E5F;
        }

        .help { color: #64748b; font-size: 12px; }
        .invalid { color: #b42318; font-size: 13px; }

        .error-box {
            background: #fdeaea;
            color: #a52a2a;
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            flex-wrap: wrap;
        }

        footer {
            text-align: center;
            padding: 24px;
            background: #1F4E5F;
            color: #cfd8dc;
            font-size: 13px;
            margin-top: 40px;
        }

        @media (max-width: 600px) {
            header { padding: 18px 20px; }
            .grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
            .card { padding: 20px; }
        }
    </style>
</head>

<body>
<header>
    <div class="logo">Sistema de Abastecimiento — La Paz</div>
    <a href="{{ route('dashboard') }}"
       class="btn"
       style="background:white;color:#1F4E5F">
        Volver al panel
    </a>
</header>

<main class="container">
    <h1>Editar cisterna #{{ $cisterna->COD_CIS }}</h1>
    <p class="subtitle">
        Modifica la información de la cisterna y guarda los cambios.
    </p>

    <section class="card">
        @if($errors->any())
            <div class="error-box">
                <strong>Revisa los siguientes errores:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('cisternas.update', $cisterna->COD_CIS) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="grid">
                <div class="field">
                    <label for="PLC_CIS">Placa de la cisterna *</label>
                    <input
                        type="text"
                        id="PLC_CIS"
                        name="PLC_CIS"
                        value="{{ old('PLC_CIS', $cisterna->PLC_CIS) }}"
                        maxlength="20"
                        required
                    >
                    @error('PLC_CIS')
                        <span class="invalid">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="CAP_CIS">Capacidad en litros *</label>
                    <input
                        type="number"
                        id="CAP_CIS"
                        name="CAP_CIS"
                        value="{{ old('CAP_CIS', $cisterna->CAP_CIS) }}"
                        min="0.01"
                        max="99999999.99"
                        step="0.01"
                        required
                    >
                    @error('CAP_CIS')
                        <span class="invalid">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="COD_COM">Tipo de combustible *</label>
                    <select id="COD_COM" name="COD_COM" required>
                        <option value="">Selecciona un combustible</option>

                        @foreach($combustibles as $combustible)
                            <option
                                value="{{ $combustible->COD_COM }}"
                                @selected(
                                    old('COD_COM', $cisterna->COD_COM)
                                    == $combustible->COD_COM
                                )
                            >
                                {{ $combustible->DES_COM }}
                            </option>
                        @endforeach
                    </select>
                    @error('COD_COM')
                        <span class="invalid">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="COD_CON">Conductor asignado</label>
                    <select id="COD_CON" name="COD_CON">
                        <option value="">Sin conductor asignado</option>

                        @foreach($conductores as $conductor)
                            <option
                                value="{{ $conductor->COD_CON }}"
                                @selected(
                                    old('COD_CON', $cisterna->COD_CON)
                                    == $conductor->COD_CON
                                )
                            >
                                {{ $conductor->NOM_CON }}
                            </option>
                        @endforeach
                    </select>
                    @error('COD_CON')
                        <span class="invalid">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field full">
                    <label for="ESD_CIS">Estado de la cisterna *</label>
                    <select id="ESD_CIS" name="ESD_CIS" required>
                        <option value="disponible"
                            @selected(old('ESD_CIS', $cisterna->ESD_CIS) === 'disponible')>
                            Disponible
                        </option>

                        <option value="asignada"
                            @selected(old('ESD_CIS', $cisterna->ESD_CIS) === 'asignada')>
                            Asignada
                        </option>

                        <option value="en_transito"
                            @selected(old('ESD_CIS', $cisterna->ESD_CIS) === 'en_transito')>
                            En tránsito
                        </option>

                        <option value="mantenimiento"
                            @selected(old('ESD_CIS', $cisterna->ESD_CIS) === 'mantenimiento')>
                            Mantenimiento
                        </option>
                    </select>
                    @error('ESD_CIS')
                        <span class="invalid">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="buttons">
                <button type="submit" class="btn btn-primary">
                    Guardar cambios
                </button>

                <a href="{{ route('cisternas.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </section>
</main>

<footer>
    © {{ date('Y') }} Sistema de Control y Gestión de Abastecimiento de Combustible
    — Municipio de La Paz
</footer>
</body>
</html>
