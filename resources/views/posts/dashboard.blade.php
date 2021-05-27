<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session()->has('success'))
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-3">
                    <div class="p-6 bg-white border-b border-gray-200 text-green-600">
                        {{ session()->get('success') }}
                    </div>
                </div>
            @endif
            @forelse($posts as $post)
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-3">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl">{{ $post->title }}</h2>
                        <small class="text-gray-600">by {{ $post->user->name }}
                            , {{ $post->created_at->diffForHumans() }}</small>
                        <br/>
                        @if(!empty($post->ratings->pluck('rating')->avg()))
                            <fieldset class="rating">
                                @foreach($ratings as $rating)
                                    <input disabled readonly type="radio" id="checked{{$post->slug . $rating->id}}"
                                           class="pointer-events-none"
                                           name="rating{{$rating->id}}" value="{{$rating->id}}"
                                        {{ $rating->description == round($post->ratings->pluck('rating')->avg() * 2) / 2  ? 'checked':''}} />
                                    <label for="checked{{$post->slug . $rating->id}}" class="pointer-events-none {{ $loop->even ? 'half' : 'full' }}"></label>
                                @endforeach
                            </fieldset>
                        @endif
                        <br/>
                        <p class="my-6">{{ substr($post->content, 0, 300) . '...' }}</p>
                        <hr class="my-2"/>
                        <a href="{{ route('posts.show', $post->slug) }}"
                           class="py-1 shadow-md mr-2 rounded bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:blue-red-600 focus:ring-opacity-50 text-white px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right inline mr-1" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                            </svg> Read All
                        </a>
                        <form method="POST" class="inline-block" action="{{ route('posts.destroy', $post->slug) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="shadow-md py-1 mr-2 rounded bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-opacity-50 text-white px-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x inline mr-1" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-3">
                    <div class="p-6 bg-white border-b border-gray-200 text-red-600">
                        There is no post to show.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
