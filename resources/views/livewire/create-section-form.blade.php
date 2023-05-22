<form action="{{route('sections.store')}}" method="POST">
    @livewire('display-validation-error')
    <x-adminlte-input name="name" label="Section name" placeholder="Enter section name" fgroup-class="col-md-6"/>
    <x-adminlte-select name="my_class_id" fgroup-class="col-md-6 mx12" label="Choose class" >
        @foreach ($myClasses as $myClass)
            <option value="{{$myClass->id}}">{{$myClass->name}}</option>
        @endforeach
    </x-adminlte-select>
    @csrf
    <x-adminlte-button label="Create" theme="primary" icon="fas fa-key" type="submit"/>
</form>