<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

/** 
 * @OA\Info(
  *             title="Sistama de Logueo",
  *             version="1.0",
  *             description="Sistema de prática con los estudiantes de quinto nivel"
  * )
  *
  * @OA\Server(url="http://127.0.0.1:8000/")
   * @OA\SecurityScheme(
   *     type="http",
   *     description="Login with email and password to get the authentication token",
   *     name="Token based Based",
   *     in="header",
   *     scheme="bearer",
   *     bearerFormat="JWT",
   *     securityScheme="apiAuth",
   * )
*/

class apicontroller extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/usuario",
     *     tags={"Usuario"},
     *     @OA\Response(
     *         response=200,
     *         description="ok",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="nombres",
     *                         type="string",
     *                         example="Aderson Felix"
     *                     ),
     *                     @OA\Property(
     *                         property="apellidos",
     *                         type="string",
     *                         example="Jara Lazaro"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $usuario =User::where('estado',false)->get();
        return response()->json(['usuario'=>$usuario],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
      * @OA\Post(
     *     path="/api/usuario",
     *     tags={"Usuario"},
     *     summary="Registo usuario",
     *     operationId="idcreate",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="nombre del usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Correo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
    *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Contrasena",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="tipos_id",
     *         in="query",
     *         description="Tipo de usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok"
     *     ),
     *     @OA\Response(
     *         response="402",
     *         description="Campos requeridos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validate= Validator::make($request->all(),
          [
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
            'tipos_id'=>'required'
          ]);
          if ($validate->fails()){
            return response()->json([
            'status'=>false,
            'message'=>'Existen campos vacios',
            'errors'=>$validate->errors()
            ],401);
          }
          $usuario = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'tipos_id'=>$request->tipos_id

          ]);
          return response()->json([
            'message'=>'Usuario creado correctamente',
            'token'=>$usuario->createToken("API TOKEN")->plainTextToken
          ],201);

    }
    /**
      *  @OA\Post(
      *     path="/api/logeo",
      *     tags={"Usuario"},
      *     summary="Authenticate user and generate JWT token",
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
      *     @OA\Response(response="200", description="Login successful"),
      *     @OA\Response(response="401", description="Invalid credentials")
      * )
       */ 
 
    public function logear(Request $request){
        $validate= Validator::make($request->all(),
        [
          'email'=>'required',
          'password'=>'required'
        ]);
        if ($validate->fails()){
          return response()->json([
          'status'=>false,
          'message'=>'Existen campos vacios',
          'errors'=>$validate->errors()
          ],401);
        }
        if(!Auth::attempt($request->only(['email','password']))){
            return response()->json([
                'status'=>false,
                'message'=>'Email & Password  incorrectas'
            ],401);
        }
        $user =User::where('email', $request->email)->first();

        return response()->json([
            'status'=>true,
            'message'=>'usuario logeado correctamente',
            'token'=>$user->createToken("API TOKEN")->plaintexttoken
        ],200);
   }
    
    /**
     * @OA\get(
      *     path="/api/usuario/search/{name}",
      *     tags={"Usuario"},
      *     summary="buscar usuario",
      *     @OA\Parameter(
      *         name="name",
      *         in="path",
      *         description="ingreso nombre a buscar",
      *         required=true,
      *         @OA\Schema(type="string")
      *     ),
      * @OA\Response(response="200", description="search successful"),
      *   @OA\Response(response="401", description="Invalid search")
      *)
     */
    // busqueda por nombre la funcion show
    public function show(string $name)
    {
        $usuario= User::where('name',$name)->get();
        return response()->json(['usuario'=>$usuario]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    
    public function update(Request $request, string $id)
    {
      $validate= Validator::make($request->all(),
      [
        'name'=>'required',
        'email'=>'required|email|unique:users,email',
        'password'=>'required'
      ]);
      if ($validate->fails()){
        return response()->json([
        'status'=>false,
        'message'=>'Existen campos vacios',
        'errors'=>$validate->errors()
        ],401);
      }

      $usuaio=User::find($id);
      if (!$usuaio) {
        return response()->json([
          'message'=>'Usuario no existe'
        ],404);
      }
        $usuaio->name = $request->name;
        $usuaio->email = $request->email;
        $usuaio->password = $request->password;

        $usuaio->save();
      
      return response()->json([
        'message'=>'Usuario actulizado correctamente'
      ],200);
    }

    /**
     * @OA\delete(
      *     path="/api/usuario/{id}",
      *     tags={"Usuario"},
      *     summary="eliminar usuario",
      *     @OA\Parameter(
      *         name="id",
      *         in="path",
      *         description="ingreso de id a elminar",
      *         required=true,
      *         @OA\Schema(type="string")
      *     ),
      * @OA\Response(response="200", description="destroy successful"),
      *   @OA\Response(response="401", description="Invalid destroy")
     *)
     */
    public function destroy(string $id)
    {
      if(!$id){
        return response()->json([
          'message'=>'El codigo de usuario no existe'
        ],401);
      }
        $usuario=User::find($id);
        if($usuario){
          $usuario->estado=true;
          $usuario->save();
          return response()->json([
            'message'=>'El Usuario Eliminado'
          ],200);
        }
      else{
        return response()->json([
          'message'=>'El Usuario no existe'
        ],404);
      }  
    }
}
