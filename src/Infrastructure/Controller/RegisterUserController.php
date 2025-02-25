<?php
namespace UserManager\Infrastructure\Controller;

use UserManager\Application\UseCase\RegisterUser\RegisterUserRequest;
use UserManager\Application\UseCase\RegisterUser\RegisterUserUseCase;
use UserManager\Domain\Exception\InvalidEmailException;
use UserManager\Domain\Exception\UserAlreadyExistsException;
use UserManager\Domain\Exception\WeakPasswordException;

final class RegisterUserController
{
    private RegisterUserUseCase $registerUserUseCase;

    public function __construct(RegisterUserUseCase $registerUserUseCase)
    {
        $this->registerUserUseCase = $registerUserUseCase;
    }

    public function __invoke(array $requestData): array
    {
        try {
            if (
                !isset($requestData['name']) ||
                !isset($requestData['email']) ||
                !isset($requestData['password'])
            ) {
                return $this->errorResponse('Missing required fields', 400);
            }

            $request = new RegisterUserRequest(
                $requestData['name'],
                $requestData['email'],
                $requestData['password']
            );

            $response = $this->registerUserUseCase->execute($request);

            return [
                'status' => 'success',
                'code' => 201,
                'data' => $response
            ];
        } catch (InvalidEmailException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (WeakPasswordException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (UserAlreadyExistsException $e) {
            return $this->errorResponse($e->getMessage(), 409);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->errorResponse('An unexpected error occurred', 500);
        }
    }

    private function errorResponse(string $message, int $code): array
    {
        return [
            'status' => 'error',
            'code' => $code,
            'message' => $message
        ];
    }
}