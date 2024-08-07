<a href="{{ route($route, [$param => $id]) }}" class="btn btn-blue me-1">
    <i class="fa-regular fa-pen-to-square"></i></a>

<a wire:click="deleteConfirmation({{ $id }})" class="btn btn-red me-1">
    <i class="fa-solid fa-trash-can"></i></button>
