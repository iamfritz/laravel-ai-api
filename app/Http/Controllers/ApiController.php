<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function site()
    {
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
                'Product catalog management',
                'Fast setup and clear structure',
            ],
        ]);
    }

    public function products(Request $request)
    {
        $products = Product::query()
            ->with('category:id,name,slug')
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->paginate(12, ['id', 'category_id', 'slug', 'title', 'description', 'image', 'sku', 'price', 'tags', 'status', 'created_at'], 'page', $request->query('page', 1));

        return response()->json([
            'data' => collect($products->items())
                ->map(fn ($product) => [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'title' => $product->title,
                    'description' => $product->description,
                    'image' => $product->image,
                    'sku' => $product->sku,
                    'price' => (float) $product->price,
                    'tags' => $product->tags ?? [],
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ] : null,
                    'published_at' => $product->created_at->toDateString(),
                ])
                ->values()
                ->all(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
            'per_page' => $products->perPage(),
            'next_page_url' => $products->nextPageUrl(),
            'prev_page_url' => $products->previousPageUrl(),
        ]);
    }

    public function productDetail(string $slug)
    {
        $product = Product::with('category:id,name,slug')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'description' => $product->description,
            'image' => $product->image,
            'sku' => $product->sku,
            'price' => (float) $product->price,
            'tags' => $product->tags ?? [],
            'custom_fields' => $product->custom_fields ?? [],
            'variations' => $product->variations ?? [],
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ] : null,
        ]);
    }

    public function productCategory(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)->first();

        if (! $category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $products = Product::query()
            ->with('category:id,name,slug')
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->orderByDesc('created_at')
            ->paginate(12, ['id', 'category_id', 'slug', 'title', 'description', 'image', 'sku', 'price', 'tags', 'status', 'created_at'], 'page', $request->query('page', 1));

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'data' => collect($products->items())
                ->map(fn ($product) => [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'title' => $product->title,
                    'description' => $product->description,
                    'image' => $product->image,
                    'sku' => $product->sku,
                    'price' => (float) $product->price,
                    'tags' => $product->tags ?? [],
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                    ],
                    'published_at' => $product->created_at->toDateString(),
                ])
                ->values()
                ->all(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
            'per_page' => $products->perPage(),
            'next_page_url' => $products->nextPageUrl(),
            'prev_page_url' => $products->previousPageUrl(),
        ]);
    }

    public function productTag(string $tag, Request $request)
    {
        $normalizedTag = strtolower(trim($tag));
        $products = Product::query()
            ->with('category:id,name,slug')
            ->where('status', 'published')
            ->get()
            ->filter(function ($product) use ($normalizedTag) {
                $tags = array_map('strtolower', (array) ($product->tags ?? []));

                return in_array($normalizedTag, $tags, true);
            })
            ->values();

        $items = $products->slice(($request->query('page', 1) - 1) * 12, 12);

        return response()->json([
            'tag' => $tag,
            'data' => $items->map(fn ($product) => [
                'id' => $product->id,
                'slug' => $product->slug,
                'title' => $product->title,
                'description' => $product->description,
                'image' => $product->image,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'tags' => $product->tags ?? [],
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ] : null,
                'published_at' => $product->created_at->toDateString(),
            ])->values()->all(),
            'current_page' => (int) $request->query('page', 1),
            'last_page' => max(1, (int) ceil($products->count() / 12)),
            'total' => $products->count(),
            'per_page' => 12,
        ]);
    }

    public function productCategories()
    {
        return response()->json(
            Category::query()
                ->select('id', 'name', 'slug')
                ->orderBy('name')
                ->get()
        );
    }

    public function productTags()
    {
        $tags = Product::query()
            ->where('status', 'published')
            ->get()
            ->flatMap(fn ($product) => $product->tags ?? [])
            ->unique()
            ->values()
            ->all();

        return response()->json($tags);
    }

    public function productsAdmin()
    {
        return response()->json(
            Product::with('category:id,name,slug')->orderBy('id')->get()->map(fn ($product) => [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'status' => $product->status,
                'image' => $product->image,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ] : null,
                'tags' => $product->tags ?? [],
                'custom_fields' => $product->custom_fields ?? [],
                'variations' => $product->variations ?? [],
                'created_at' => $product->created_at,
            ])
        );
    }

    public function createProduct(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'custom_fields' => ['nullable', 'array'],
            'variations' => ['nullable', 'array'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $product = Product::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
            'sku' => $validated['sku'],
            'price' => $validated['price'],
            'tags' => $validated['tags'] ?? [],
            'custom_fields' => $validated['custom_fields'] ?? [],
            'variations' => $validated['variations'] ?? [],
            'status' => $validated['status'],
        ]);

        return response()->json($product, 201);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'sku' => ['sometimes', 'required', 'string', 'max:255', 'unique:products,sku,' . $product->id],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'custom_fields' => ['nullable', 'array'],
            'variations' => ['nullable', 'array'],
            'status' => ['sometimes', 'required', 'in:draft,published'],
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function pages()
    {
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
    }

    public function page(string $slug)
    {
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
    }

    public function blog(Request $request)
    {
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
    }

    public function blogDetail(string $slug)
    {
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
    }

    public function categories()
    {
        return response()->json(
            Category::query()
                ->select('id', 'name', 'slug')
                ->orderBy('name')
                ->get()
        );
    }

    public function categoryBlog(string $slug, Request $request)
    {
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
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['nullable', 'in:admin,editor'],
        ]);

        $user = User::create([
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
    }

    public function login(Request $request)
    {
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
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function posts()
    {
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
    }

    public function createPost(Request $request)
    {
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
    }

    public function pagesAdmin()
    {
        return response()->json(Page::orderBy('id')->get());
    }

    public function createPage(Request $request)
    {
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
    }

    public function updatePage(Request $request, Page $page)
    {
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
    }

    public function deletePage(Page $page)
    {
        $page->delete();

        return response()->json(['message' => 'Page deleted successfully']);
    }

    public function updatePost(Request $request, BlogPost $post)
    {
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
    }

    public function deletePost(BlogPost $post)
    {
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
