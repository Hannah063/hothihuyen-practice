<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all posts",
     *     tags={"Get all posts"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $posts = DB::table('posts')->get();
        $arr = [
            'status' => true,
            'message' => "Thành công",
            'data' => $posts
        ];
        return response()->json($arr, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     tags = {"Create a new post"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="post's name",
     *         required=true,
     *         @OA\Schema(type="string", )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="post's description",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="Successfully"),
     *     @OA\Response(response="400", description="Errors")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:100|min:5',
            'description' => 'required|max:50|min:10',
        ], [
            'title.required' => 'Title bắt buộc phải nhập',
            'title.min' => 'Title phải từ :min ký tự trở lên',
            'title.max' => 'Title phải từ :max ký tự trở lên',
            'title.unique' => 'Title đã tồn tại trên hệ thống',
            'description.required' => 'Description bắt buộc phải nhập',
            'description.min' => 'Description phải từ :min ký tự trở lên',
            'description.max' => 'Description phải từ :max ký tự trở lên',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
            ];
            $post = DB::table('posts')->insert($data);
            if ($post) {
                $arr = [
                    'status' => true,
                    'message' => "Thành công",
                    'data' => $post
                ];
                return response()->json($arr, 200);
            } else {
                $arr = [
                    'status' => false,
                    'message' => "Thất bại",
                    'data' => $post
                ];
                return response()->json($arr, 400);
            }
        }
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get a post",
     *     tags = {"Get a post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="post's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Errors"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show($id)
    {
        $post = DB::table('posts')->where('id', $id)->first();
        if ($post) {
            $arr = [
                'status' => true,
                'message' => "Thành công",
                'data' => $post
            ];
        } else {
            $arr = [
                'status' => false,
                'message' => "Thất bại",
                'data' => $post
            ];
        }
        return response()->json($arr, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update a post",
     *     tags = {"Update a post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="post's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="post's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="post's description",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="Successfully"),
     *     @OA\Response(response="400", description="Errors")
     * )
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:100|min:5',
            'description' => 'required|max:50|min:10',
        ], [
            'title.required' => 'Title bắt buộc phải nhập',
            'title.min' => 'Title phải từ :min ký tự trở lên',
            'title.max' => 'Title phải từ :max ký tự trở lên',
            'title.unique' => 'Title đã tồn tại trên hệ thống',
            'description.required' => 'Description bắt buộc phải nhập',
            'description.min' => 'Description phải từ :min ký tự trở lên',
            'description.max' => 'Description phải từ :max ký tự trở lên',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
            ];
            $post = DB::table('posts')->where('id', $id)->update($data);
            if ($post) {
                $arr = [
                    'status' => true,
                    'message' => "Thành công",
                    'data' => $post
                ];
            } else {
                $arr = [
                    'status' => false,
                    'message' => "Thất bại",
                    'data' => $post
                ];
            }
            return response()->json($arr, 200);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a post",
     *     tags = {"Delete a post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="post's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Errors"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($id)
    {
        $post = DB::table('posts')->where('id', $id)->delete();
        if ($post) {
            return response()->json('Thành công', 200);
        } else {
            return response()->json('Thất bại', 400);
        }
    }
}
