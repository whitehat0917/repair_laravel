<?php

namespace App\Http\Controllers\Front;

use App\ {
    Http\Controllers\Controller,
    Http\Requests\SearchRequest,
    Http\Requests\PostRequest,
    Repositories\PostRepository,
    Models\Tag,
    Models\Post,
    Models\Category,
    Models\User,
    Notifications\PurchaseSuccessful
};
use Illuminate\Http\Request;
use PDF;

class PostController extends Controller
{
    /**
     * The PostRepository instance.
     *
     * @var \App\Repositories\PostRepository
     */
    protected $postRepository;

    /**
     * The pagination number.
     *
     * @var int
     */
    protected $nbrPages;

    /**
     * Create a new PostController instance.
     *
     * @param  \App\Repositories\PostRepository $postRepository
     * @return void
    */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->nbrPages = config('app.nbrPages.front.posts');
        $this->page_title = "posts";
    }

    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->postRepository->getAllByUser();
        // print_r($posts);
        // die();
        $page_title = $this->page_title;

        return view('front.index', compact('posts', 'page_title'));
    }

    /**
     * Display a listing of the posts for the specified category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function category(Category $category)
    {
        $posts = $this->postRepository->getActiveOrderByDateForCategory($this->nbrPages, $category->slug);
        $info = __('Posts for category: ') . '<strong>' . $category->title . '</strong>';

        return view('front.index', compact('posts', 'info'));
    }

    /**
     * Display the specified post by slug.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $user = $request->user();

        return view('front.post', array_merge($this->postRepository->getPostBySlug($slug), compact('user')));
    }

    /**
     * Get posts for specified tag
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function tag(Tag $tag)
    {
        $posts = $this->postRepository->getActiveOrderByDateForTag($this->nbrPages, $tag->id);
        $info = __('Posts found with tag ') . '<strong>' . $tag->tag . '</strong>';

        return view('front.index', compact('posts', 'info'));
    }

    /**
     * Get posts with search
     *
     * @param  \App\Http\Requests\SearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function search(SearchRequest $request)
    {
        $search = $request->search;
        $posts = $this->postRepository->search($this->nbrPages, $search)->appends(compact('search'));
        $info = __('Posts found with search: ') . '<strong>' . $search . '</strong>';

        return view('front.index', compact('posts', 'info'));
    }

    /**
     * Display the specified post by slug.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        $post = Post::create ($request->all ());
        $insertedId = $post->id;
        $repair_id = $insertedId."";
        for ($i=strlen((string)$insertedId);$i<4;$i++){
            $repair_id  = "0".$repair_id;
        }
        Post::where('id', $insertedId)->update(array('repair_id' => $repair_id));
        if (config('app.' . 'autoemail') == true){
            $user = User::select('email')->where('id', '=', auth()->id())->first();
            $user->notify(new PurchaseSuccessful("Repair is submitted","foned-repair",$repair_id,$request->input('problem')));
        }
        
        return ['repair_id' => $repair_id];
    }

    public function savePDF(Request $request)
    {
        $content = $request->input('content');
        // print_r($content);
        // die();
        PDF::SetTitle("Repair Label");
        PDF::AddPage();
        PDF::writeHTML("", false, false, false, false, '');
        PDF::SetAutoPageBreak(false, 0);
        PDF::SetMargins(0, 0, 0, true);
        PDF::setTextRenderingMode($stroke=0, $fill=true, $clip=false);
        PDF::Image('images/serial.jpg', 3, 4, 50,30, 'JPG', '', '', true, 300, '', false, false, 0, '', false, true);        
        PDF::SetFont('dejavusans', '', 6, '', true);
        PDF::Write(0, 'reparatie # '.$content.'           ', '', 0, 'C', true, 0, false, false, 0);
        PDF::Output('repair_label.pdf', 'I');    
    }
}
