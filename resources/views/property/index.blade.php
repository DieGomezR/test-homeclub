@extends('layouts.app')

@section('title', 'Propiedades')

@section('content')
<div class="my-4 px-4 lg:px-0 w-full flex flex-col gap-4">
    <h1 class="text-2xl font-bold">Propiedades</h1>
    <hr class="mb-4 my-8">
    <button onclick="openModal('createPropertyModal')" class="bg-black text-white px-4 py-2 rounded hover:bg-transparent hover:text-black border border-black">
        Crear Propiedad
    </button>

    <div class="overflow-auto max-h-96">
        <table class="min-w-full bg-white mt-4 shadow-md rounded">
            <thead>
                <tr class="bg-blue-gray-100 text-gray-700 border-b border-blue-gray-200">
                    <th class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $property)
                <tr class="border-b border-blue-gray-200">
                    <td class="px-4 py-3">{{ $property->name }}</td>
                    <td class="px-4 py-3 text-right">
                        <span
                            onclick="toggleChildRows('{{ $property->id }}')"
                            class="cursor-pointer text-gray-600 hover:text-gray-800 inline-block"
                            title="Ver detalles">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </span>
                    </td>
                </tr>
                <tr class="child-row hidden" id="child-row-{{ $property->id }}">
                    <td colspan="6" class="bg-gray-50">
                        <table class="min-w-full bg-gray-50 border border-gray-200 rounded-lg pb-2 mb-2">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th colspan="6" class="px-4 py-3 text-center font-bold">
                                        Reservas para propiedad: {{ $property->name }}
                                    </th>
                                </tr>
                                <tr class="bg-blue-gray-100 text-gray-700 border-b border-blue-gray-200">
                                    <th>ID</th>
                                    <th>Nombre del cliente</th>
                                    <th>F. Inicio de alquiler</th>
                                    <th>F. Fin de alquiler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($property->bookings as $booking)
                                <tr class="border-b border-blue-gray-200">
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->name_client }}</td>
                                    <td>{{ $booking->date_start }}</td>
                                    <td>{{ $booking->date_end }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


<!-- Modal para crear propiedad -->
@section('modals')
@include('components.modal', [
'id' => 'createPropertyModal',
'title' => 'Crear Propiedad',
'action' => route('property.store'),
'fields' => [
[ 'id' => 'name', 'name' => 'name', 'label' => 'Nombre de la propiedad', 'value' => '', 'type' => 'text', 'readonly' => false ]
]
])

<!-- Modales dinámicos para editar propiedades -->
@foreach ($properties as $property)
@include('components.modal', [
'id' => 'editPropertyModal' . $property->id,
'title' => 'Editar Propiedad',
'action' => route('property.update', $property),
'method' => 'PUT',
'fields' => [
[ 'id' => 'id', 'name' => 'id', 'label' => '', 'value' => $property->id, 'type' => 'hidden', 'readonly' => true ],
[ 'id' => 'name', 'name' => 'name', 'label' => 'Nombre de la propiedad', 'value' => $property->name, 'type' => 'text', 'readonly' => false ]
]
])
@endforeach

@endsection


<script>
    function toggleChildRows(id) {
        const childRow = document.getElementById(`child-row-${id}`);
        if (childRow) {
            childRow.classList.toggle('hidden');
        }
    }
</script>
