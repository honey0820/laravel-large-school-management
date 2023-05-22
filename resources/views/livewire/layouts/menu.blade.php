<nav class="fixed lg:sticky inset-0 h-screen w-full lg:w-72 shadow-lg flex duration-250 text-gray-100 transition-all dark:lg:border-r dark:border-white z-40" :class="{'w-0 overflow-hidden lg:flex lg:w-20' : menuOpen == false}">
    <aside class="w-4/6 md:w-3/6 lg:w-full bg-gray-800 dark:bg-gray-900 shadow-md shadow-black border-x border-black overflow-scroll beautify-scrollbar">
        <a href="{{route('home')}}" class="flex md:hidden items-center justify-center border-b border-gray-200 p-4">
            <img src="{{asset(config('adminlte.logo_img'))  }}" alt="" class="rounded-full w-14 h-14 border border-gray-200 shadow-md">
            <h1 class="text-lg font-semibold mx-3 text-center capitalize">{{config('app.name')}}</h1>
        </a>
        <div class="p-3">

            @isset ($menu)
                @foreach ($menu as $menuItem)
                    @if (isset($menuItem['header']) & (!isset($menuItem['can']) || auth()->user()->can($menuItem['can'])))
                        <p x-show="menuOpen" x-transition class="my-3">{{$menuItem['header']}}</p> 
                    @elseif(!isset($menuItem['can']) || auth()->user()->can($menuItem['can']))
                        <div @click.outside="submenu = false" x-data="{
                            'submenu'  : {{ isset($menuItem['submenu']) && in_array(Route::currentRouteName() , array_column($menuItem['submenu']  , 'route')) ? '1' : '0'}}
                           }" >
                            @if (!isset($menuItem['submenu']))
                                <a class="flex items-center gap-2 p-3 px-4 my-2 rounded hover:bg-white hover:bg-opacity-5" href="{{route($menuItem['route'])}}" :class="{'bg-blue-600 hover:bg-blue-400 hover:bg-opacity-100' : {{Route::currentRouteName() == $menuItem['route'] ? '1' : '0'}}}"">
                                    <i class="{{$menuItem['icon'] ?? 'fa fa-circle'}} " aria-hidden="true" x-transition></i>
                                    <p x-show="menuOpen">{{$menuItem['text']}}</p>
                                </a>
                            @else
                                <a class="flex items-center justify-between gap-2 p-3 my-2 px-4 rounded hover:bg-white hover:bg-opacity-5"  @click="submenu = !submenu" :class="{'bg-blue-600 hover:bg-blue-400 hover:bg-opacity-100' : {{in_array(Route::currentRouteName() , array_column($menuItem['submenu'] , 'route'))  ? '1' : '0'}} }">
                                    <div class="flex items-center gap-2">
                                        <i class="{{$menuItem['icon'] ?? 'fa fa-circle'}} " aria-hidden="true" x-transition></i>
                                        <p x-show="menuOpen" class="cursor-default">{{$menuItem['text']}}</p>
                                    </div>
                                    <i class="transition-all" :class="{'fas fa-angle-left' : submenu == false , 'fas fa-angle-down ' : submenu == true}" x-show="menuOpen"></i>
                                </a>
                                @foreach ($menuItem['submenu'] as $submenu)
                                    @if ($submenu['can'] && auth()->user()->can($submenu['can']))
                                        <a class="flex items-center gap-2 p-3 px-4 my-2 transition-all rounded  hover:bg-opacity-5 hover:bg-white whitespace-nowrap" :class="{'h-0 my-auto overflow-hidden py-0' : submenu == false, 'bg-white text-black hover:bg-opacity-100' : {{( Route::currentRouteName() == $submenu['route'] ? '1': '0' )}} }" x-transition href="{{route($submenu['route'])}}"">
                                            <i class="{{$submenu['icon'] ?? 'far fa-fw fa-circle'}} " aria-hidden="true"></i>
                                            <p x-show="menuOpen">{{$submenu['text']}}</p>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </aside>
    <div class="lg:hidden w-2/6 md:w-3/6 bg-gray-600 opacity-30" @click="menuOpen = false" x-show="menuOpen" x-transition:enter="transition-all ease-in duration-200 delay-250" x-transition:enter-start="opacity-0" >
    </div>
</nav>
