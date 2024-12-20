@extends('layouts.app')

@section('title', 'Incidencias')

@section('content')
<div class="my-4 px-4 lg:px-0 w-full flex flex-col gap-4">
    <div class="overflow-x-auto">
        <h2 class="text-2xl font-semibold">Incidencias</h2>
        <hr class="mb-4 my-8">
        <button onclick="openModal('createIncidenceModal')" class="bg-black text-white px-4 py-2 rounded hover:bg-transparent hover:text-black border border-black">
            Crear Incidencia
        </button>
        <div class="overflow-auto max-h-96">
            <table id="properties" class="min-w-full bg-white shadow-md rounded-xl mt-4">
                <thead>
                    <tr class="bg-blue-gray-100 text-gray-700">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Nombre de la propiedad</th>
                        <th class="px-4 py-3 text-left">Nombre del cliente</th>
                        <th class="px-4 py-3 text-left">Descripcion del incidente</th>
                        <th class="px-4 py-3 text-left">Estado del incidente</th>
                        <th class="px-4 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-blue-gray-900">
                    @foreach ($incidences as $incidence)
                    <tr class="border-b border-blue-gray-200">
                        <td class="px-4 py-3">{{ $incidence->id }}</td>
                        <td class="px-4 py-3">{{ $incidence->booking->property->name }}</td>
                        <td class="px-4 py-3">{{ $incidence->booking->name_client }}</td>
                        <td class="px-4 py-3 max-w-xs text-ellipsis overflow-hidden whitespace-nowrap" title="{{ $incidence->description }}">
                            {{ $incidence->description }}
                        </td>
                        <td class="px-4 py-3">{{$incidence->status}}</td>
                        <td class="px-4 py-3">
                            <span
                                onclick="toggleChildRows('{{ $incidence->id }}')"
                                class="cursor-pointer text-gray-600 hover:text-gray-800 inline-block"
                                title="Ver detalles">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </span>
                            <span
                                onclick="openModal('assignNewTaskModal{{ $incidence->id }}')"
                                class="cursor-pointer text-gray-600 hover:text-gray-800 inline-block"
                                title="Registrar nueva tarea">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                            <span
                                onclick="openModal('editIncidenceModal{{ $incidence->id }}')"
                                class="cursor-pointer text-gray-600 hover:text-gray-800 inline-block"
                                title="Editar propiedad">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                            </span>
                            <x-delete-button
                                :action="route('incidence.destroy', $incidence->id)"
                                :id="$incidence->id"
                                buttonText="Eliminar recurso" />
                        </td>
                    </tr>
                    <tr class="child-row hidden" id="child-row-{{ $incidence->id }}">
                        <td colspan="6" class="bg-gray-50">
                            <table class="min-w-full bg-gray-50 border border-gray-200 rounded-lg pb-2 mb-2">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th colspan="6" class="px-4 py-3 text-center font-bold">
                                            Tareas a realizar - Incidencia {{ $incidence->id }}
                                        </th>
                                    </tr>
                                    <tr class="bg-white text-gray-700">
                                        <th class="px-4 py-2 text-left">ID</th>
                                        <th class="px-4 py-2 text-left">Nombre de la Tarea</th>
                                        <th class="px-4 py-2 text-left">Estado</th>
                                        <th class="px-4 py-2 text-left">Costo</th>
                                        <th class="px-4 py-2 text-left">Asumido por</th>
                                        <th class="px-4 py-2 text-left">Comentarios</th>
                                        <th class="px-4 py-2 text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($incidence->tasks as $task)
                                    <tr class="border-b border-gray-300">
                                        <td class="px-4 py-2">{{ $task->id }}</td>
                                        <td class="px-4 py-3 max-w-xs text-ellipsis overflow-hidden whitespace-nowrap" title="{{ $task->title }}">{{ $task->title }}</td>
                                        <td class="px-4 py-2">
                                            @if ($task->status === 'pending')
                                            Pendiente
                                            @elseif ($task->status === 'assigned')
                                            Asignada
                                            @elseif ($task->status === 'solved')
                                            Solucionada
                                            @else
                                            No solucionada
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">{{ $task->price }}€</td>
                                        <td class="px-4 py-2">
                                            @if ($task->assumed_by === 'client')
                                            Cliente
                                            @elseif ($task->assumed_by === 'owner')
                                            Propietario
                                            @elseif ($task->assumed_by === 'homeselect')
                                            HomeSelect
                                            @else
                                            Sin asignar
                                            @endif</td>
                                        <td class="px-4 py-2 max-w-xs text-ellipsis overflow-hidden whitespace-nowrap" title="{{ $task->notes }}">{{ $task->notes }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                onclick="openModal('finishTaskModal{{ $task->id }}')"
                                                class="cursor-pointer text-gray-600 hover:text-gray-800 inline-block"
                                                title="Terminar tarea">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </span>
                                            <span
                                                onclick="openModal('editTaskModal{{ $task->id }}')"
                                                class="cursor-pointer text-gray-600 hover:text-gray-800 inline-block"
                                                title="Editar tarea">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                            </span>
                                            <x-delete-button
                                                :action="route('task.destroy', $task->id)"
                                                :id="$task->id"
                                                buttonText="Eliminar recurso" />
                                        </td>
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
</div>

<!-- Propiedades para crear reserva -->
@include('components.modal', [
'id' => 'createIncidenceModal',
'title' => 'Crear Incidencia',
'action' => route('incidence.store'),
'fields' => [
[
'id' => 'booking_id',
'name' => 'booking_id',
'label' => 'Nombre de la reserva',
'value' => '',
'type' => 'select',
'readonly' => false,
'options' => []
],
[
'id' => 'description',
'name' => 'description',
'label' => 'Descripcion del incidente',
'value' => '',
'type' => 'text',
'readonly' => false
],
]
])

<!-- Propiedades para asignar nueva tarea -->
@foreach ($incidences as $incidence)
@include('components.modal', [
'id' => 'assignNewTaskModal' . $incidence->id,
'title' => 'Asignar nueva tarea',
'action' => route('task.store'),
'fields' => [
[
'id' => 'incidence_id',
'name' => 'incidence_id',
'label' => '',
'value' => $incidence->id,
'type' => 'hidden',
'readonly' => true
],
[
'id' => 'title',
'name' => 'title',
'label' => 'Tarea a realizar',
'value' => '',
'type' => 'text',
'readonly' => false
],
[
'id' => 'description',
'name' => 'description',
'label' => 'Descripcion de la tarea',
'value' => '',
'type' => 'text',
'readonly' => false
],
[
'id' => 'price',
'name' => 'price',
'label' => 'Costo a asumir',
'value' => '',
'type' => 'text',
'readonly' => false
],
[
'id' => 'assumed_by',
'name' => 'assumed_by',
'label' => 'Responsable',
'value' => '',
'type' => 'select',
'readonly' => false,
'options' => [
'client' => 'Cliente',
'owner' => 'Propetario',
'homeselect' => 'Homeselect',
]
],
]
])
@endforeach

<!--Propiedades para el terminar tareas  -->
@foreach ($incidences as $incidence)
@foreach ($incidence->tasks as $task)
@include('components.modal', [
'id' => 'finishTaskModal' . $task->id,
'title' => 'Terminar Tarea',
'action' => route('task.updateFinish', $task),
'method' => 'PATCH',
'fields' => [
[
'id' => 'incidence_id',
'name' => 'incidence_id',
'label' => '',
'value' => $task->incidence_id,
'type' => 'hidden',
'readonly' => true
],
[
'id' => 'title',
'name' => 'title',
'label' => 'Tarea a realizar',
'value' => $task->title,
'type' => 'text',
'readonly' => true
],
[
'id' => 'status',
'name' => 'status',
'label' => 'Estado de la tarea',
'value' => '',
'type' => 'select',
'readonly' => false,
'options' => [
'solved' => 'Solucionada',
'closed' => 'No solucionada',
]
],
[
'id' => 'notes',
'name' => 'notes',
'label' => 'Comentarios',
'value' => '',
'type' => 'textarea',
'readonly' => false
],
]
])
@endforeach
@endforeach


<!--Propiedades para el editar  -->
@foreach ($incidences as $incidence)
@include('components.modal', [
'id' => 'editIncidenceModal' . $incidence->id,
'title' => 'Editar Propiedad',
'action' => route('incidence.update', $incidence),
'method' => 'PUT',
'fields' => [
[
'id' => 'booking_id',
'name' => 'booking_id',
'label' => '',
'value' => $incidence->booking_id,
'type' => 'hidden',
'readonly' => true
],
[
'id' => 'description',
'name' => 'description',
'label' => 'Descripcion del incidente',
'value' => $incidence->description,
'type' => 'textarea',
'readonly' => false
],
[
'id' => 'status',
'name' => 'status',
'label' => 'Estado del incidente',
'value' => $incidence->status,
'type' => 'select',
'readonly' => false,
'options' => [
'pending' => 'Pendiente',
'closed' => 'Cerrado',
]
],
]
])
@endforeach

<!--Propiedades para el editar tareas  -->
@foreach($incidences as $incidence)
@foreach ($incidence->tasks as $task)
@include('components.modal', [
'id' => 'editTaskModal' . $task->id,
'title' => 'Editar Tarea',
'action' => route('task.update', $task),
'method' => 'PUT',
'fields' => [
[
'id' => 'incidence_id',
'name' => 'incidence_id',
'label' => '',
'value' => $task->incidence_id,
'type' => 'hidden',
'readonly' => true
],
[
'id' => 'title',
'name' => 'title',
'label' => 'Tarea a realizar',
'value' => $task->title,
'type' => 'text',
'readonly' => false
],
[
'id' => 'description',
'name' => 'description',
'label' => 'Descripcion de la tarea',
'value' => $task->description,
'type' => 'text',
'readonly' => false
],
[
'id' => 'status',
'name' => 'status',
'label' => 'Estado de la tarea',
'value' => $task->status,
'type' => 'select',
'readonly' => false,
'options' => [
'pending' => 'Pendiente',
'assigned' => 'Asignada',
]
],
[
'id' => 'price',
'name' => 'price',
'label' => 'Costo a asumir',
'value' => $task->price,
'type' => 'text',
'readonly' => false
],
[
'id' => 'assumed_by',
'name' => 'assumed_by',
'label' => 'Responsable',
'value' => $task->assumed_by,
'type' => 'select',
'readonly' => false,
'options' => [
'client' => 'Cliente',
'owner' => 'Propetario',
'homeselect' => 'Homeselect',
]
],
]
])
@endforeach
@endforeach
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('/api/booking')
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Error al cargar las propiedades');
                }
                return response.json();
            })
            .then(function(bookings) {
                let selectElement = document.getElementById('booking_id');
                selectElement.innerHTML = '';
                let defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Selecciona una propiedad';
                selectElement.appendChild(defaultOption);
                bookings.forEach(function(bookings) {
                    let option = document.createElement('option');
                    option.value = bookings.id;
                    option.textContent = bookings.name_property;
                    selectElement.appendChild(option);
                });
            })
            .catch(function(error) {
                console.error('Error loading properties:', error);
            });
    });

    function toggleChildRows(id) {
        const childRow = document.getElementById(`child-row-${id}`);
        if (childRow) {
            childRow.classList.toggle('hidden');
        }
    }
</script>
