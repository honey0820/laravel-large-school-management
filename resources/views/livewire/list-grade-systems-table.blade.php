<div>
    <form action="" >
        <x-adminlte-select id="class-group" label="Select a class group to see grading system"  fgroup-class="col-md-6" name="" wire:model="classGroup">
            @foreach ($classGroups as $item)
                <option value="{{$item['id']}}">{{$item['name']}}</option>
            @endforeach
        </x-adminlte-select>
    </form>
    <div class="d-flex justify-content-center">
        <div wire:loading class="spinner-border" role="status">
            <p class="sr-only">Loading.....</p>
        </div>
    </div>
    @if($classGroups->find($classGroup) !== null)
        <h3 class="text-center" wire:loading.remove>{{" Grading system for ".$classGroups->find($classGroup)->name}}</h3>
    @endif
    <table class="table col-12 table-bordered" wire:loading.remove>
        <tbody class="">
            <th>Name</th>
            <th>Remark</th>
            <th>Grade from</th>
            <th>Grade to</th>
            <th></th>
            <th></th>
            @foreach ($grades as $grade)
                <tr>
                    <td>{{$grade->name}}</td>
                    <td>{{$grade->remark}}</td>
                    <td>{{$grade->grade_from}}</td>
                    <td>{{$grade->grade_to}}</td>
                    <td> @livewire('dropdown-links', [
                        'links' => [
                            ['href' => route("grade-systems.edit", $grade->id), 'text' => 'Edit', 'icon' => 'fas fa-cog'],
                        ],
                    ],)</td>
                    <td> @livewire('delete-modal', ['modal_id' => $grade->id ,"action" => route('grade-systems.destroy', $grade->id), 'item_name' => $grade->name])</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>