@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
<div class="my-4 px-4 lg:px-0 w-full flex flex-col gap-4">
    <div class="overflow-x-auto">
        <h2 class="text-2xl font-semibold">Reservas</h2>
        <hr class="mb-4 my-8">
        <button onclick="openModal('createBookingModal')" class="bg-black text-white px-4 py-2 rounded hover:bg-transparent hover:text-black border border-black">
            Crear Reservas
        </button>
        <div class="overflow-auto max-h-96">
            <table id="properties" class="min-w-full bg-white shadow-md rounded-xl mt-4">
                <thead>
                    <tr class="bg-blue-gray-100 text-gray-700 border-b border-blue-gray-200">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Nombre de la propiedad</th>
                        <th class="px-4 py-3 text-left">Nombre del cliente</th>
                        <th class="px-4 py-3 text-left">F. Inicio de alquiler</th>
                        <th class="px-4 py-3 text-left">F. Fin de alquiler</th>
                        <th class="px-4 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-blue-gray-900">
                    @foreach ($bookings as $booking)
                    <tr class="border-b border-blue-gray-200">
                        <td class="px-4 py-3">{{ $booking->id }}</td>
                        <td class="px-4 py-3">{{ $booking->property->name }}</td>
                        <td class="px-4 py-3">{{ $booking->name_client }}</td>
                        <td class="px-4 py-3">{{ $booking->date_start }}</td>
                        <td class="px-4 py-3">{{ $booking->date_end }}</td>
                        <td class="px-4 py-3">
                            <span
                                onclick="openModal('editBookingModal{{ $booking->id }}')"
                                class="cursor-pointer text-gray-600 hover:text-gray-800 inline-block"
                                title="Editar propiedad">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                            </span>
                            <x-delete-button
                                :action="route('booking.destroy', $booking->id)"
                                :id="$booking->id"
                                buttonText="Eliminar recurso" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Propiedades para crear reserva -->
    @include('components.modal', [
    'id' => 'createBookingModal',
    'title' => 'Crear Reserva',
    'action' => route('booking.store'),
    'fields' => [
    [
    'id' => 'property_id',
    'name' => 'property_id',
    'label' => 'Nombre de la propiedad',
    'value' => '', // Valor seleccionado por defecto
    'type' => 'select',
    'readonly' => false,
    'options' => [] // Dejamos vacío para llenarlo dinámicamente
    ],
    [
    'id' => 'name_client',
    'name' => 'name_client',
    'label' => 'Nombre del cliente',
    'value' => '',
    'type' => 'text',
    'readonly' => false
    ],
    [
    'id' => 'date_start',
    'name' => 'date_start',
    'label' => 'Fecha de inicio de alquiler',
    'value' => '',
    'type' => 'date',
    'readonly' => false
    ],
    [
    'id' => 'date_end',
    'name' => 'date_end',
    'label' => 'Fecha de fin de alquiler',
    'value' => '',
    'type' => 'date',
    'readonly' => false
    ]
    ]
    ])

    <!--Propiedades para el editar  -->
    @foreach ($bookings as $booking)
    @include('components.modal', [
    'id' => 'editBookingModal' . $booking->id,
    'title' => 'Editar Propiedad',
    'action' => route('booking.update', $booking),
    'method' => 'PUT',
    'fields' => [
    [
    'id' => 'id',
    'name' => 'id',
    'label' => '',
    'value' => $booking->id,
    'type' => 'hidden',
    'readonly' => true
    ],
    [
    'id' => 'property_id',
    'name' => 'property_id',
    'label' => '',
    'value' => $booking->property_id,
    'type' => 'hidden',
    'readonly' => true
    ],
    [
    'id' => 'property_name',
    'name' => 'property_name',
    'label' => 'Nombre de la propiedad',
    'value' => $booking->property->name,
    'type' => 'text',
    'readonly' => true,
    ],
    [
    'id' => 'name_client',
    'name' => 'name_client',
    'label' => 'Nombre del cliente',
    'value' => $booking->name_client,
    'type' => 'text',
    'readonly' => false
    ],
    [
    'id' => 'date_start',
    'name' => 'date_start',
    'label' => 'Fecha de inicio de alquiler',
    'value' => $booking->date_start,
    'type' => 'date',
    'readonly' => false
    ],
    [
    'id' => 'date_end',
    'name' => 'date_end',
    'label' => 'Fecha de fin de alquiler',
    'value' => $booking->date_end,
    'type' => 'date',
    'readonly' => false
    ]
    ]
    ])
    @endforeach
    @endsection

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/property')
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Error al cargar las propiedades');
                    }
                    return response.json();
                })
                .then(function(properties) {
                    let selectElement = document.getElementById('property_id');
                    selectElement.innerHTML = '';
                    let defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Selecciona una propiedad';
                    selectElement.appendChild(defaultOption);
                    properties.forEach(function(property) {
                        let option = document.createElement('option');
                        option.value = property.id;
                        option.textContent = property.name;
                        selectElement.appendChild(option);
                    });
                })
                .catch(function(error) {
                    console.error('Error loading properties:', error);
                });
        });
    </script>
