@extends('layouts.app')

@section('title', 'Propiedades')

@section('content')
<div class="my-4 px-4 lg:px-0 w-full flex flex-col gap-4">
    <div class="overflow-x-auto">
        <h1 class="text-2xl font-bold">Propiedades</h1>
        <hr class="mb-4 my-8">
        <button onclick="openModal('createPropertyModal')" class="bg-black text-white px-4 py-2 rounded hover:bg-transparent hover:text-black border border-black">
            Crear Propiedad
        </button>

        <!-- Contenedor con overflow para el cuerpo de la tabla -->
        <div class="overflow-auto max-h-96">
            <table id="properties" class="min-w-full bg-white mt-4 shadow-md rounded">
                <thead>
                    <tr class="bg-blue-gray-100 text-gray-700 border-b border-blue-gray-200">
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-blue-gray-900">
                    @foreach ($properties as $property)
                    <tr class="border-b border-blue-gray-200">
                        <td class="px-4 py-3">{{ $property->name }}</td>
                        <td class="px-4 py-3 text-right">
                            <span
                                onclick="openModal('editPropertyModal{{ $property->id }}')"
                                class="cursor-pointer text-gray-600 hover:text-gray-800 inline-block"
                                title="Editar propiedad">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                            </span>
                            <x-delete-button
                                :action="route('property.destroy', $property->id)"
                                :id="$property->id"
                                buttonText="Eliminar recurso" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para crear propiedad -->
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
