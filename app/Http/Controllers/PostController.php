<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $posts = Cache::rememberForever('posts.index', function () {
            $model = Post::with(['user', 'ratings']);
            if (!auth()->user() or auth()->user()->hasRole(['admin', 'moderator']) !== true) {
                $model->whereNotNull('published_at');
            }

            return $model->get();
        });
        $ratings = Cache::rememberForever('ratings', function () {
            return Rating::all()->reverse();
        });

        return view('posts.index', compact('posts', 'ratings'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        if (auth()->user()->cannot('write articles')) {
            abort(403);
        }
        $posts = Cache::rememberForever('posts.dashboard', function () {
            $model = Post::with(['user', 'ratings'])
                ->where('user_id', auth()->user()->id);
            return $model->get();
        });
        $ratings = Cache::rememberForever('ratings', function () {
            return Rating::all()->reverse();
        });

        return view('posts.dashboard', compact('posts', 'ratings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        if (auth()->user()->cannot('write articles')) {
            abort(403);
        }

        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('write articles')) {
            abort(403);
        }
        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|unique:posts|alpha_dash|max:255',
            'content' => 'required|min:35'
        ]);
        $request->request->add(['user_id' => $request->user()->id]);
        Post::create($request->all());
        Cache::forget('posts.index');

        return redirect('/posts')->with('success', 'Post has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Post $post)
    {
        $ratings = Cache::rememberForever('ratings', function () {
            return Rating::all()->reverse();
        });
        return view('posts.detail', compact('post', 'ratings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Post $post)
    {
        if (!auth()->user()
            or !auth()->user()->can('edit articles')
            or (true !== auth()->user()->hasAnyRole(['admin', 'moderator'])
                and auth()->user()->id != $post->user->id)) {
            abort(403);
        }
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Post $post)
    {
        if (!empty($request->rating)) {
            $request->validate(['rating' => 'exists:ratings,id']);
            $post->ratings()->attach($request->rating, ['user_id' => auth()->user()->id]);
            Cache::forget('posts.index');
            return back()->with('success', 'Post has been updated.');
        }
        if (!empty($request->publish)) {
            if ($request->user()->cannot('edit articles')) {
                abort(403);
            }
            $post->published_at = date('Y-m-d H:i:s', time());
            $post->save();
            Cache::forget('posts.index');
            return redirect('/posts')->with('success', 'Post has been published.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|max:255',
            'articleContent' => 'required|min:35'
        ]);
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->content = $request->articleContent;
        $post->save();
        Cache::forget('posts.index');
        return back()->with('success', 'Post has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post): \Illuminate\Http\RedirectResponse
    {
        $post->delete();
        Cache::forget('posts.index');
        return redirect()->back()->with('success', 'Post has been deleted.');
    }
}
