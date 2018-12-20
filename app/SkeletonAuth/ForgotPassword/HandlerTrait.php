<?php

namespace SkeletonAuth\ForgotPassword;

use App\Mailers\ResetPasswordLink;
use App\Models\AuthToken;
use Psr\Http\Message\ResponseInterface as Response;

trait HandlerTrait
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

        $fullname = $user->first_name . " " . $user->last_name;
        $link = base_url("auth/reset-password/" . $authToken->token);

        $registerVerification = new ResetPasswordLink($fullname, $user->email, $link);
        $recipient_nums = $registerVerification->send();

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