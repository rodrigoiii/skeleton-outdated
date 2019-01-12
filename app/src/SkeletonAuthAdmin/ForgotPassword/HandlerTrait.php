<?php

namespace SkeletonAuthAdmin\ForgotPassword;

use App\SkeletonAuthAdmin\Mailers\ResetPasswordLink;
use App\SkeletonAuthAdmin\Models\Admin;
use App\SkeletonAuth\Models\AuthToken;
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
        $admin = Admin::find($payload->admin_id);

        $fullname = $admin->getFullName();
        $link = base_url("auth-admin/reset-password/" . $authToken->token);

        $registerVerification = new ResetPasswordLink($fullname, $admin->email, $link);
        $recipient_nums = $registerVerification->send();

        if ($recipient_nums > 0)
        {
            \Log::info("Info: Forgot password link for ". $admin->getFullName() ." {$link}");
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
        return $response->withRedirect($this->router->pathFor('auth-admin.login'));
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
        return $response->withRedirect($this->router->pathFor('auth-admin.forgot-password'));
    }
}
