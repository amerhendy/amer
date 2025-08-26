<?php

namespace Amerhendy\Amer\App\Helpers\Library\Exceptions;

use Exception;

class AccessDeniedException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if($request->headers->get('accept') == 'application/json'){
            return \AmerHelper::responseError(['message' =>$this->message ],403);
        }
        return response(view('errors.403', ['exception' => $this]), 403);
    }
}
