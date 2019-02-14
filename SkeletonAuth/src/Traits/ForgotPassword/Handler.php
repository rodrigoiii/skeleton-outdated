<?php

namespace SkeletonAuth\ForgotPassword;

use App\SkeletonAuth\Mailers\ResetPasswordLink;
use App\SkeletonAuth\Models\AuthToken;
use App\SkeletonAuth\Models\User;
use Psr\Http\Message\ResponseInterface as Response;

trait Handler
{
    /**
     * Send reset password link handler
     *
     * @param  AuthToken $authToken
     * @return integer
     */
    public function sendResetPasswordLink(AuthToken $authToken)
    {
        $payload = json_decode($authToken->getPayload());
        $user = User::find($payload->user_id);

        $fullname = $user->getFullName();
        $link = base_url("auth/reset-password/" . $authToken->token);

        $registerVerification = new ResetPasswordLink($fullname, $user->email, $link);
        $recipient_nums = $registerVerification->send();

        if ($recipient_nums > 0)
        {
            \Log::info("Info: Forgot password link for ". $user->getFullName() ." {$link}", 1);
        }

        return $recipient_nums;
    }

    /**
     * Success send email link handler
     *
     * @param  Response $response
     * @return Response
     */
    public function sendEmailLinkSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Success! Please check your email to reset your password.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    /**
     * Error send email link handler
     *
     * @param  Response $response
     * @return Response
     */
    public function sendEmailLinkError(Response $response)
    {
        \Log::error("Error: Sending email contains reset password link fail.");
        return $this->resetPasswordError($response);
    }

    /**
     * Error resetting password handler
     *
     * @param  Response $response
     * @return Response
     */
    public function resetPasswordError(Response $response)
    {
        $this->flash->addMessage('error', "Reset password not working properly this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.forgot-password'));
    }
}
