<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    always cant be null
    bigIncrements
    bigInteger
    binary
    
     */
    
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
                'Annonce_id'=>['foreignId'=>'Employment_StartAnnonces'],
                'Code'=>['char'=>[10],'nullable'],'Name'=>['string','nullable'],'JobName'=>['string','nullable'],'JobDescription'=>['string','nullable'],
                'Slug'=>['string','unique'],'Functional_id'=>['foreignId'=>'Employment_FunctionalClass'],'Count'=>['unsignedInteger','default'=>0],'AgeIn'=>['date'],'Age'=>['unsignedInteger','default'=>35],
                'Experince'=>['unsignedInteger','default'=>0],'Driver'=>['enum'=>[0,1],'default'=>1],'Statue'=>['enum'=>['Published', 'Draft']],
            ],
            'Employment_Mir'=>['Father'=>['unsignedInteger','nullable','default'=>0],'Text'=>['string'],],
            'Employment_PagesDinamic'=>['Name'=>['string'],'Control'=>['string'],'Function'=>['string'],],
            'Employment_PagesStatic'=>['Title'=>['string'],'Content'=>['longText'],'Data'=>['longText','nullable'],],
            'Employment_People'=>[
                'Annonce_id'=>['foreignId'=>'Employment_StartAnnonces'],
                'Stage_id'=>['foreignId'=>'Employment_Stages'],
                'Job_id'=>['foreignId'=>'Employment_Job'],
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
                'BornGov'=>['foreignId'=>'Governorates'],
                'BornCity'=>['foreignId'=>'Cities'],
                'LiveGov'=>['foreignId'=>'Governorates'],
                'LiveCity'=>['foreignId'=>'Cities'],
                'LiveAddress'=>['string','nullable'],'LandLine'=>['string','nullable'],'Mobile'=>['string','nullable'],'Email'=>['string','nullable'],
                'Health_id'=>['foreignId'=>'Employment_Health','nullable'],
                'Mir_id'=>['foreignId'=>'Employment_Mir','nullable'],
                'Arm_id'=>['foreignId'=>'Employment_Arm','nullable'],
                'Ama_id'=>['foreignId'=>'Employment_Ama','nullable'],
                'Education_id'=>['foreignId'=>'Employment_Education','nullable'],
                'EducationYear'=>['string','nullable'],'Experince'=>['unsignedInteger','default'=>0],'InsuranceNumber'=>['unsignedInteger','nullable','default'=>0],'FileName'=>['string'],
                'DriverDegree_id'=>['foreignId'=>'Employment_Driver','nullable'],'DriverStart'=>['date','nullable'],'DriverEnd'=>['date','nullable'],'Result'=>['longText','nullable'],'Message'=>['longText','nullable'],
            ],
            'Employment_PeopleNewData'=>[
                'Uid'=>['foreignId'=>'Employment_People'],
                'Stage_id'=>['foreignId'=>'Employment_Stages'],
                'Job_id'=>['foreignId'=>'Employment_Job','nullable'],
                'FName'=>['string'],
                'SName'=>['string'],
                'TName'=>['string'],
                'LName'=>['string'],
                'BornGov'=>['foreignId'=>'Governorates'],
                'BornCity'=>['foreignId'=>'Cities'],
                'LiveGov'=>['foreignId'=>'Governorates'],
                'LiveCity'=>['foreignId'=>'Cities'],
                'LiveAddress'=>['string','nullable'],
                'LandLLine'=>['string','nullable'],
                'Mobile'=>['string','nullable'],
                'Email'=>['string','nullable'],
                'Health_id'=>['foreignId'=>'Employment_Health','nullable'],
                'Mir_id'=>['foreignId'=>'Employment_Mir','nullable'],
                'Arm_id'=>['foreignId'=>'Employment_Arm','nullable'],
                'Ama_id'=>['foreignId'=>'Employment_Ama','nullable'],
                'Education_id'=>['foreignId'=>'Employment_Education','nullable'],
                'EducationYear'=>['string','nullable'],
                'Experince'=>['unsignedInteger','nullable'],
                'InsuranceNumber'=>['unsignedInteger','nullable'],
                'FileName'=>['string','nullable'],
                'DriverDegree_id'=>['foreignId'=>'Employment_Driver','nullable'],
                'DriverStart'=>['date','nullable'],
                'DriverEnd'=>['date','nullable'],
                'Result'=>['longText','nullable'],
                'Statue'=>['enum'=>[1,2],'nullable'],
            ],
            'Employment_PeopleDegree'=>[
                'Uid'=>['foreignId'=>'Employment_People','unique'],
                'Written'=>['float','nullable'],
                'Practical'=>['float','nullable'],
                'Interview'=>['float','nullable'],
            ],
            'Employment_PeopleNewStage'=>[
                'Uid'=>['foreignId'=>'Employment_People'],
                'NewStatue'=>['foreignId'=>'Employment_Status'],
                'NewMessage'=>['longText','nullable'],
                'Stage_id'=>['foreignId'=>'Employment_Stages'],
            ],
            'Employment_Places'=>[
                'Text'=>['string'],
            ],
            'Employment_Qualifications'=>[
                'Type'=>['enum'=>['Private','Public']],
                'Text'=>['text'],
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
                'Stage_id'=>['foreignId'=>['Employment_Stages']],
                'Slug'=>['string','unique'],
                'Statue'=>['enum'=>['Published','Draft']],
            ],
            'Cities'=>[
                'Gov_id'=>['foreignId'=>['Governorates']],
                'Name'=>['string'],
                'English'=>['string'],
            ],
            'Employment_Grievance'=>[
                'Uid'=>['foreignId'=>['Employment_People']],
                'Stage_id'=>['foreignId'=>['Employment_Stages']]
            ],
            'employment_Job_Ama'=>[
                'Job_id'=>['foreignId'=>['Employment_Job']],
                'Ama_id'=>['foreignId'=>['Employment_Ama']]
            ],
            'employment_Job_Arm'=>[
                'Job_id'=>['foreignId'=>['Employment_Job']],
                'Arm_id'=>['foreignId'=>['Employment_Arm']]
            ],
            'employment_Job_City'=>[
                'Job_id'=>['foreignId'=>['Employment_Job']],
                'City_id'=>['foreignId'=>['Employment_City']]
            ],
            'employment_Job_Driver'=>['Job_id'=>['foreignId'=>['Employment_Job']],'Driver_id'=>['foreignId'=>['Employment_Driver']]],
            'employment_Job_Education'=>['Job_id'=>['foreignId'=>['Employment_Job']],'Education_id'=>['foreignId'=>['Employment_Education']]],
            'employment_Job_Health'=>['Job_id'=>['foreignId'=>['Employment_Job']],'Health_id'=>['foreignId'=>['Employment_Health']]],
            'employment_Job_IncludedFiles'=>['Job_id'=>['foreignId'=>['Employment_Job']],'IncludedFiles_id'=>['foreignId'=>['Employment_IncludedFiles']]],
            'employment_Job_Instructions'=>['Job_id'=>['foreignId'=>['Employment_Job']],'Instructions_id'=>['foreignId'=>['Employment_Instructions']]],
            'employment_Job_Mir'=>['Job_id'=>['foreignId'=>['Employment_Job']],'Mir_id'=>['foreignId'=>['Employment_Mir']]],
            'employment_Job_Places'=>['Job_id'=>['foreignId'=>['Employment_Job']],'Place_id'=>['foreignId'=>['Employment_Places']],'Count'=>['unsignedInteger']],
            'employment_Job_Qualifications'=>['Job_id'=>['foreignId'=>['Employment_Job']],'Qualification_id'=>['foreignId'=>['Employment_Qualifications']]],
            'employment_startannonces_Qualifications'=>['Annonce_id'=>['foreignId'=>['Employment_StartAnnonces']],'Qualification_id'=>['foreignId'=>['Employment_Qualifications']]],
            'employment_startannonces_Governorates'=>['Annonce_id'=>['foreignId'=>['Employment_StartAnnonces']],'Governorate_id'=>['foreignId'=>['Governorates']]],
            
            ]
    ];
    public $forign=[
        'Employment_Grievance'=>['Uid'=>'Employment_People','Stage_id'=>'Employment_Stages'],
        'Cities'=>['Gov_id'=>'Governorates'],
        'Employment_StartAnnonces'=>['Stage_id'=>'Employment_Stages'],
        'Employment_Places'=>['Job_id'=>'Employment_Job'],
        'Employment_PeopleNewStage'=>['Stage_id'=>'Employment_Stages'],
        'Employment_PeopleDegree'=>['Uid'=>'Employment_People'],
        'Employment_PeopleNewData'=>['Uid'=>'Employment_People','Stage_id'=>'Employment_Stages','Job_id'=>'Employment_Job','Born_gov'=>'Governorates','Born_city'=>'Cities','Live_gov'=>'Governorates','Live_city'=>'Cities','Health_id'=>'Employment_Health','Mir_id'=>'Employment_Mir','Arm_id'=>'Employment_Arm','Ama_id'=>'Employment_Ama','Education_id'=>'Employment_Education','DriverDegree_id'=>'Employment_Driver'],
        'Employment_People'=>['Annonce_id'=>'Employment_StartAnnonces','Stage_id'=>'Employment_Stages','Job_id'=>'Employment_Job','Born_gov'=>'Governorates','Born_city'=>'Cities','Live_gov'=>'Governorates','Live_city'=>'Cities','Health_id'=>'Employment_Health','Mir_id'=>'Employment_Mir','Arm_id'=>'Employment_Arm','Ama_id'=>'Employment_Ama','Education_id'=>'Employment_Education','DriverDegree_id'=>'Employment_Driver'],
        'Employment_Job'=>['Annonce_id'=>'Employment_StartAnnonces','Functional_id'=>'Employment_FunctionalClass'],
        'Employment_Annonce'=>['Annonce_id'=>'Employment_StartAnnonces'],  
    ];
    public function up(): void
    {
        
        Schema::create('Employment_Driver', function (Blueprint $table) {
            $table->id();
            $table->string('Text')->unique();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Ama', function (Blueprint $table) {
            $table->id();
            $table->string('Text')->unique();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Arm', function (Blueprint $table) {
            $table->id();
            $table->string('Text')->unique();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Education', function (Blueprint $table) {
            $table->id();
            $table->string('Text')->unique();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_FunctionalClass', function (Blueprint $table) {
            $table->id();
            $table->string('Text')->unique();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Health', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('Father')->nullable();
        $table->string('Text')->unique();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Annonce', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Annonce_id');
        $table->longText('Text');
        $table->enum('Statue',['Published','Draft']);
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_IncludedFiles', function (Blueprint $table) {
            $table->id();
            $table->string('Text');
        $table->enum('Checked',['0','1','2']);
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Instructions', function (Blueprint $table) {
            $table->id();
            $table->string('Text');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Job', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Annonce_id');
        $table->char('Code',10)->nullable();
        $table->string('Name')->nullable();
        $table->string('JobName')->nullable();
        $table->string('JobDescription')->nullable();
        $table->string('Slug')->unique();
        $table->foreignId('Functional_id');
        $table->unsignedInteger('Count')->default(0);
        $table->date('AgeIn');
        $table->unsignedInteger('Age')->default(35);
        $table->unsignedInteger('Experince')->default(0);
        $table->enum('Driver',['0','1'])->default(1);
        $table->enum('Statue',['Published','Draft']);
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Mir', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('Father')->nullable()->default(0);
        $table->string('Text');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_PagesDinamic', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
        $table->string('Control');
        $table->string('Function');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_PagesStatic', function (Blueprint $table) {
            $table->id();
            $table->string('Title');
        $table->longText('Content');
        $table->longText('Data')->nullable();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_People', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Annonce_id');
        $table->foreignId('Stage_id');
        $table->foreignId('Job_id');
        $table->unsignedBigInteger('Nid');
        $table->enum('Sex',['0','1']);
        $table->date('BirthDate');
        $table->unsignedInteger('AgeYears')->nullable();
        $table->unsignedInteger('AgeMonths')->nullable();
        $table->unsignedInteger('AgeDays')->nullable();
        $table->string('FName');
        $table->string('SName');
        $table->string('TName');
        $table->string('LName');
        $table->foreignId('BornGov');
        $table->foreignId('BornCity');
        $table->foreignId('LiveGov');
        $table->foreignId('LiveCity');
        $table->string('LiveAddress')->nullable();
        $table->string('LandLine')->nullable();
        $table->string('Mobile')->nullable();
        $table->string('Email')->nullable();
        $table->foreignId('Health_id')->nullable();
        $table->foreignId('Mir_id')->nullable();
        $table->foreignId('Arm_id')->nullable();
        $table->foreignId('Ama_id')->nullable();
        $table->foreignId('Education_id')->nullable();
        $table->string('EducationYear')->nullable();
        $table->unsignedInteger('Experince')->default(0);
        $table->unsignedInteger('InsuranceNumber')->nullable()->default(0);
        $table->string('FileName');
        $table->foreignId('DriverDegree_id')->nullable();
        $table->date('DriverStart')->nullable();
        $table->date('DriverEnd')->nullable();
        $table->longText('Result')->nullable();
        $table->longText('Message')->nullable();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_PeopleNewData', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Uid');
        $table->foreignId('Stage_id');
        $table->foreignId('Job_id')->nullable();
        $table->string('FName');
        $table->string('SName');
        $table->string('TName');
        $table->string('LName');
        $table->foreignId('BornGov');
        $table->foreignId('BornCity');
        $table->foreignId('LiveGov');
        $table->foreignId('LiveCity');
        $table->string('LiveAddress')->nullable();
        $table->string('LandLLine')->nullable();
        $table->string('Mobile')->nullable();
        $table->string('Email')->nullable();
        $table->foreignId('Health_id')->nullable();
        $table->foreignId('Mir_id')->nullable();
        $table->foreignId('Arm_id')->nullable();
        $table->foreignId('Ama_id')->nullable();
        $table->foreignId('Education_id')->nullable();
        $table->string('EducationYear')->nullable();
        $table->unsignedInteger('Experince')->nullable();
        $table->unsignedInteger('InsuranceNumber')->nullable();
        $table->string('FileName')->nullable();
        $table->foreignId('DriverDegree_id')->nullable();
        $table->date('DriverStart')->nullable();
        $table->date('DriverEnd')->nullable();
        $table->longText('Result')->nullable();
        $table->enum('Statue',['1','2'])->nullable();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_PeopleDegree', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Uid')->unique();
        $table->float('Written')->nullable();
        $table->float('Practical')->nullable();
        $table->float('Interview')->nullable();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_PeopleNewStage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Uid');
        $table->foreignId('NewStatue');
        $table->longText('NewMessage')->nullable();
        $table->foreignId('Stage_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Places', function (Blueprint $table) {
            $table->id();
            $table->string('Text');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Qualifications', function (Blueprint $table) {
            $table->id();
            $table->enum('Type',['Private','Public']);
        $table->string('Text');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Stages', function (Blueprint $table) {
            $table->id();
            $table->string('Title');
        $table->unsignedInteger('Days')->nullable();
        $table->string('Page');
        $table->enum('Front',['0','1']);
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Status', function (Blueprint $table) {
            $table->id();
            $table->string('Text')->unique();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Governorates', function (Blueprint $table) {
            $table->id();
            $table->string('Name')->unique();
        $table->string('English')->unique();
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_ApplyLog', function (Blueprint $table) {
            $table->id();
            $table->jsonb('userData');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_StartAnnonces', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('Number');
        $table->unsignedInteger('Year');
        $table->longText('Description');
        $table->foreignId('Stage_id');
        $table->string('Slug')->unique();
        $table->enum('Statue',['Published','Draft']);
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Gov_id');
        $table->string('Name');
        $table->string('English');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('Employment_Grievance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Uid');
        $table->foreignId('Stage_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Ama', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Ama_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Arm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Arm_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_City', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('City_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Driver', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Driver_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Education_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Health', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Health_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_IncludedFiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('IncludedFiles_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Instructions_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Mir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Mir_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Place_id');
        $table->unsignedInteger('Count');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_Job_Qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Job_id');
        $table->foreignId('Qualification_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_startannonces_Qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Annonce_id');
        $table->foreignId('Qualification_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::create('employment_startannonces_Governorates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Annonce_id');
        $table->foreignId('Governorate_id');
            $table->nullableTimestamps(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        Schema::table('Employment_Annonce', function (Blueprint $table) {
                $table->foreign('Annonce_id')->references('id')->on('Employment_StartAnnonces');});
        Schema::table('Employment_Job', function (Blueprint $table) {
                $table->foreign('Annonce_id')->references('id')->on('Employment_StartAnnonces');    	$table->foreign('Functional_id')->references('id')->on('Employment_FunctionalClass');});
        Schema::table('Employment_People', function (Blueprint $table) {
                $table->foreign('Annonce_id')->references('id')->on('Employment_StartAnnonces');    	$table->foreign('Stage_id')->references('id')->on('Employment_Stages');    	$table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('BornGov')->references('id')->on('Governorates');    	$table->foreign('BornCity')->references('id')->on('Cities');    	$table->foreign('LiveGov')->references('id')->on('Governorates');    	$table->foreign('LiveCity')->references('id')->on('Cities');    	$table->foreign('Health_id')->references('id')->on('Employment_Health');    	$table->foreign('Mir_id')->references('id')->on('Employment_Mir');    	$table->foreign('Arm_id')->references('id')->on('Employment_Arm');    	$table->foreign('Ama_id')->references('id')->on('Employment_Ama');    	$table->foreign('Education_id')->references('id')->on('Employment_Education');    	$table->foreign('DriverDegree_id')->references('id')->on('Employment_Driver');});
        Schema::table('Employment_PeopleNewData', function (Blueprint $table) {
                $table->foreign('Uid')->references('id')->on('Employment_People');    	$table->foreign('Stage_id')->references('id')->on('Employment_Stages');    	$table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('BornGov')->references('id')->on('Governorates');    	$table->foreign('BornCity')->references('id')->on('Cities');    	$table->foreign('LiveGov')->references('id')->on('Governorates');    	$table->foreign('LiveCity')->references('id')->on('Cities');    	$table->foreign('Health_id')->references('id')->on('Employment_Health');    	$table->foreign('Mir_id')->references('id')->on('Employment_Mir');    	$table->foreign('Arm_id')->references('id')->on('Employment_Arm');    	$table->foreign('Ama_id')->references('id')->on('Employment_Ama');    	$table->foreign('Education_id')->references('id')->on('Employment_Education');    	$table->foreign('DriverDegree_id')->references('id')->on('Employment_Driver');});
        Schema::table('Employment_PeopleDegree', function (Blueprint $table) {
                $table->foreign('Uid')->references('id')->on('Employment_People');});
        Schema::table('Employment_PeopleNewStage', function (Blueprint $table) {
                $table->foreign('Uid')->references('id')->on('Employment_People');    	$table->foreign('NewStatue')->references('id')->on('Employment_Status');    	$table->foreign('Stage_id')->references('id')->on('Employment_Stages');});
        Schema::table('Employment_StartAnnonces', function (Blueprint $table) {
                $table->foreign('Stage_id')->references('id')->on('Employment_Stages');});
        Schema::table('Cities', function (Blueprint $table) {
                $table->foreign('Gov_id')->references('id')->on('Governorates');});
        Schema::table('Employment_Grievance', function (Blueprint $table) {
                $table->foreign('Uid')->references('id')->on('Employment_People');    	$table->foreign('Stage_id')->references('id')->on('Employment_Stages');});
        Schema::table('employment_Job_Ama', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Ama_id')->references('id')->on('Employment_Ama');});
        Schema::table('employment_Job_Arm', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Arm_id')->references('id')->on('Employment_Arm');});
        Schema::table('employment_Job_City', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('City_id')->references('id')->on('Cities');});
        Schema::table('employment_Job_Driver', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Driver_id')->references('id')->on('Employment_Driver');});
        Schema::table('employment_Job_Education', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Education_id')->references('id')->on('Employment_Education');});
        Schema::table('employment_Job_Health', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Health_id')->references('id')->on('Employment_Health');});
        Schema::table('employment_Job_IncludedFiles', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('IncludedFiles_id')->references('id')->on('Employment_IncludedFiles');});
        Schema::table('employment_Job_Instructions', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Instructions_id')->references('id')->on('Employment_Instructions');});
        Schema::table('employment_Job_Mir', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Mir_id')->references('id')->on('Employment_Mir');});
        Schema::table('employment_Job_Places', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Place_id')->references('id')->on('Employment_Places');});
        Schema::table('employment_Job_Qualifications', function (Blueprint $table) {
                $table->foreign('Job_id')->references('id')->on('Employment_Job');    	$table->foreign('Qualification_id')->references('id')->on('Employment_Qualifications');});
        Schema::table('employment_startannonces_Qualifications', function (Blueprint $table) {
                $table->foreign('Annonce_id')->references('id')->on('Employment_StartAnnonces');    	$table->foreign('Qualification_id')->references('id')->on('Employment_Qualifications');});
        Schema::table('employment_startannonces_Governorates', function (Blueprint $table) {
                $table->foreign('Annonce_id')->references('id')->on('Employment_StartAnnonces');    	$table->foreign('Governorate_id')->references('id')->on('Governorates');});
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach($this->maintables[0] as $a=>$b){
            Schema::dropIfExists($a);
        }
    }
};
