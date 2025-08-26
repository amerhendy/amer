<?php
namespace Amerhendy\Amer\App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use AmerHelper;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use ZipArchive;

class StorageController extends Controller
{
    protected $currentClass;

    protected $settings = [];
    protected $currentOperation;
    protected $routeMethod;
    protected $id;
    public function __construct() {
    }
    public function __invoke(Request $request)
    {
        //
    }
    public function index()
    {
        if(auth::guard('Employers')->check())
        {

            $route = Route::currentRouteName();
            $user=auth::guard('Employers')->user();
            $userinfo='';
            $user['JobName']=Mosama_JobName::where('JobTitle_id',$user->Mosama_JobTitles)->where('Degree_id',$user->Mosama_Degrees)->where('Group_id',$user->Mosama_Groups)->get(['text','id']);
            return view('layout.Employers.dashboard',['user'=>$user,'Regulations'=>Regulations::all()]);
        }else{
            return view(Amerview('content.Employers.home'));
        }

    }
    protected $historyFile = 'uploads/.history.json';

    private function logHistory($action, $file)
    {
        $history = [];
        if (\Storage::exists($this->historyFile)) {
            $history = json_decode(\Storage::get($this->historyFile), true);
        }

        $history[] = [
            'action' => $action,
            'file' => $file,
            'time' => now()->toDateTimeString(),
        ];

        \Storage::put($this->historyFile, json_encode($history));
    }

    public function list()
    {
        $files = collect(\Storage::files('uploads'))
            ->map(function ($path) {
                return ['name' => basename($path)];
            })
            ->values();

        return response()->json($files);
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $file->storeAs('uploads', $file->getClientOriginalName());
            $this->logHistory('رفع', $file->getClientOriginalName());
            return response()->json(['message' => 'تم الرفع']);
        }
        return response()->json(['error' => 'لم يتم الرفع'], 400);
    }

    public function createFolder(Request $request)
    {
        $folder = $request->input('name');
        if ($folder) {
            \Storage::makeDirectory("uploads/$folder");
            $this->logHistory('إنشاء مجلد', $folder);
            return response()->json(['message' => 'تم إنشاء المجلد']);
        }
        return response()->json(['error' => 'اسم المجلد مطلوب'], 400);
    }

    public function delete(Request $request)
    {
        $file = $request->input('file');
        if ($file && \Storage::exists("uploads/$file")) {
            \Storage::delete("uploads/$file");
            $this->logHistory('حذف', $file);
            return response()->json(['message' => 'تم الحذف']);
        }
        return response()->json(['error' => 'الملف غير موجود'], 404);
    }

    public function archive(Request $request)
    {
        $files = $request->input('files', []);
        if (count($files)) {
            $zip = new ZipArchive;
            $zipFile = 'uploads/archive_' . time() . '.zip';

            if ($zip->open(storage_path("app/$zipFile"), ZipArchive::CREATE) === TRUE) {
                foreach ($files as $file) {
                    $filePath = storage_path("app/uploads/$file");
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $file);
                    }
                }
                $zip->close();
                $this->logHistory('أرشفة', implode(',', $files));
                return response()->json(['message' => 'تمت الأرشفة', 'zip' => basename($zipFile)]);
            }
        }
        return response()->json(['error' => 'لم تتم الأرشفة'], 400);
    }

    public function unarchive(Request $request)
    {
        $file = $request->input('file');
        $zipPath = storage_path("app/uploads/$file");

        if (file_exists($zipPath)) {
            $zip = new ZipArchive;
            if ($zip->open($zipPath) === TRUE) {
                $zip->extractTo(storage_path('app/uploads'));
                $zip->close();
                $this->logHistory('فك أرشفة', $file);
                return response()->json(['message' => 'تم فك الأرشفة']);
            }
        }
        return response()->json(['error' => 'لم يتم فك الأرشفة'], 400);
    }

    public function history()
    {
        if (\Storage::exists($this->historyFile)) {
            return response()->json(json_decode(\Storage::get($this->historyFile), true));
        }
        return response()->json([]);
    }

    public function generateLink($file)
    {
        $filePath = "uploads/$file";
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'الملف غير موجود'], 404);
        }

        $slugAr = str_replace(' ', '-', pathinfo($file, PATHINFO_FILENAME));
        $slugEn = Str::slug($slugAr);
        $link = url("storage/$filePath");

        return response()->json([
            'name' => $file,
            'slug_ar' => $slugAr,
            'slug_en' => $slugEn,
            'link' => $link,
        ]);
    }

}
