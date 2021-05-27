<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-3">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form class="w-full" method="post" action="{{ route('posts.store') }}">
                        @csrf
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                       for="title">
                                    Title
                                </label>
                                <input class="@error('title') border border-red-500 @enderror appearance-none block w-full bg-gray-200 text-gray-700
                                 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none
                                 focus:bg-white focus:border-gray-500" id="title" name="title" type="text"
                                       value="{{ old('title') }}"
                                       placeholder="Please type"/>
                                @error('title')
                                <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/2 px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                       for="slug">
                                    Slug
                                </label>
                                <input class="@error('slug') border border-red-500 @enderror appearance-none block w-full bg-gray-200 text-gray-700
                                 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none
                                 focus:bg-white focus:border-gray-500" name="slug" id="slug" type="text"
                                       placeholder="Please type" value="{{ old('slug') }}"/>
                                @error('slug')
                                <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full px-3 my-4">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                       for="content">
                                    Content
                                </label>
                                <textarea rows="5" name="content"
                                          class="@error('content') border border-red-500 @enderror sappearance-none block w-full bg-gray-200 text-gray-700
                                 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none
                                 focus:bg-white focus:border-gray-500"
                                          id="content">{{ old('content') }}</textarea>
                                @error('content')
                                <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-between px-3">
                                <button type="submit"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save inline mr-1" viewBox="0 0 16 16">
                                        <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                                    </svg>
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
