<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Models\AgentToken;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\AgentActivationRequest;
use App\Http\Requests\Api\AgentForgotPasswordRequest;
use App\Http\Requests\Api\AgentResetPasswordRequest;
use App\Http\Requests\Api\AgentUpdatePasswordRequest;
use App\Transformer\SuccessResponseTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use JWTAuth;
use Mockery\Exception;

class AuthController extends ApiController
{
    /**
     * Attiva un account utente
     *
     * @param AgentActivationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/activate",
     *   summary="activate user using token",
     *   security={},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"token", "password", "password_confirmation"},
     *       @OA\Property(property="token", type="string"),
     *       @OA\Property(property="password", type="string", format="password", default="123456789"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", default="123456789"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="access_token", type="string"),
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="role", type="integer", example="1", description="1 for leader, 0 for agent"),
     *       @OA\Property(property="token_type", type="string", default="bearer"),
     *       @OA\Property(property="expires_in", type="integer"),
     *     )
     *   ),
     *   @OA\Response(response=400, description="Bad request",
     *     @OA\JsonContent(type="object",
     *       @OA\Property(property="errors", type="object")
     *     )
     *   ),
     *   @OA\Response(response=422, description="Unprocessable Entity",
     *     @OA\JsonContent(type="object",
     *       @OA\Property(property="statusCode", type="integer"),
     *       @OA\Property(property="errors", type="string"),
     *     )
     *   )
     * )
     */
    public function activate(AgentActivationRequest $request)
    {
        try {
            $token = AgentToken::where('token', $request->token)->where('type', AgentToken::$TYPES['ACTIVATION'])->firstOrFail();
            /** @var Agent $agent */
            $agent = $token->agent;
            $authToken = JWTAuth::fromUser($agent);

            $agent->activate($token, $request->password);
            $agent->registerLastAccess();
        } catch (Exception $exception) {
            return response()->json([
                'statusCode' => $exception->getCode(),
                'errors' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(
            [
                'access_token' => $authToken,
                'name' => $agent->getName(),
                'role' => $agent->isGroupLeader(),
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60 * 30
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/login",
     *   summary="agent login",
     *   security={},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"email", "password"},
     *       @OA\Property(property="email", type="string", format="email", default="it@carplanner.com"),
     *       @OA\Property(property="password", type="string", format="password", default="123456789"),
     *       @OA\Property(property="provider", type="string", default="agents"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="access_token", type="string"),
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="role", type="integer", example="1", description="1 for leader, 0 for agent"),
     *       @OA\Property(property="token_type", type="string", default="bearer"),
     *       @OA\Property(property="expires_in", type="integer"),
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $agent = auth('api')->user();
        //Controllo se l'account e' attivo
        if (!$agent->isActive()) {
            return response()->json(
                [
                    'error' => 'Unauthorized',
                    'message' => $agent->status,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $agent->registerLastAccess();
        $name = $agent->getName();
        return response()->json([
            'access_token' => $token,
            'name' => $name,
            'role' => (int)auth('api')->user()->isGroupLeader(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 30
        ], Response::HTTP_OK);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Log the agent out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/logout",
     *   summary="agent logout",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(type="object",
     *       @OA\Property(property="message", type="string", default="Successfully logged out")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not found",
     *     @OA\JsonContent(type="object",
     *       @OA\Property(property="status", type="string", default="Authorization Token not found")
     *     )
     *   )
     * )
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }

    /**
     * Send request for resetting password
     *
     * @param AgentForgotPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Auth"},
     *   path="/password/reset",
     *   summary="send request for resetting password",
     *   security={},
     *   @OA\Parameter(
     *      name="email",
     *      required=true,
     *      in="path",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(type="object",
     *       @OA\Property(property="statusCode", type="integer", default="200")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Bad request",
     *     @OA\JsonContent(type="object",
     *       @OA\Property(property="statusCode", type="integer", default="400"),
     *       @OA\Property(property="errors", type="string"),
     *     )
     *   )
     * )
     */
    public function forgot(AgentForgotPasswordRequest $request)
    {
        try {
            $agent = Agent::where('email', $request->email)->firstOrFail();
            $token = AgentToken::generate($agent, AgentToken::$TYPES['PASSWORD_RESET']);
            $agent->sendResetPasswordEmailNotification($token);
        } catch (Exception $exception) {
            return response()->json([
                'statusCode' => $exception->getCode(),
                'errors' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(['statusCode' => Response::HTTP_OK], Response::HTTP_OK);
    }

    /**
     * Update password using token
     *
     * @param AgentResetPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
     *
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/password/reset",
     *   summary="update password using token",
     *   security={},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"token", "password", "password_confirmation"},
     *       @OA\Property(property="token", type="string"),
     *       @OA\Property(property="password", type="string", format="password", default="123456789"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", default="123456789"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="access_token", type="string"),
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="role", type="integer", example="1", description="1 for leader, 0 for agent"),
     *       @OA\Property(property="token_type", type="string", default="bearer"),
     *       @OA\Property(property="expires_in", type="integer"),
     *     )
     *   ),
     *   @OA\Response(response=400, description="Bad request",
     *     @OA\JsonContent(type="object",
     *       @OA\Property(property="errors", type="object")
     *     )
     *   ),
     *   @OA\Response(response=422, description="Unprocessable Entity",
     *     @OA\JsonContent(type="object",
     *       @OA\Property(property="statusCode", type="integer"),
     *       @OA\Property(property="errors", type="string"),
     *     )
     *   )
     * )
     */
    public function reset(AgentResetPasswordRequest $request)
    {
        try {
            $token = AgentToken::where('token', $request->token)->where('type', AgentToken::$TYPES['PASSWORD_RESET'])->firstOrFail();
            /** @var Agent $agent */
            $agent = $token->agent;
            $authToken = JWTAuth::fromUser($agent);
            $agent->updatePassword($token, $request->password);
            $agent->registerLastAccess();
        } catch (Exception $exception) {
            return response()->json([
                'statusCode' => $exception->getCode(),
                'errors' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(
            [
            'access_token' => $authToken,
            'name' => $agent->getName(),
            'role' => $agent->isGroupLeader(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 30
        ],
            Response::HTTP_OK
        );
    }

    /**
     * Name update
     *
     * @param AgentUpdatePasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
     *
     * @OA\Patch(
     *   tags={"Auth"},
     *   path="/password",
     *   summary="name update",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"password", "new_password", "confirm_password"},
     *       @OA\Property(property="password", type="string", format="password", description="current password"),
     *       @OA\Property(property="new_password", type="string", format="password"),
     *       @OA\Property(property="confirm_password", type="string", format="password"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK"
     *   ),
     *   @OA\Response(response="401", ref="#/components/schemas/Error500"),
     *   @OA\Response(response="500", ref="#/components/schemas/Error500"),
     * )
     */
    public function update(AgentUpdatePasswordRequest $request)
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();

        $password = $request->get('password');
        $newPassword = $request->get('confirm_password');

        try {
            $agent->updateAccessPassword($password, $newPassword);
        } catch (UnauthorizedException $exception) {
            return $this
                        ->setStatusCode(Response::HTTP_UNAUTHORIZED)
                        ->respondWithError($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        } catch (Exception $exception) {
            return $this
                        ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                        ->respondWithError($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respondWithItem([], new SuccessResponseTransformer);
    }


    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 30
        ], Response::HTTP_OK);
    }
}
