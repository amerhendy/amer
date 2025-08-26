<?php
namespace Amerhendy\Amer\App\Helpers\Library\Exceptions;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiExceptionHelper
{
    public static function handle($exception)
    {
        // QueryException - أخطاء قاعدة البيانات
        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1] ?? null;

            switch ($errorCode) {
                case 1062:
                    return [
                        'message' => 'القيمة موجودة مسبقًا ولا يمكن تكرارها.',
                        'status' => 409
                    ];
                case 1451:
                case 1452:
                    return [
                        'message' => 'لا يمكن تنفيذ العملية بسبب ارتباط البيانات بجداول أخرى.',
                        'status' => 409
                    ];
                default:
                    return [
                        'message' => 'حدث خطأ أثناء الاتصال بقاعدة البيانات.',
                        'status' => 500
                    ];
            }
        }

        // Model Not Found
        if ($exception instanceof ModelNotFoundException) {
            return [
                'message' => 'العنصر المطلوب غير موجود.',
                'status' => 404
            ];
        }

        // غير ذلك: خطأ غير متوقع
        return [
            'message' => 'حدث خطأ غير متوقع. الرجاء المحاولة لاحقًا.',
            'status' => 500
        ];
    }
}
