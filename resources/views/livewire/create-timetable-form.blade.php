<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create timetanle</h3>
    </div>
    <div class="card-body">
        <form action="{{route('timetables.store')}}" method="POST" class="md:w-1/2">
            @csrf 
            <x-display-validation-errors/>
            <p class="text-secondary">
                {{__('All fields marked * are required')}}
            </p>
            <x-input wire:ignore id="name" name="name" label="Timetable name *" placeholder="Enter timetable name" fgroup-class="col-md-6"/>
            <x-textarea id="description" name="description" label="Description" placeholder="Enter description" fgroup-class="col-md-6"/>
            <x-select id="cla" name="my_class_id" label="Select class *" fgroup-class="col-md-6" wire:model="class" wire:loading.attr="disabled" wire:target="class">
                @foreach ($classes as $item)
                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                @endforeach
            </x-select>
            <div class='col-12 my-2'>
                <x-button label="Create" theme="primary" icon="fas fa-key" type="submit" class="w-full md:w-1/2"/>
            </div>
        </form>
    </div>
</div>