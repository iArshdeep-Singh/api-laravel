<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Human;
use App\Models\Data;

class ApiController extends Controller
{

    public function test(Request $request)
    {
        return response()->json(["message" => "testing", "second message" => "testing again"]);
    }


    // registering user
    public function signup(Request $req)
    {

        // Create the user with hashed password
        $user = Human::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => $req->password,
        ]);

        // Return a JSON response with the created user
        return response()->json(['user' => $user], 201); // 201: Created
    }

    public function login(Request $req)
    {
        $user = Human::where('email', $req->email)->first();

        if ($user && $user->password === $req->password) {
            $user->access_token = "tok_" . Str::random(60);
            $user->save();

            return response()->json(['access_token' => $user->access_token], 201);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }


    public function logout(Request $req, $id)
    {
        $user = Human::find($id);
        $token = $req->header('Authorization');

        if (!$user) {
            return response()->json(["message" => "User not found."], 404);
        }

        if ($user->access_token !== $token) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        $user->access_token = null;
        $user->save();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }

    // ---------------------------------------------------------------------------------------- -

    public function create(Request $req)
    {
        $data = Data::create([
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
        ]);

        return response()->json(["message" => "Data created successfully.", 'data' => $data], 201);
    }

    public function read()
    {
        $data = Data::all();

        return response()->json(["data" => $data], 200);
    }

    public function readById(Request $req, $id)
    {
        $datum = Data::find($id);

        if (!$datum) {
            return response()->json(["message" => "Invalid Id."], 401);
        }

        return response()->json(["data" => $datum], 200);
    }

    public function update(Request $req, $id)
    {
        $data = Data::find($id);

        if (!$data) {
            return response()->json(["message" => "Data not found."], 404);
        }

        $data->update($req->only(['name', 'email', 'phone']));

        return response()->json(["message" => "Data updated successfully", "data" => $data], 200);
    }

    public function delete(Request $req, $id)
    {
        $data = Data::find($id);

        if (!$data) {
            return response()->json(["message" => "Data not found."], 404);
        }

        $data->delete();

        return response()->json(["message" => "Data deleted successfully."], 200);
    }
}
