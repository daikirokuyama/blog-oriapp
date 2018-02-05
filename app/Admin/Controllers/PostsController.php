<?php

namespace App\Admin\Controllers;

use App\Post;
use App\Category;
use App\Tag;

use Illuminate\Http\Request;
use App\Http\Requests;
use Image;

// use Encore\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PostsController extends Controller
{
    // use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        // $header = "Edit Post"; $description = "";
        // $post = Post::find($id);
        // $categories = Category::all()->pluck(['id' => 'category_name']);
        //
        // return view('posts.edit')->with([
        //   'header' => $header, 'description' => $description, 'post' => $post, 'categories' => $categories,
        // ]);

        // return Admin::content(function (Content $content) use ($id) {
        //
        //     $content->header('header');
        //     $content->description('description');
        //
        //     $content->body($this->form()->edit($id));
        // });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        $header = "New Post";
        $description = "";
        $post = new Post();
        $categories = Category::all()->pluck('category_name', 'id');

        return view('posts.create')->with([
            'header'      => $header,
            'description' => $description,
            'post'        => $post,
            'categories'  => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $fileName = $request['image']->getClientOriginalName();
        Image::make($request['image'])->save(public_path() . '/image/topImages/' . $fileName);

        $post = Post::create([
            'title'       => $request->title,
            'content'     => $request->content,
            'top_image'   => $fileName,
            'user_id'     => Admin::user()->id,
            'category_id' => $request->category_id,
        ]);

        $tag_names = preg_split('/[\s,]+/', $request->tags, -1, PREG_SPLIT_NO_EMPTY);
        $tag_ids = [];
        foreach ($tag_names as $tag_name) {
            $tag = Tag::firstOrCreate([
                'tag_name' => $tag_name,
            ]);
            $tag_ids[] = $tag->id;
        }

        $post->tags()->sync($tag_ids);

        return redirect("admin/posts");
    }

    public function destroy($id)
    {
        if ($this->form()->destroy($id)) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin::lang.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin::lang.delete_failed'),
            ]);
        }
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Post::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->column('title');
            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Post::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
