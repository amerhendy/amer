<?php
namespace Amerhendy\Amer\App\Helpers;
use Illuminate\Support\Arr;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use amerhendy\Amer\App\Helpers\AmerHelper;
class migrate extends Migration{
    public $indexedcols=[
                'bigIncrements','mediumIncrements','smallIncrements','tinyIncrements','foreignId','foreignIdFor','foreignUlid','foreignUuid',
                'id','increments','ulid','uuid',
            ];
    public $indexmodifier=[
        'after'=>['column_name'],
        'autoIncrement'=>[],'first'=>[],'invisible'=>[],'unsigned'=>[],'useCurrent'=>[],'useCurrentOnUpdate'=>[],'always'=>[],'isGeometry'=>[],
        'charset'=>['charset'],'collation'=>['charset'],
        'comment'=>['value'],'default'=>'value','from'=>['value'],
        'nullable'=>[true],'storedAs'=>['expression'],'virtualAs'=>['expression'],'generatedAs'=>['expression']
    ];
        public $indexedallowedmodi=['unique'];
    public $columntypes=[
/////////////////
        'bigIncrements'=>['column_name'],'mediumIncrements'=>['column_name'],'smallIncrements'=>['column_name'],'tinyIncrements'=>['column_name'],
        'foreignId'=>['column_name'],'foreignIdFor'=>['class_name'],
        'foreignUlid'=>['class_name'],'foreignUuid'=>['class_name'],
        'id'=>[],
        'increments'=>['column_name'],
        'ulid'=>['column_name'],'uuid'=>['column_name'],
///////////////////
        'integer'=>['column_name'],'bigInteger'=>['column_name'],'mediumInteger'=>['column_name'],'smallInteger'=>['column_name'],'tinyInteger'=>['column_name'],
        'unsignedBigInteger'=>['column_name'],'unsignedInteger'=>['column_name'],'unsignedMediumInteger'=>['column_name'],'unsignedSmallInteger'=>['column_name'],'unsignedTinyInteger'=>['column_name'],
        'decimal'=>['column_name','precision','scale'],'double'=>['column_name','precision','scale'],'float'=>['column_name','precision','scale'],
        'unsignedDecimal'=>['column_name','precision','scale'],
//////////////////////
        'binary'=>['column_name'],
//////////////////////
        'boolean'=>['column_name'],
////////////////////////
        'char'=>['column_name','length'],
        //'lineString'=>['column_name'],'multiLineString'=>['column_name'],
        'text'=>['column_name'],'longText'=>['column_name'],'mediumText'=>['column_name'],'tinyText'=>['column_name'],
        'string'=>['column_name','length'],
///////////////////////////////////////////
        'json'=>['column_name'],'jsonb'=>['column_name'],'rememberToken'=>[],
///////////////////////////////////////////////
        'ipAddress'=>['column_name'],'macAddress'=>['column_name'],
////////////////////////////////////
        'dateTimeTz'=>['column_name','precision'],
        'dateTime'=>['column_name','precision'],
        'date'=>['column_name'],'year'=>['column_name'],
        'nullableTimestamps'=>[0],
        'softDeletesTz'=>['column_name','precision'],'softDeletes'=>['column_name','precision'],
        'timeTz'=>['column_name','precision'],'time'=>['column_name','precision'],
        'timestampTz'=>['column_name','precision'],'timestamp'=>['column_name','precision'],'timestampsTz'=>[0],'timestamps'=>[0],
///////////////////////////////////////
        'enum'=>['column_name','choice'],'set'=>['column_name','choice'],
    ];
    public function fromarray($tables){
        $tables=$tables[0];
        foreach($tables as $a=>$b){
            Schema::create($a, function (Blueprint $table) use($b){
                $table->id();
            foreach($b as $c=>$d){
                $columntyp=$this->getcolumntype($d);
                if(is_array($columntyp)){dd("column type not found :". $columntyp['error']);}
                $d['column_name']=$c;
                $d=$this->getprecisionscale($d,$columntyp);
                $this->prep($d,$table);
            }
            $table->nullableTimestamps();
            $table->softDeletes($column = 'deleted_at');
        });
        }
        foreach($tables as $a=>$b){
            Schema::table($a, function (Blueprint $table) use($b){
                foreach($b as $c=>$d){
                    $columntyp=$this->getcolumntype($d);
                    $d['column_name']=$c;
                    $d=$this->getprecisionscale($d,$columntyp);
                    $this->indexModifier($d);
                }
            });
        }
    }
    public function getprecisionscale($d,$columntyp){
        $colvals=$this->columntypes[$columntyp];
        $columnname=null;
        $class=null;
        $length=0;
        $precision =0;
        $scale=0;
        $d['column_type']=$columntyp;
        if(in_array($columntyp,['decimal','double','float','unsignedDecimal'])){$precision =8;$scale=2;$targettext=['precision','scale'];}
        if(in_array($columntyp,['char','string'])){
            $length=255;
            $targettext=['length'];
        }
        if(in_array($columntyp,['dateTimeTz','dateTime','softDeletesTz','softDeletes','timeTz','time','timestampTz','timestamp'])){
            $precision=0;
            $targettext=['precision'];
        }
        if(in_array($columntyp,['enum','set'])){
            $aol=Arr::where($d,function($v,$k){
                return is_array($v);
            });
            if(empty($aol)){dd("no $columntyp choice please add list of choice as Array");}
            $d['choice']=$aol[$columntyp];
        }
        if(isset($targettext)){
            foreach($targettext as $a=>$b){
                if(in_array($b,$colvals)){
                    if(in_array($b,$d)){$result=(int) $d[$b];}
                    elseif(isset($d[$columntyp][$b])){$result=(int) $d[$columntyp][$b];}
                    elseif(isset($d[$columntyp])){$result=(int) $d[$columntyp];}else{
                        $result=$$b;
                    }
                    $d[$b]=$result;
                }
            }
        }
        return $this->getindexmodifier($d);
    }
    public function getcolumntype($d){
        $keyfirst=array_key_first($d);
        if(!is_numeric(array_key_first($d))){
            $wanted=$keyfirst;
        }else{
            $wanted=$d[0];
        }
        $ke=Arr::where($this->columntypes,function($v,$k)use($wanted){
            return $wanted==$k;
        });
        if(!$ke)return ['error'=>$wanted];return $wanted;
    }
    public function getindexmodifier($d){
        foreach($this->indexmodifier as $a=>$b){
            if(in_array($a,$d)){
                $wanted=$a;
            }
            if(array_key_exists($a,$d)){
                $wanted=$a;
            }
        }
        if(isset($wanted)){
            if($wanted == "nullable"){
                $d['index']=["nullable",0];
            }
        }
        return $d;
    }
    public function indexModifier($d){
        dd($d);
        $a=$table->getColumns();
        $autoincre='';
        foreach($a as $h){
            $htoarray=$h->toArray();
            if(array_key_exists('autoIncrement',$htoarray)){
                $autoincre='stop';
            }
        }
        $tableName=$table->getTable();
        if(in_array('unique',$d)){$uniquename=$tableName.'_'.$columnname;$uniquealgorithm=null;$table->unique($columnname,$uniquename,$uniquealgorithm);}
        if(array_key_exists('unique',$d)){
            if(isset($d['unique']['columns'])){$columnname=$d['unique']['columns'];}
            $uniquename=$tableName;
            if(isset($d['unique']['name'])){if($d['unique']['name'] !== ''){$uniquename.="_".$d['unique']['name'];}else{$uniquename.="_".$columnname;}}else{$uniquename.="_".$columnname;}
            if(isset($d['unique']['algorithm'])){$uniquealgorithm=$d['unique']['algorithm'];}else{$uniquealgorithm=null;}
            $table->unique($columnname,$uniquename,$uniquealgorithm);
        }
        if(in_array('autoIncrement',$d)){
            if($autoincre !== 'stop'){
                if(in_array($columntype,['integer','tinyInteger','smallInteger','mediumInteger','unsignedBigInteger','unsignedMediumInteger','unsignedSmallInteger','unsignedTinyInteger','unsignedInteger','bigInteger'])){
                    //$table->autoIncrement();
                }
            }else{
                
            }
        }
        if(in_array('nullable',$d)){if($columntype == 'dateTimeTz'){
                if(in_array('dateTimeTz',$d)){
                    $precision =0;
                }
                if(array_key_exists('dateTimeTz',$d)){$precision =$d['dateTimeTz'];}
                $table->dateTimeTz($columnname,(int) $precision )->nullable();
            }elseif($columntype == 'date'){
                $table->date($columnname)->nullable();
            }elseif($columntype == 'decimal'){
                if(in_array('decimal',$d)){$precision=8;$scale=2;}
                if(array_key_exists('decimal',$d)){
                    if(!isset($d['decimal']['precision'])){$precision=8;}else{$precision=$d['decimal']['precision'];}
                    if(!isset($d['decimal']['scale'])){$scale=2;}else{$scale=$d['decimal']['scale'];}
                }
                $table->decimal($columnname, $precision, $scale);
                
            }elseif($columntype == 'double'){
                if(in_array('double',$d)){$precision=8;$scale=2;}
                if(array_key_exists('double',$d)){
                    if(!isset($d['double']['precision'])){$precision=8;}else{$precision=$d['double']['precision'];}
                    if(!isset($d['double']['scale'])){$scale=2;}else{$scale=$d['double']['scale'];}
                }
                $table->double($columnname, $precision, $scale);
            }elseif($columntype == 'float'){
                if(in_array('float',$d)){$precision=8;$scale=2;}
                if(array_key_exists('float',$d)){
                    if(!isset($d['float']['precision'])){$precision=8;}else{$precision=$d['float']['precision'];}
                    if(!isset($d['float']['scale'])){$scale=2;}else{$scale=$d['float']['scale'];}
                }
                $table->double($columnname, $precision, $scale);
            }elseif($columntype == 'enum'){
                
            }else{
                    $table->$columntype($columnname)->nullable();
            }
            
        }
        if(array_key_exists('default',$d)){
            if($columntype == 'enum'){
                $table->enum($columnname,$d['enum'])->default($d['default']);
            }
        }
        //dd($table);
        if(isset($d['first'])){$table->first();}
        if(isset($d['invisible'])){$table->invisible();}

        if(isset($d['unsigned'])){$table->unsigned();}
        if(isset($d['useCurrent'])){$table->useCurrent();}
        if(isset($d['useCurrentOnUpdate'])){$table->useCurrentOnUpdate();}
        if(isset($d['always'])){$table->always();}
        if(isset($d['isGeometry'])){$table->isGeometry();}
        if(array_key_exists('after',$d)){$table->after($d['after'][0]);}
        if(array_key_exists('charset',$d)){$table->charset($d['charset'][0]);}
        if(array_key_exists('collation',$d)){$table->collation($d['collation'][0]);}
        if(array_key_exists('comment',$d)){$table->comment($d['comment'][0]);}
        
        if(array_key_exists('from',$d)){$table->from($d['from']);}
        
        if(array_key_exists('storedAs',$d)){$table->storedAs($d['storedAs'][0]);}
        if(array_key_exists('virtualAs',$d)){$table->virtualAs($d['virtualAs'][0]);}
        if(array_key_exists('generatedAs',$d)){$table->generatedAs($d['generatedAs'][0]);}
        return $table;
    }
    public function prep($d,$table) {
        $a=$table->getColumns();
        $columntype=$d["column_type"];
            //prepare indexes cols
            $wnatedvals=count($this->columntypes[$columntype]);
            if($wnatedvals == 0){
                return $table->$columntype();
            }
            elseif($wnatedvals == 1){
                if(in_array($columntype,['nullableTimestamps','timestampsTz','timestamps']))
                {
                    return $table->$columntype(0);
                }
                if(($columntype == 'foreignIdFor') || ($columntype == 'foreignUlid')){
                    if(isset($d['class'])){
                        $class=$d['class'];
                    }elseif(isset($d[$columntype])){
                        $class=$d[$columntype];
                    }else{
                        dd("Error : please assign the class for column type $columntype like that ` $columntype=classname");
                    }
                    $allmodels=AmerHelper::getModels();
                    $vos=Arr::where($allmodels,function($v,$k)use($class){
                        return $v['className'] == $class;
                    });
                    //dd($vos[0]);
                    if(!count($vos)){dd("Error : please assign the class ".$class." for column type $columntype in App/models/");}
                    
                    return $table->$columntype($vos[0]['callLink']);
                    //return $table->foreignIdFor("\\App\\Models\\User");
                }
                return $table->{$columntype}($d['column_name']);
            }else{
                if(in_array($columntype,['decimal','double','float','unsignedDecimal'])){
                    return $table->$columntype($d["column_name"],$d["precision"],$d["scale"]);
                }
                if(in_array($columntype,['char','string'])){
                    return $table->$columntype($d["column_name"],$d["length"]);
                }
                if(in_array($columntype,['dateTimeTz','dateTime','softDeletesTz','softDeletes','timeTz','time','timestampTz','timestamp'])){
                    return $table->$columntype($d["column_name"],$d["precision"]);
                }
                if(in_array($columntype,['enum','set'])){
                    return $table->$columntype($d["column_name"],$d["choice"]);
                }
            }
    }
    function createIndexName($array,$extenstion){
        $merge=$array;
        $mergeA=[];$mergeB=[];$lname=[];
        foreach($merge as $a=>$b){
            $perg=preg_split('/(?=[A-Z])/',$b);
            if($perg[0] == ''){unset($perg[0]);}
            $mergeA[]=$perg;
        }
        foreach($mergeA as $a=>$b){
            if(!is_array($b)){
                $mergeB[]=substr($b,0,3);
            }else{
                foreach($b as $c){
                    $mergeB[]=substr($c,0,3);
                }
            }
        }
        $mergeB=array_unique($mergeB);
        return implode('_',$mergeB).'_'.$extenstion;
    }
}



public $maintables=[
    [//,'nullable','unique','from'=>100
        'Employment_Driver'=>['Text'=>['string','unique']],//checked
        'Employment_Ama'=>['Text'=>['string','unique']],//checked
        'Employment_Arm'=>['Text'=>['string','unique']],//checked
        'Employment_Education'=>['Text'=>['string','unique']],//checked
        'Employment_FunctionalClass'=>['Text'=>['string','unique']],//checked
        'Employment_Health'=>['Father'=>['unsignedInteger','nullable'],'Text'=>['string','unique'],],//checked
        'Employment_Annonce'=>[
            'Annonce_id'=>['foreignId'=>'Employment_StartAnnonces'],
            'Text'=>['longText'],'Statue'=>['enum'=>['Published','Draft']],],
        'Employment_IncludedFiles'=>['Text'=>['string'],'Checked'=>['enum'=>[0,1,2]],],
        'Employment_Instructions'=>['Text'=>['string'],],
        'Employment_Job'=>[
            'Annonce_id'=>['foreignId'],
            'Code'=>['char'=>[10],'nullable'],'Name'=>['string','nullable'],'JobName'=>['string','nullable'],'JobDescription'=>['string','nullable'],
            'Slug'=>['string','unique'],'Functional_id'=>['foreignId'],'Count'=>['unsignedInteger','default'=>0],'AgeIn'=>['date'],'Age'=>['unsignedInteger','default'=>35],
            'Experince'=>['unsignedInteger','default'=>0],'Driver'=>['enum'=>[0,1],'default'=>1],'Statue'=>['enum'=>['Published', 'Draft']],
        ],
        'Employment_Mir'=>['Father'=>['unsignedInteger','nullable','default'=>0],'Text'=>['string'],],
        'Employment_PagesDinamic'=>['Name'=>['string'],'Control'=>['string'],'Function'=>['string'],],
        'Employment_PagesStatic'=>['Title'=>['string'],'Content'=>['longText'],'Data'=>['longText','nullable'],],
        'Employment_People'=>[
            'Annonce_id'=>['foreignId'],
            'Stage_id'=>['foreignId'],
            'Job_id'=>['foreignId'],
            'Nid'=>['unsignedBigInteger'],
            'Sex'=>['enum'=>[0,1]],
            'BirthDate'=>['date'],
            'AgeYears'=>['unsignedInteger','nullable'],
            'AgeMonths'=>['unsignedInteger','nullable'],
            'AgeDays'=>['unsignedInteger','nullable'],
            'FName'=>['string'],
            'SName'=>['string'],
            'TName'=>['string'],
            'LName'=>['string'],
            'BornGov'=>['foreignId'],
            'BornCity'=>['foreignId'],
            'LiveGov'=>['foreignId'],
            'LiveCity'=>['foreignId'],
            'LiveAddress'=>['string','nullable'],'LandLine'=>['string','nullable'],'Mobile'=>['string','nullable'],'Email'=>['string','nullable'],
            'Health_id'=>['foreignId','nullable'],
            'Mir_id'=>['foreignId','nullable'],
            'Arm_id'=>['foreignId','nullable'],
            'Ama_id'=>['foreignId','nullable'],
            'Education_id'=>['foreignId','nullable'],
            'EducationYear'=>['string','nullable'],'Experince'=>['unsignedInteger','default'=>0],'InsuranceNumber'=>['unsignedInteger','nullable','default'=>0],'FileName'=>['string'],
            'DriverDegree_id'=>['foreignId','nullable'],'DriverStart'=>['date','nullable'],'DriverEnd'=>['date','nullable'],'Result'=>['longText','nullable'],'Message'=>['longText','nullable'],
        ],
        'Employment_PeopleNewData'=>[
            'Uid'=>['foreignId'],
            'Stage_id'=>['foreignId'],
            'Job_id'=>['foreignId','nullable'],
            'FName'=>['string'],
            'SName'=>['string'],
            'TName'=>['string'],
            'LName'=>['string'],
            'BornGov'=>['foreignId'],
            'BornCity'=>['foreignId'],
            'LiveGov'=>['foreignId'],
            'LiveCity'=>['foreignId'],
            'LiveAddress'=>['string','nullable'],
            'LandLLine'=>['string','nullable'],
            'Mobile'=>['string','nullable'],
            'Email'=>['string','nullable'],
            'Health_id'=>['foreignId','nullable'],
            'Mir_id'=>['foreignId','nullable'],
            'Arm_id'=>['foreignId','nullable'],
            'Ama_id'=>['foreignId','nullable'],
            'Education_id'=>['foreignId','nullable'],
            'EducationYear'=>['string','nullable'],
            'Experince'=>['unsignedInteger','nullable'],
            'InsuranceNumber'=>['unsignedInteger','nullable'],
            'FileName'=>['string','nullable'],
            'DriverDegree_id'=>['foreignId','nullable'],
            'DriverStart'=>['date','nullable'],
            'DriverEnd'=>['date','nullable'],
            'Result'=>['longText','nullable'],
            'Statue'=>['enum'=>[1,2],'nullable'],
        ],
        'Employment_PeopleDegree'=>[
            'Uid'=>['foreignId','unique'],
            'Written'=>['float','nullable'],
            'Practical'=>['float','nullable'],
            'Interview'=>['float','nullable'],
        ],
        'Employment_PeopleNewStage'=>[
            'Uid'=>['foreignId'],
            'NewStatue'=>['foreignId'],
            'NewMessage'=>['longText','nullable'],
            'Stage_id'=>['foreignId'],
        ],
        'Employment_Places'=>[
            'Text'=>['string'],
        ],
        'Employment_Qualifications'=>[
            'Type'=>['enum'=>['Private','Public']],
            'Text'=>['string'],
        ],
        'Employment_Stages'=>[
            'Title'=>['string'],
            'Days'=>['unsignedInteger','nullable'],
            'Page'=>['string'],
            'Front'=>['enum'=>[0,1]],
        ],
        'Employment_Status'=>[
            'Text'=>['string','unique'],
        ],
        'Governorates'=>[
            'Name'=>['string','unique'],
            'English'=>['string','unique'],
        ],
        'Employment_ApplyLog'=>[
            'userData'=>['jsonb'],
        ],
        'Employment_StartAnnonces'=>[
            'Number'=>['unsignedInteger'],
            'Year'=>['unsignedInteger'],
            'Description'=>['longText'],
            'Stage_id'=>['foreignId'],
            'Slug'=>['string','unique'],
            'Statue'=>['enum'=>['Published','Draft']],
        ],
        'Cities'=>[
            'Gov_id'=>['foreignId'],
            'Name'=>['string'],
            'English'=>['string'],
        ],
        'Employment_Grievance'=>[
            'Uid'=>['foreignId'],
            'Stage_id'=>['foreignId']
        ],
        'employment_Job_Ama'=>[
            'Job_id'=>['foreignId'],
            'Ama_id'=>['foreignId']
        ],
        'employment_Job_Arm'=>[
            'Job_id'=>['foreignId'],
            'Arm_id'=>['foreignId']
        ],
        'employment_Job_City'=>[
            'Job_id'=>['foreignId'],
            'City_id'=>['foreignId']
        ],
        'employment_Job_Driver'=>['Job_id'=>['foreignId'],'Driver_id'=>['foreignId']],
        'employment_Job_Education'=>['Job_id'=>['foreignId'],'Education_id'=>['foreignId']],
        'employment_Job_Health'=>['Job_id'=>['foreignId'],'Health_id'=>['foreignId']],
        'employment_Job_IncludedFiles'=>['Job_id'=>['foreignId'],'IncludedFiles_id'=>['foreignId']],
        'employment_Job_Instructions'=>['Job_id'=>['foreignId'],'Instructions_id'=>['foreignId']],
        'employment_Job_Mir'=>['Job_id'=>['foreignId'],'Mir_id'=>['foreignId']],
        'employment_Job_Places'=>['Job_id'=>['foreignId'],'Place_id'=>['foreignId'],'Count'=>['unsignedInteger']],
        'employment_Job_Qualifications'=>['Job_id'=>['foreignId'],'Qualifications_id'=>['foreignId']],
        'employment_startannonces_Qualifications'=>['Annonce_id'=>['foreignId'],'Qualification_id'=>['foreignId']],
        'employment_startannonces_Governorates'=>['Annonce_id'=>['foreignId'],'Governorate_id'=>['foreignId']],
        
        ]
];