<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserDetailResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => ['required', Rule::in([1, 2, 3, 4])]
        ]);

        $user = User::create(
            $request->all()
        );

        return response()->json(['data' => $user]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100'
        ]);
        $user = User::find($id);
        $user->update([
            'name' => $request->name
        ]);

        return new UserDetailResource($user);
    }
}
