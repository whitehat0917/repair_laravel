<?php

namespace App\Http\Controllers\Back;

use App\ {
    Http\Requests\PostRequest,
    Http\Controllers\Controller,
    Models\Category,
    Models\Post,
    Models\User,
    Repositories\PostRepository,
    Repositories\ConfigAppRepository,
    Notifications\PurchaseSuccessful
};

use Illuminate\Http\Request;
use SalamWaddah\Mandrill\MandrillMessage;

class PostController extends Controller
{
    use Indexable;

    /**
     * Create a new PostController instance.
     *
     * @param  \App\Repositories\PostRepository $repository
     */
    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;

        $this->table = 'posts';
    }

    /**
     * Update "new" field for post.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function updateSeen(Post $post)
    {
        $post->ingoing->delete ();

        return response ()->json ();
    }

    /**
     * Update "active" field for post.
     *
     * @param  \App\Models\Post $post
     * @param  bool $status
     * @return \Illuminate\Http\Response
     */
    public function updateActive(Post $post, $status = false)
    {
        $post->active = $status;
        $post->save();

        return response ()->json ();
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where("role","user")->get();

        return view('back.posts.create', compact('users'));
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = Post::create ($request->all ());
        $insertedId = $post->id;
        $repair_id = $insertedId."";
        for ($i=4-(strlen((string)$insertedId));$i<4;$i++){
            $repair_id  = "0".$repair_id;
        }
        Post::where('id', $insertedId)->update(array('repair_id' => $repair_id));
        $user = User::select('email')->where('id', '=',$request->input('user_id'))->first();
        $user->notify(new PurchaseSuccessful("Repair is submitted","foned-repair",$repair_id,$request->input('problem')));

        return redirect(route('posts.index'))->with('post-ok', __('The post has been successfully created'));
    }

    /**
     * Display the post.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('back.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the post.
     *
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $this->authorize('manage', $post);

        $categories = Category::all()->pluck('title', 'id');

        return view('back.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the post in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('manage', $post);

        $this->repository->update($post, $request);

        return back()->with('post-ok', __('The post has been successfully updated'));
    }

    /**
     * Remove the post from storage.
     *
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('manage', $post);

        $post->delete ();

        return response ()->json ();
    }

    /**
     * Display the specified post by slug.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change_status(Request $request)
    {
        Post::where("repair_id",$request->input('id'))->update(array("status"=>$request->input('status')));
        $user_id = Post::select('user_id')->where('repair_id', '=', $request->input('id'))->first()->user_id;
        $post = Post::select('problem','repair_id')->where('repair_id', '=', $request->input('id'))->first();
        if (config('app.' . 'autoemail') == true){
            $user = User::select('email')->where('id', '=', $user_id)->first();
            $user->notify(new PurchaseSuccessful("Status changed","foned-repair",$post->repair_id,$post->problem));
        }
        return response()->json();
    }

    public function note(Request $request)
    {
        Post::where("repair_id",$request->input('id'))->update(array("note"=>$request->input('note')));
        return response()->json();
    }
}
