<?php

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/site', function () {
    return response()->json([
        'name' => 'Laravel AI',
        'tagline' => 'Build faster with a Laravel API and a modern Next.js frontend.',
        'hero' => [
            'title' => 'Launch smarter digital experiences.',
            'subtitle' => 'A clean architecture for modern web products, content sites, and business dashboards.',
        ],
        'features' => [
            'Laravel API foundation',
            'Next.js user experience',
            'Content-driven blog pages',
            'Fast setup and clear structure',
        ],
    ]);
});

Route::get('/pages', function () {
    return response()->json(
        Page::query()
            ->where('status', 'published')
            ->orderBy('title')
            ->get()
            ->map(fn ($page) => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'content' => $page->content,
                'photo' => $page->photo,
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
                'meta_keywords' => $page->meta_keywords,
                'meta_fields' => $page->meta_fields,
            ])
    );
});

Route::get('/pages/{slug}', function (string $slug) {
    $page = Page::where('slug', $slug)
        ->where('status', 'published')
        ->first();

    if (! $page) {
        return response()->json(['message' => 'Page not found'], 404);
    }

    return response()->json([
        'id' => $page->id,
        'title' => $page->title,
        'slug' => $page->slug,
        'content' => $page->content,
        'photo' => $page->photo,
        'meta_title' => $page->meta_title,
        'meta_description' => $page->meta_description,
        'meta_keywords' => $page->meta_keywords,
        'meta_fields' => $page->meta_fields,
    ]);
});

Route::get('/blog', function (Request $request) {
    $posts = BlogPost::query()
        ->with('category:id,name,slug')
        ->where('status', 'published')
        ->orderByDesc('created_at')
        ->paginate(6, ['id', 'category_id', 'slug', 'title', 'excerpt', 'photo', 'created_at'], 'page', $request->query('page', 1));

    return response()->json([
        'data' => collect($posts->items())
            ->map(fn ($post) => [
                'id' => $post->id,
                'slug' => $post->slug,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'photo' => $post->photo,
                'category' => $post->category ? [
                    'id' => $post->category->id,
                    'name' => $post->category->name,
                    'slug' => $post->category->slug,
                ] : null,
                'published_at' => $post->created_at->toDateString(),
            ])
            ->values()
            ->all(),
        'current_page' => $posts->currentPage(),
        'last_page' => $posts->lastPage(),
        'total' => $posts->total(),
        'per_page' => $posts->perPage(),
        'next_page_url' => $posts->nextPageUrl(),
        'prev_page_url' => $posts->previousPageUrl(),
    ]);
});

Route::get('/blog/{slug}', function (string $slug) {
    $post = BlogPost::with('category:id,name,slug')
        ->where('slug', $slug)
        ->where('status', 'published')
        ->first();

    if (! $post) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    return response()->json([
        'title' => $post->title,
        'content' => $post->content,
        'photo' => $post->photo,
        'category' => $post->category ? [
            'id' => $post->category->id,
            'name' => $post->category->name,
            'slug' => $post->category->slug,
        ] : null,
    ]);
});

Route::get('/categories', function () {
    return response()->json(
        Category::query()
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get()
    );
});

Route::get('/categories/{slug}/blog', function (string $slug, Request $request) {
    $category = Category::where('slug', $slug)->first();

    if (! $category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    $posts = BlogPost::query()
        ->with('category:id,name,slug')
        ->where('status', 'published')
        ->where('category_id', $category->id)
        ->orderByDesc('created_at')
        ->paginate(6, ['id', 'category_id', 'slug', 'title', 'excerpt', 'photo', 'created_at'], 'page', $request->query('page', 1));

    return response()->json([
        'category' => [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ],
        'data' => collect($posts->items())
            ->map(fn ($post) => [
                'id' => $post->id,
                'slug' => $post->slug,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'photo' => $post->photo,
                'category' => $post->category ? [
                    'id' => $post->category->id,
                    'name' => $post->category->name,
                    'slug' => $post->category->slug,
                ] : null,
                'published_at' => $post->created_at->toDateString(),
            ])
            ->values()
            ->all(),
        'current_page' => $posts->currentPage(),
        'last_page' => $posts->lastPage(),
        'total' => $posts->total(),
        'per_page' => $posts->perPage(),
        'next_page_url' => $posts->nextPageUrl(),
        'prev_page_url' => $posts->previousPageUrl(),
    ]);
});

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8', 'confirmed'],
        'role' => ['nullable', 'in:admin,editor'],
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $validated['role'] ?? 'editor',
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ], 201);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (! auth()->attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = auth()->user();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('role:admin,editor')->get('/posts', function () {
        return response()->json(
            BlogPost::with('user:id,name')->get()->map(fn ($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'photo' => $post->photo,
                'status' => $post->status,
                'author' => $post->user ? $post->user->name : null,
                'created_at' => $post->created_at,
            ])
        );
    });

    Route::middleware('role:admin,editor')->post('/posts', function (Request $request) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blog_posts,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $post = BlogPost::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'excerpt' => $validated['excerpt'] ?? substr(strip_tags($validated['content']), 0, 180),
            'content' => $validated['content'],
            'photo' => $validated['photo'] ?? null,
            'status' => $validated['status'],
        ]);

        return response()->json($post, 201);
    });

    Route::middleware('role:admin,editor')->get('/pages-admin', function () {
        return response()->json(Page::orderBy('id')->get());
    });

    Route::middleware('role:admin,editor')->post('/pages-admin', function (Request $request) {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug'],
            'content' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_fields' => ['nullable', 'array'],
        ]);

        $page = Page::create($validated);

        return response()->json($page, 201);
    });

    Route::middleware('role:admin,editor')->put('/pages-admin/{page}', function (Request $request, Page $page) {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'unique:pages,slug,' . $page->id],
            'content' => ['sometimes', 'nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', 'in:draft,published'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_fields' => ['nullable', 'array'],
        ]);

        $page->update($validated);

        return response()->json($page);
    });

    Route::middleware('role:admin')->delete('/pages-admin/{page}', function (Request $request, Page $page) {
        $page->delete();

        return response()->json(['message' => 'Page deleted successfully']);
    });

    Route::middleware('role:admin,editor')->put('/posts/{post}', function (Request $request, BlogPost $post) {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'unique:blog_posts,slug,' . $post->id],
            'excerpt' => ['nullable', 'string'],
            'content' => ['sometimes', 'required', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', 'in:draft,published'],
        ]);

        $post->update(array_merge($validated, [
            'excerpt' => $validated['excerpt'] ?? ($validated['content'] ?? $post->content) ? substr(strip_tags($validated['content'] ?? $post->content), 0, 180) : $post->excerpt,
        ]));

        return response()->json($post);
    });

    Route::middleware('role:admin')->delete('/posts/{post}', function (Request $request, BlogPost $post) {
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    });
});
