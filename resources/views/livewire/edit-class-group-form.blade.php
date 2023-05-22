<form action="{{route('class-groups.update', $classGroup->id)}}" method="POST">
    @livewire('display-validation-error')
    <x-adminlte-input name="name" label="Name" placeholder="Enter class group name" fgroup-class="col-md-6" value="{{old('name') ? old('name') : $classGroup->name}}"/>
    @csrf
    @method('PUT')
    <x-adminlte-button label="Save Changes" theme="primary" icon="fas fa-key" type="submit"/>
</form>