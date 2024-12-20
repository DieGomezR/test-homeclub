<div id="{{ $id }}" class="fixed inset-0 z-50 hidden bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-semibold mb-4">{{ $title }}</h3>
        <hr>
        {{-- Formulario dinámico --}}
        <form action="{{ $action }}" method="POST" onsubmit="submitForm(event, '{{ $id }}')">
            @csrf
            @if(isset($method) && $method !== 'POST')
            @method($method)
            @endif
            @foreach($fields as $field)
            <div class="mb-4">
                <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>

                @if ($field['type'] === 'select')
                <select
                    id="{{ $field['id'] }}"
                    name="{{ $field['name'] }}"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded"
                    {{ $field['readonly'] ? 'disabled' : '' }}
                    required>

                    @foreach($field['options'] ?? [] as $value => $label)
                    <option value="{{ $value }}" {{ $value == $field['value'] ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @elseif ($field['type'] === 'textarea')
                <textarea
                    id="{{ $field['id'] }}"
                    name="{{ $field['name'] }}"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded"
                    rows="5"
                    cols="50"
                    {{ $field['readonly'] ? 'readonly' : '' }}
                    required>{{ $field['value'] ?? '' }}</textarea>
                @else
                <input
                    type="{{ $field['type']  ?? 'hidden' }}"
                    id="{{ $field['id'] }}"
                    name="{{ $field['name'] }}"
                    value="{{ $field['value'] ?? '' }}"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded"
                    {{ $field['readonly'] ? 'readonly' : '' }}
                    required>
                @endif
            </div>
            @endforeach
            <hr class="mb-4">
            <div class="flex justify-end">
                <button type="submit" class="bg-black text-white py-2 px-4 rounded hover:bg-transparent hover:text-black border border-black">Guardar cambios</button>
                <button type="button" class="ml-4 bg-gray-300 py-2 px-4 rounded hover:bg-gray-200" onclick="closeModal('{{ $id }}')">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    async function submitForm(event, modalId) {
        event.preventDefault();
        const form = event.target;
        const action = form.action;
        const method = form.method;
        const formData = new FormData(form);

        try {
            const options = await fetch(action, {
                method: method,
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });
            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            })
            if (options.ok) {
                const result = await options.json();
                Swal.fire({
                    icon: result.icon || 'success',
                    title: result.title || 'Éxito',
                    text: result.message || 'Operación realizada con éxito.',
                }).then(() => {
                    closeModal(modalId);
                    location.reload();
                });
            } else {
                const error = await options.json();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al procesar la solicitud.',
                });
            }
        } catch (err) {
            console.error('Error al enviar el formulario:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                text: 'Ocurrió un error inesperado. Por favor, intenta nuevamente.',
            });
        }
    }
</script>
