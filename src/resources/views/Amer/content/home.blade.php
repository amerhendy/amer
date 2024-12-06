@extends(Baseview('app'))
@push('header')
@endpush
@push('after_styles')
    <style>
        .requireinput{
            border-color: #0B90C4;
        }
        .select2-results__group{
        background-color:gray;
        }
        .has-error{
            border-color: rgb(185, 74, 72) !important;
        }
    </style>
@endpush
@section('content')
<div class="container">
    <div class="row text-center">
        <h3>
            قطاع تنمية الموارد البشرية
        </h3>
        <h3>
            بالشركة القابضة لمياه الشرب والصرف الصحى
         </h3>
         <h4>
         بالتعاون مع 
         <br>
        شركة مياه الشرب والصرف الصحى 
        <br>
        بشمال وجنوب سيناء
    </h4>
    <h5>
        الخدمات المتعلقة بالعاملين وحياتهم الوظيفية 
    </h5>
    </div>
    </div>
@endsection