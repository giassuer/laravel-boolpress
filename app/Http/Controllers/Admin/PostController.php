<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Post;
use App\Category;
use App\Tag;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        $data = [
            'posts' => $posts
        ];
        
        return view('admin.posts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::all();
        $tags = Tag::all();

        $data = [
            'categories' => $category,
            'tags' => $tags
        ];

        return view('admin.posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form_data = $request->all();

        $new_post = new Post();
        $new_post->fill($form_data);
        $new_post->slug = $this->getUniqueSlugFromTitle($form_data['title']);
        $new_post->save();

        // se l'array dei tag non è vuoto salva i tags
        if(isset($form_data['tags'])) {
            $new_post->tags()->sync($form_data['tags']);
        }

        return redirect()->route('admin.posts.show', ['post' => $new_post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
       

        $data = [
            'post' => $post
        ];
        
        return view('admin.posts.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post_mod = Post::findOrFail($id);
        $category = Category::all();
        $tags = Tag::all();

        $data = [
            'post' => $post_mod,
            'categories' => $category,
            'tags' => $tags
        ];

        return view('admin.posts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $form_data = $request->all();
        $post = Post::findOrFail($id);

        if($form_data['title'] != $post->title) {
            $form_data['slug'] = $this->getUniqueSlugFromTitle($form_data['title']);
        }
        $post->update($form_data);

        if(isset($form_data['tags'])) {
            $post->tags()->sync($form_data['tags']);
        } else {
            // Se non esiste la chiave tags in form_data
            // significa che l'utente a rimosso il check da tutti i tag
            // quindi se questo post aveva dei tag collegati li rimuovo
            $post->tags()->sync([]);
        }

        return redirect()->route('admin.posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->tags()->sync([]);
        $post->delete();

        return redirect()->route('admin.posts.index');
    }

    protected function getUniqueSlugFromTitle($title) {
        //creo lo slug dal $title
        $slug = Str::slug($title);
        //creo un'altra variabile per evitare che i numeri si concatenino (slug-1-2-3)
        $slug_base = $slug;

        // Controlliamo se esiste già un post con questo slug.
        $post_found = Post::where('slug', '=', $slug)->first();
        $counter = 1;
        while($post_found) {
            // Se esiste, aggiungiamo -1 allo slug
            // ricontrollo che non esista lo slug con -1, se esiste provo con -2
            $slug = $slug_base . '-' . $counter;
            $post_found = Post::where('slug', '=', $slug)->first();
            $counter++;
        }
        return $slug;
    }
}
