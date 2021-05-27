<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(): \Illuminate\Http\JsonResponse
    {
        $posts = Cache::rememberForever('posts.index', function () {
            $model = Post::with(['user', 'ratings']);
            if (!auth()->user() or auth()->user()->hasRole(['admin', 'moderator']) !== true) {
                $model->whereNotNull('published_at');
            }

            return $model->get();
        });

        return response()->json(compact('posts'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard(): \Illuminate\Http\JsonResponse
    {
        $posts = Cache::rememberForever('posts.dashboard', function () {
            $model = Post::with(['user', 'ratings'])
                ->where('user_id', auth()->user()->id);
            return $model->get();
        });

        return response()->json(compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
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

        return response()->json(['message' => 'Successfully created.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post): \Illuminate\Http\JsonResponse
    {
        return response()->json(compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Post $post): \Illuminate\Http\JsonResponse
    {
        if (!empty($request->rating)) {
            $request->validate(['rating' => 'exists:ratings,id']);
            $post->ratings()->attach($request->rating, ['user_id' => auth()->user()->id]);
            Cache::forget('posts.index');
            return response()->json(['message' => 'Successfully voted.']);
        }
        if (!empty($request->publish)) {
            if ($request->user()->cannot('edit articles')) {
                abort(403);
            }
            $post->published_at = date('Y-m-d H:i:s', time());
            $post->save();
            Cache::forget('posts.index');
            return response()->json(['message' => 'Successfully published.']);
        }
        if (!auth()->user()
            or !auth()->user()->can('edit articles')
            or (true !== auth()->user()->hasAnyRole(['admin', 'moderator'])
                and auth()->user()->id != $post->user->id)) {
            abort(403);
        }
        if (!empty($request->title)) {
            $request->validate(['title' => 'max:255']);
            $post->title = $request->title;
        }
        if (!empty($request->slug)) {
            $request->validate(['slug' => 'alpha_dash|max:255']);
            $post->slug = $request->slug;
        }
        if (!empty($request->articleContent)) {
            $request->validate(['articleContent' => 'min:35']);
            $post->content = $request->articleContent;
        }
        if (empty($request->title) and empty($request->slug) and empty($request->articleContent)) {
            return response()->json(['message' => 'Nothing to update. ' . json_encode($post)]);
        }

        $post->save();
        Cache::forget('posts.index');

        return response()->json(['message' => 'Successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post): \Illuminate\Http\JsonResponse
    {
        $post->delete();
        Cache::forget('posts.index');
        return response()->json(['message' => 'Post has been deleted.']);
    }
}
