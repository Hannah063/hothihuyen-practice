<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     tags={"Get all users"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $users = DB::table('users')->get();
        $arr = [
            'status' => true,
            'message' => "Thành công",
            'data' => $users
        ];
        return response()->json($arr, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags = {"Create a new user"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="password_confirmation",
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
            'name' => 'required|string|min:3|max:15',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ], [
            'name.required' => 'Họ và tên bắt buộc phải nhập',
            'name.string' => 'Họ và tên bắt buộc là string',
            'name.min' => 'Họ và tên phải từ :min ký tự trở lên',
            'name.max' => 'Họ và tên phải nhỏ hơn :max ký tự',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            'email.string' => 'Email bắt buộc là string',
            'password.required' => 'Password bắt buộc phải nhập',
            'password.string' => 'Password bắt buộc là string',
            'password.confirmed' => 'Password xác nhận không đúng',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];
            $user = DB::table('users')->insert($data);
            if ($user) {
                return response()->json('Thành công', 200);
            } else {
                return response()->json('Thất bại', 400);
            }
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a user",
     *     tags = {"Get a user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id",
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
        $user = DB::table('users')->where('id', $id)->first();
        if ($user) {
            $arr = [
                'status' => true,
                'message' => "Thành công",
                'data' => $user
            ];
        } else {
            $arr = [
                'status' => false,
                'message' => "Thất bại",
                'data' => $user
            ];
        }
        return response()->json($arr, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     tags = {"Update a user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user's id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="password_confirmation",
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
            'name' => 'required|string|min:3|max:15',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ], [
            'name.required' => 'Họ và tên bắt buộc phải nhập',
            'name.string' => 'Họ và tên bắt buộc là string',
            'name.min' => 'Họ và tên phải từ :min ký tự trở lên',
            'name.max' => 'Họ và tên phải nhỏ hơn :max ký tự',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            'email.string' => 'Email bắt buộc là string',
            'password.required' => 'Password bắt buộc phải nhập',
            'password.string' => 'Password bắt buộc là string',
            'password.confirmed' => 'Password xác nhận không đúng',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];
            $user = DB::table('users')->where('id', $id)->update($data);
            if ($user) {
                $arr = [
                    'status' => true,
                    'message' => "Thành công",
                    'data' => $user
                ];
            } else {
                $arr = [
                    'status' => false,
                    'message' => "Thất bại",
                    'data' => $user
                ];
            }
            return response()->json($arr, 200);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     tags = {"Delete a user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="user's id",
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
        $user = DB::table('users')->where('id', $id)->delete();
        if ($user) {
            return response()->json('Thành công', 200);
        } else {
            return response()->json('Thất bại', 400);
        }
    }
}
