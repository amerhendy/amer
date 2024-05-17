        @foreach($Amer->columns() as $column)
        <div class="row">
          <div class="col-sm-3 border border-light">{!! $column['label'] !!}</div>
          <div class="col-sm border border-light">
            
            @if(!isset($column['type']))
              @include(Baseview('columns.text'))
            @else
              @if(!view()->exists(Baseview('columns.'.$column['type'])))
                @include(Baseview('columns.text'))
              @else
                @include(Baseview('columns.'.$column['type']))
              @endif
            @endif
          </div>
        </div>
        
        @endforeach
