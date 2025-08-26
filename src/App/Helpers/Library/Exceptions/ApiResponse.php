<?php
namespace Amerhendy\Amer\App\Helpers\Library\Exceptions;
/**
 *     use ApiResponse;
 *
 try {
            $data = ['name' => 'عامر', 'role' => 'مدير'];
            return $this->successResponse($data, 'تم جلب البيانات بنجاح');
        } catch (\Throwable $e) {
            return $this->errorResponse('فشل في جلب البيانات');
        }
 */
trait ApiResponse
{
    /**
     * رد ناجح
     */
    protected function successResponse($data = null, $message = 'تم بنجاح', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * رد بخطأ
     */
    protected function errorResponse($message = 'حدث خطأ ما', $code = 500, $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
