<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($post)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl">{{ $post->title }}</h2>
                        <small class="text-gray-600">by {{ $post->user->name }}
                            , {{ $post->created_at->diffForHumans() }}</small>
                        <br/>
                        <form method="POST" action="{{ route('posts.update', $post->slug) }}" class="mb-3">
                            @csrf
                            @method('PUT')
                            <fieldset class="rating">
                                @foreach($ratings as $rating)
                                    <input type="radio" id="checked{{$post->slug . $rating->id}}"
                                           {{ (auth()->user() and $post->ratings->pluck('pivot')->where('user_id', auth()->user()->id)->isEmpty()
                      and auth()->user()->id != $post->user->id) ? 'onclick=this.form.submit()' : 'disabled readonly' }}
                                           name="rating" value="{{$rating->id}}"
                                        {{ $rating->description == round($post->ratings->pluck('rating')->avg() * 2) / 2  ? 'checked':''}} />
                                    <label for="checked{{$post->slug . $rating->id}}"
                                           class="{{ (auth()->user() and $post->ratings->pluck('pivot')->where('user_id', auth()->user()->id)->isEmpty()
                      and auth()->user()->id != $post->user->id) ? '' : 'pointer-events-none' }} {{ $loop->even ? 'half' : 'full' }}"></label>
                                @endforeach
                            </fieldset>
                        </form>
                        <br/>
                        <p class="my-6">{{ $post->content }}</p>
                        @if(auth()->user() and auth()->user()->can('publish articles') and empty($post->published_at))
                            <form method="POST" class="inline-block" action="{{ route('posts.update', $post->slug) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="publish" value="yes"/>
                                <button type="submit"
                                        class="shadow-md py-1 mr-2 rounded bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-opacity-50 text-white px-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-globe2 inline mr-1" viewBox="0 0 16 16">
                                        <path
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855-.143.268-.276.56-.395.872.705.157 1.472.257 2.282.287V1.077zM4.249 3.539c.142-.384.304-.744.481-1.078a6.7 6.7 0 0 1 .597-.933A7.01 7.01 0 0 0 3.051 3.05c.362.184.763.349 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9.124 9.124 0 0 1-1.565-.667A6.964 6.964 0 0 0 1.018 7.5h2.49zm1.4-2.741a12.344 12.344 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332zM8.5 5.09V7.5h2.99a12.342 12.342 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.612 13.612 0 0 1 7.5 10.91V8.5H4.51zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741H8.5zm-3.282 3.696c.12.312.252.604.395.872.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a6.696 6.696 0 0 1-.598-.933 8.853 8.853 0 0 1-.481-1.079 8.38 8.38 0 0 0-1.198.49 7.01 7.01 0 0 0 2.276 1.522zm-1.383-2.964A13.36 13.36 0 0 1 3.508 8.5h-2.49a6.963 6.963 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667zm6.728 2.964a7.009 7.009 0 0 0 2.275-1.521 8.376 8.376 0 0 0-1.197-.49 8.853 8.853 0 0 1-.481 1.078 6.688 6.688 0 0 1-.597.933zM8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855.143-.268.276-.56.395-.872A12.63 12.63 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.963 6.963 0 0 0 14.982 8.5h-2.49a13.36 13.36 0 0 1-.437 3.008zM14.982 7.5a6.963 6.963 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008h2.49zM11.27 2.461c.177.334.339.694.482 1.078a8.368 8.368 0 0 0 1.196-.49 7.01 7.01 0 0 0-2.275-1.52c.218.283.418.597.597.932zm-.488 1.343a7.765 7.765 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z"/>
                                    </svg>
                                    Publish
                                </button>
                            </form>
                        @endif
                        @if(auth()->user() and auth()->user()->can('delete articles')
                            and (auth()->user()->hasAnyRole(['admin','moderator']) or auth()->user()->id == $post->user->id))
                            <form method="POST" class="inline-block" action="{{ route('posts.destroy', $post->slug) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="shadow-md py-1 mr-2 rounded bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-opacity-50 text-white px-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-x inline mr-1" viewBox="0 0 16 16">
                                        <path
                                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        @endif
                        @if(auth()->user() and auth()->user()->can('edit articles')
                           and (auth()->user()->hasAnyRole(['admin','moderator']) or auth()->user()->id == $post->user->id))
                            <a href="{{route('posts.edit', $post->slug)}}"
                                    class="shadow-md py-1 mr-2 rounded bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:ring-opacity-50 text-white px-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square inline mr-1" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                                Edit
                            </a>
                        @endif

                    </div>
                </div>
            @else
                <div class="bg-red-300 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-red-300 border-b border-gray-200">
                        <h2> Post not found. </h2>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
