<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyClassStoreRequest;
use App\Http\Requests\MyClassUpdateRequest;
use App\Models\MyClass;
use App\Services\MyClass\MyClassService;

class MyClassController extends Controller
{
    /**
     * Class service instance
     *
     * @var MyClassService
     */
    public MyClassService $myClassService;

    //construct method
    public function __construct(MyClassService $myClassService)
    {
        $this->myClassService = $myClassService;
        $this->authorizeResource(MyClass::class, 'class');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.class.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.class.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MyClassStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(MyClassStoreRequest $request)
    {
        $data = $request->validated();
        $this->myClassService->createClass($data);

        return back()->with('success', __('Class created successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param MyClass $class
     *
     * @return \Illuminate\Http\Response
     */
    public function show(MyClass $class)
    {
        $data['class'] = $class;

        return view('pages.class.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MyClass $class
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(MyClass $class)
    {
        $data['myClass'] = $class;

        return view('pages.class.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MyClassStoreRequest $request
     * @param MyClass             $class
     *
     * @return \Illuminate\Http\Response
     */
    public function update(MyClassUpdateRequest $request, MyClass $class)
    {
        $data = $request->validated();
        $this->myClassService->updateClass($class, $data);

        return back()->with('success', __('Class updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MyClass $class
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(MyClass $class)
    {
        $this->myClassService->deleteClass($class);

        return back()->flash('success', __('Class deleted successfully'));
    }
}
